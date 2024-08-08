<?php

namespace App\Http\Controllers;

use App\Models\CrowdfundEvent;
use App\Models\Disability;
use App\Models\EducationLevel;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Enrollee;
use App\Models\PwdFeedback;
use App\Models\TrainingApplication;
use App\Models\Competency;
use App\Models\Skill;
use App\Models\SkillUser;
use App\Models\Experience;
use App\Notifications\ApplicationAcceptedNotification;
use App\Notifications\NewTrainingProgramNotification;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AgencyController extends Controller
{
    private function convertToNumber($number)
    {
        return (float) str_replace(',', '', $number);
    }

    public function showPrograms()
    {
        $userId = auth()->id();
        $programs = TrainingProgram::where('agency_id', $userId)
            ->latest()
            ->with('crowdfund')
            ->get();

        

        foreach ($programs as $program) {
            $endDate = new DateTime($program->end);
            $today = new DateTime();
            $interval = $today->diff($endDate);
            $program->remainingDays = $interval->days;

            $program->enrolleeCount = Enrollee::where('program_id', $program->id)
            ->count();

            $program->slots = $program->participants - $program->enrolleeCount;

            if ($program->crowdfund) {
                $raisedAmount = $program->crowdfund->raised_amount ?? 0; // Default to 0 if raised_amount is null
                $goal = $program->crowdfund->goal ?? 1; // Default to 1 to avoid division by zero
                $progress = ($goal > 0) ? round(($raisedAmount / $goal) * 100, 2) : 0; // Calculate progress percentage
                $program->crowdfund->progress = $progress;
            }
        }
        return view('agency.manageProg', compact('programs'));
    }

    public function showProgramDetails($id)
    {
        $program = TrainingProgram::findOrFail($id);
        $userId = auth()->id();
        $reviews = PwdFeedback::where('program_id', $id)->with('pwd')->latest()->get();
        $applications = TrainingApplication::where('training_program_id', $program->id)->get();
        $requests = TrainingApplication::where('training_program_id', $program->id)->where('application_status', 'Pending')->get();
        $enrollees = Enrollee::where('program_id', $program->id)->get();

        $pendingsCount = $applications->where('application_status', 'Pending')->count();
        $ongoingCount = $enrollees->where('completion_status', 'Ongoing')->count();
        $completedCount = $enrollees->where('completion_status', 'Completed')->count();
        $enrolleesCount = $enrollees->count();

        if ($program->crowdfund) {
            $raisedAmount = $program->crowdfund->raised_amount ?? 0;
            $goal = $program->crowdfund->goal ?? 1;
            $progress = ($goal > 0) ? round(($raisedAmount / $goal) * 100, 2) : 0;
            $program->crowdfund->progress = $progress;
        }
        return view('agency.showProg', compact('program', 'applications', 'reviews', 'enrollees', 'pendingsCount', 'ongoingCount', 'completedCount', 'enrolleesCount', 'requests'));
    }

    public function showAddForm()
    {
        $disabilities = Disability::all();
        $levels = EducationLevel::all();
        $skills = Skill::all();
        return view('agency.addProg', compact('disabilities', 'levels', 'skills'));
    }

    public function addProgram(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_age' => 'integer|min:1|max:99',
            'end_age' => 'integer|min:1|max:99',
            'participants' => 'required|max:255',
            // 'disability' => 'required|exists:disabilities,id',
            // 'education' => 'required|exists:education_levels,id',
            'goal' => 'nullable|string',
            'competencies' => 'array|max:4',
            'competencies.*' => 'string|distinct',
        ]);

        $participants = $this->convertToNumber($request->participants);



        // try {
        $trainingProgram = TrainingProgram::create([
            'agency_id' => auth()->id(),
            'title' => $request->title,
            'state' => $request->state,
            'city' => $request->city,
            'description' => $request->description,
            'start' => $request->start_date,
            'end' => $request->end_date,
            'disability_id' => $request->disability,
            'education_id' => $request->education,
            'skill_id' => $request->skills,
            'start_age' => $request->start_age,
            'end_age' => $request->end_age,
            'participants' => $participants,
        ]);

        if ($request->has('competencies')) {
            $competencies = $request->competencies;
            $competencyIds = [];

            foreach ($competencies as $competency) {
                $existingCompetency = Competency::firstOrCreate(['name' => $competency]);
                $competencyIds[] = $existingCompetency->id;
            }

            // Attach competencies to the training program
            $trainingProgram->competencies()->sync($competencyIds);
        }


        //NOTIFY PWD USERS!!!
        $pwdUsers = User::whereHas('role', function ($query) {
            $query->where('role_name', 'PWD');
        })->get();

        foreach ($pwdUsers as $user) {
            $user->notify(new NewTrainingProgramNotification($trainingProgram));
        }

        if ($request->has('goal') && $request->goal !== null) {
            $goal = $this->convertToNumber($request->goal);
            CrowdfundEvent::create([
                'program_id' => $trainingProgram->id,
                'goal' => $goal,
            ]);
        }

        return redirect()->route('programs-manage')->with('success', 'Training program created successfully!');
        // } catch (\Exception $e) {
        //     return back()->with('error', 'Failed to create training program. Review form.');
        // }
        // Create a new training program

    }

    public function deleteProgram($id)
    {
        $program = TrainingProgram::find($id);

        if ($program && $program->agency_id == auth()->id()) {
            // Find and delete related notifications
            DB::table('notifications')
                ->where('data', 'like', '%"training_program_id":' . $id . '%')
                ->delete();

            $program->delete();
            return redirect()->route('programs-manage')->with('success', 'Training program deleted successfully');
        } else {
            return redirect()->route('programs-manage')->with('error', 'Failed to delete training program');
        }
    }

    public function editProgram($id)
    {
        $program = TrainingProgram::find($id);

        if (!$program || $program->agency_id != auth()->id()) {
            return redirect()->route('programs-manage');
        }

        // Fetch provinces and cities
        $provinceResponse = file_get_contents('https://psgc.cloud/api/provinces');
        $provinces = json_decode($provinceResponse, true);

        // Fetch disabilities and education levels
        $disabilities = Disability::all();
        $levels = EducationLevel::all();
        $skills = Skill::all();

        // Return the view with all required data
        return view('agency.editProg', compact('program', 'provinces', 'disabilities', 'levels', 'skills' ));

        // return redirect()->route('programs-manage');
    }

    public function updateProgram(Request $request, $id)
    {
        $program = TrainingProgram::find($id);

        if ($program && $program->agency_id == auth()->id()) {
            $request->validate([
                'title' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'goal' => 'nullable|string',
                'competencies' => 'array|max:4',
                'competencies.*' => 'string|distinct',
                'skills' => 'required|exists:skills,id',
                'start_age' => 'integer|min:1|max:99',
                'end_age' => 'integer|min:1|max:99',
            ]);

            $program->update([
                'title' => $request->title,
                'state' => $request->state,
                'city' => $request->city,
                'description' => $request->description,
                'start' => $request->start_date,
                'end' => $request->end_date,
                'disability_id' => $request->disability,
                'education_id' => $request->education,
                'skill_id' => $request->skills,
                'start_age' => $request->start_age,
                'end_age' => $request->end_age,

            ]);

            if ($request->has('competencies')) {
                $competencies = $request->competencies;
                $competencyIds = [];

                foreach ($competencies as $competency) {
                    $existingCompetency = Competency::firstOrCreate(['name' => $competency]);
                    $competencyIds[] = $existingCompetency->id;
                }

                // Attach competencies to the training program
                $program->competencies()->sync($competencyIds);
            }

            if ($request->has('goal') && $request->goal !== null) {
                $crowdfundEvent = $program->crowdfund;
                $goal = $this->convertToNumber($request->goal);

                if ($crowdfundEvent) {
                    $crowdfundEvent->update([
                        'goal' => $goal,
                    ]);
                } else {
                    CrowdfundEvent::create([
                        'program_id' => $program->id,
                        'goal' => $goal,
                    ]);
                }
            } else {
                // If goal is not present, it means the crowdfund checkbox is unchecked, so delete the crowdfund event if it exists
                if ($program->crowdfund) {
                    $program->crowdfund->delete();
                }
            }

            return redirect()->route('programs-show', $id)->with('success', 'Training program has been updated successfully!');
        } else {
            return back()->with('error', 'Failed to update training program. Review form.');
        }
    }

    public function showCalendar(Request $request)
    {

        $user = auth()->user()->userInfo->user_id;
        log::info($user);

        if ($request->ajax()) {
            $data = TrainingProgram::where('agency_id', $user)
                ->whereDate('start', '>=', $request->start)
                ->whereDate('end', '<=', $request->end)
                ->get(['agency_id', 'title', 'start', 'end']);

            return response()->json($data);
        }

        return view('agency.calendar');
    }

    public function accept(Request $request)
    {
        Log::info("Reached accept method");

        // Validate the incoming request
        $validatedData = $request->validate([
            'pwd_id' => 'required|exists:users,id',
            'program_id' => 'required|exists:training_programs,id',
            'training_application_id' => 'required|exists:training_applications,id',
        ]);

        $pwdId = $validatedData['pwd_id'];
        $programId = $validatedData['program_id'];
        $applicationId = $validatedData['training_application_id'];
        $completionStatus = 'Ongoing';

        // Find the application by training_id
        $application = TrainingApplication::findOrFail($applicationId);
        $application->application_status = 'Approved';
        $application->save();

        $pwdUser = $application->user;
        $trainingProgram = $application->program;

        $pwdUser->notify(new ApplicationAcceptedNotification($trainingProgram));


        // Create Enrollee record
        Enrollee::create([
            'pwd_id' => $pwdId,
            'program_id' => $programId,
            'training_application_id' => $applicationId,
            'completion_status' => $completionStatus,
        ]);

        $application->update(['application_status' => 'Approved']);

        // return response()->json(['success' => true, 'message' => 'Application submitted successfully.']);
        return back()->with('success', 'Application is accepted');
    }

    public function application(Request $request)
    {

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'training_program_id' => 'required|exists:training_programs,id',
            'application_status' => 'required|in:Pending,Approved,Denied',
        ]);

        TrainingApplication::create($validatedData);

        return response()->json(['success' => true, 'message' => 'Application submitted successfully.']);
    }

    public function markComplete(Request $request)
    {
        $validatedData = $request->validate([
            'enrolleeId' => 'required|exists:enrollees,id'
        ]);

        $enrolleeId = $validatedData['enrolleeId'];
        $completionStatus = 'Completed';

        $enrollee = Enrollee::findOrFail($enrolleeId);
        $enrollee->update(['completion_status' => $completionStatus]);

        return back()->with('success', 'Enrollee completed the training');
    }



    // public function action(Request $request)
    // {
    //     log::info("calendar reach in action!");
    //     if($request->ajax())
    // 	{
    // 		if($request->type == 'add')
    // 		{
    // 			$event = TrainingProgram::create([
    // 				'title'		=>	$request->title,
    // 				'start'		=>	$request->start,
    // 				'end'		=>	$request->end
    // 			]);

    // 			return response()->json($event);
    // 		}

    // 		if($request->type == 'update')
    // 		{
    // 			$event = TrainingProgram::find($request->id)->update([
    // 				'title'		=>	$request->title,
    // 				'start'		=>	$request->start,
    // 				'end'		=>	$request->end
    // 			]);

    // 			return response()->json($event);
    // 		}

    // 		if($request->type == 'delete')
    // 		{
    // 			$event = TrainingProgram::find($request->id)->delete();

    // 			return response()->json($event);
    // 		}
    // 	}
    // }

    //TEMPORARY LOGIC
    public function showEnrolleeProfile($id)
    {
        $user = User::find($id);
        $skilluser = SkillUser::where('user_id', $id)->get();
        $certifications = Enrollee::where('pwd_id', $id)->where('completion_status', 'Completed')->get();
        $experiences = Experience::where('user_id', $id)->get();
        // $enrollees = Enrollee::where('user_id', $user)->get();
        return view('agency.pwdProfile', compact('user', 'certifications', 'skilluser', 'experiences'));
    }
}
