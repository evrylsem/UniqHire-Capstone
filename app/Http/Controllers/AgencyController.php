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
use App\Notifications\NewTrainingProgramNotification;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AgencyController extends Controller
{
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
        $applications = TrainingApplication::whereHas('program', function ($query) use ($userId) {
            $query->where('agency_id', $userId);
        })->get();

        if ($program->crowdfund) {
            $raisedAmount = $program->crowdfund->raised_amount ?? 0; // Default to 0 if raised_amount is null
            $goal = $program->crowdfund->goal ?? 1; // Default to 1 to avoid division by zero
            $progress = ($goal > 0) ? round(($raisedAmount / $goal) * 100, 2) : 0; // Calculate progress percentage
            $program->crowdfund->progress = $progress;
        }
        return view('agency.showProg', compact('program', 'applications', 'reviews'));
    }

    public function showAddForm()
    {
        $disabilities = Disability::all();
        $levels = EducationLevel::all();
        return view('agency.addProg', compact('disabilities', 'levels'));
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
            // 'disability' => 'required|exists:disabilities,id',
            // 'education' => 'required|exists:education_levels,id',
            'goal' => 'nullable|numeric',
            'competencies' => 'array|max:4',
            'competencies.*' => 'string|distinct',
        ]);

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
            CrowdfundEvent::create([
                'program_id' => $trainingProgram->id,
                'goal' => $request->goal,
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

        // Return the view with all required data
        return view('agency.editProg', [
            'program' => $program,
            'provinces' => $provinces,
            'disabilities' => $disabilities,
            'levels' => $levels,
        ]);

        return redirect()->route('programs-manage');
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
                'goal' => 'nullable|numeric',
                'competencies' => 'array|max:4',
                'competencies.*' => 'string|distinct',
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

                if ($crowdfundEvent) {
                    $crowdfundEvent->update([
                        'goal' => $request->goal,
                    ]);
                } else {
                    CrowdfundEvent::create([
                        'program_id' => $program->id,
                        'goal' => $request->goal,
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
        log::info("nakaabot sa accept");
        $validatedData = $request->validate([
            'training_application_id' => 'required|exists:training_applications,training_id',
            'completion_status' => 'required|in:Completed,Ongoing,Not completed',
        ]);

        Enrollee::create([
            'training_application_id' => $validatedData['training_application_id'],
            'completion_status' => $validatedData['completion_status']
        ]);

        return response()->json(['success' => true, 'message' => 'Application submitted successfully.']);

        // return back()->with('success', 'Application submitted successfully');
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
        $enrollees =
            Enrollee::where('user_id', $id)->get();
        return view('auth.profile', compact('user', 'enrollees'));
    }
}
