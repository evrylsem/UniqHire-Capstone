<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserInfo;
use App\Models\TrainingProgram;
use App\Models\Disability;
use App\Models\EducationLevel;
use App\Models\TrainingApplication;
use App\Http\Requests\StoreUserInfoRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Models\Enrollee;
use App\Models\PwdFeedback;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;


class PwdController extends Controller
{

    public function showPrograms(Request $request)
    {
        $user = auth()->user()->userInfo;
        $disabilities = Disability::all();
        $educations = EducationLevel::all();
        $query = TrainingProgram::query();

        // Filtering the programs through searching program title
        if ($request->filled('search')) {
            $query->where("title", "LIKE", "%" . $request->search . "%");
        }

        // Filtering the programs based on disability [multiple selection]
        if (isset($request->disability) && ($request->disability != null)) {
            $query->whereHas('disability', function ($q) use ($request) {
                $q->whereIn('disability_name', $request->disability);
            });
        }

        if (isset($request->education) && ($request->education != null)) {
            $query->whereHas('education', function ($q) use ($request) {
                $q->whereIn('education_name', $request->education);
            });
        }

        $filteredPrograms = $query->get();


        $rankedPrograms = [];

        foreach ($filteredPrograms as $program) {
            $similarity = $this->calculateSimilarity($user, $program);
            $rankedPrograms[] = [
                'program' => $program,
                'similarity' => $similarity
            ];
        }



        // Sorting the programs based on similarity score [ascending]
        usort($rankedPrograms, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $currentItems = array_slice($rankedPrograms, ($currentPage - 1) * $perPage, $perPage);
        $paginatedItems = new LengthAwarePaginator($currentItems, count($rankedPrograms), $perPage);
        $paginatedItems->setPath($request->url());

        // $viewName = $request->input('view', 'pwd.listPrograms');

        return view('pwd.listPrograms', compact('paginatedItems', 'disabilities', 'educations'));
    }

    private function calculateSimilarity($user, $program)
    {
        $similarityScore = 0;

        // Criteria: disability, location

        if ($user->disability_id === $program->disability_id) {
            $similarityScore += 1;
        }

        if ($user->city === $program->city) {
            $similarityScore += 1;
        }

        return $similarityScore;
    }

    public function showDetails($id)
    {
        $program = TrainingProgram::with('agency.userInfo', 'disability', 'education', 'crowdfund')->findOrFail($id);
        $userId = auth()->user()->id;
        $application = TrainingApplication::where('user_id', $userId)
            ->where('training_program_id', $program->id)
            ->first();
        $reviews = PwdFeedback::where('program_id', $id)->with('pwd')->latest()->get();
        $enrollees = Enrollee::where('training_program_id', $program->id)->where('completion_status', 'Ongoing');

        if ($program->crowdfund) {
            $raisedAmount = $program->crowdfund->raised_amount ?? 0; // Default to 0 if raised_amount is null
            $goal = $program->crowdfund->goal ?? 1; // Default to 1 to avoid division by zero
            $progress = ($goal > 0) ? round(($raisedAmount / $goal) * 100, 2) : 0; // Calculate progress percentage
            $program->crowdfund->progress = $progress;
        }
        return view('pwd.show', compact('program', 'reviews', 'application', 'enrollees'));


        // $program = TrainingProgram::with('agency.userInfo', 'disability', 'education')->findOrFail($id);
        // $userId = auth()->user()->id; // Get the authenticated user's ID
        
        // // Get the application status for the specific program
        // $application = TrainingApplication::where('user_id', $userId)
        //     ->where('training_program_id', $id)
        //     ->first();
        // $applicationStatus = $application ? $application->application_status : 'Apply';

        //  // Check if the user has any pending or approved applications
        // $hasPendingOrApproved = TrainingApplication::where('user_id', $userId)
        // ->whereIn('application_status', ['Pending', 'Approved'])
        // ->exists();

        // Log::info('User ID ' . $userId . ' has pending or approved applications: ' . ($hasPendingOrApproved ? 'true' : 'false'));
     
        // return response()->json([
        //     'program' => $program,
        //     'application_status' => $applicationStatus,
        //     'has_pending_or_approved' => $hasPendingOrApproved
        // ]);
    }

    public function showCalendar(Request $request)
    {
        Log::info("calendar reached in showCalendar!");

        $userId = Auth()->user()->id;

        if ($request->ajax()) {
            // Get the training programs based on the enrollee's application for the authenticated user
            $trainingPrograms = TrainingProgram::whereIn('id', function ($query) use ($userId) {
                $query->select('training_program_id')
                    ->from('training_applications')
                    ->whereIn('training_id', function ($query) use ($userId) {
                        $query->select('training_application_id')
                            ->from('enrollees')
                            ->where('user_id', $userId);
                    });
            })
                ->whereDate('start', '>=', $request->start)
                ->whereDate('end', '<=', $request->end)
                ->get(['id', 'title', 'start', 'end']);

            return response()->json($trainingPrograms);
        }

        return view('pwd.calendar');
    }

    public function application(Request $request)
    {

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'training_program_id' => 'required|exists:training_programs,id',
        ]);

        $validatedData['application_status'] = 'Pending';
        TrainingApplication::create($validatedData);

        return back()->with('confirmation', 'Do you really want to apply for this training program?');
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

    public function showTrainings() {
        $trainings = TrainingProgram::all(); //temporary since wala pay ongoing ug completed
        return view('pwd.trainings', compact('trainings'));
    }

    public function rateProgram(Request $request) {
        $request->validate([
            'program_id' => 'required|exists:training_programs,id',
            'rating' => 'required|integer|between:1,5',
            'content' => 'string|max:1000',
        ]);

        try {
            PwdFeedback::create([
                'program_id' => $request->program_id,
                'pwd_id' => $request->user()->id,
                'rating' => $request->rating,
                'content' => $request->content,
            ]);
            // return response()->json(['success' => true, 'message' => 'Feedback submitted successfully.']);
            return back()->with('success', 'Thank you for leaving us a review!');
        } catch (\Exception $e) {
            // return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
            return back()->with('error', 'An error occurred while submitting your feedback. Please try again later.');
        }
    }
}
