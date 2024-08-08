<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserInfo;
use App\Models\TrainingProgram;
use App\Models\Disability;
use App\Models\EducationLevel;
use App\Models\TrainingApplication;
use App\Models\User;
use App\Models\SkillUser;
use App\Http\Requests\StoreUserInfoRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Models\Enrollee;
use App\Models\PwdFeedback;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Notifications\PwdApplicationNotification;
use Illuminate\Support\Facades\Auth;

class PwdController extends Controller
{

    public function showPrograms(Request $request)
    {
        $user = auth()->user()->userInfo;
        $disabilities = Disability::all();
        $educations = EducationLevel::all();
        $query = TrainingProgram::query();

        $approvedProgramIds = TrainingApplication::where('user_id', auth()->id())
            ->where('application_status', 'Approved')
            ->pluck('training_program_id')
            ->toArray();

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

        $query->whereNotIn('id', $approvedProgramIds);

        $filteredPrograms = $query->get();


        $rankedPrograms = [];

        foreach ($filteredPrograms as $program) {
            $similarity = $this->calculateSimilarity($user, $program);
            Log::info("Similarity score for program ID {$program->id}: " . $similarity);
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

        $disabilityCounts = Disability::withCount('program')->get()->keyBy('id');
        $educationCounts = EducationLevel::withCount('program')->get()->keyBy('id');
        

        return view('pwd.listPrograms', compact('paginatedItems', 'disabilities', 'educations','disabilityCounts', 'educationCounts'));
    }

    private function calculateSimilarity($user, $program)
    {
        $similarityScore = 0;
        $weights = [
            'disability' => 50,
            'location' => 20,
            'age' => 5,
            'educ' => 10,
            'skills' => 10,
            'rating' => 5
        ];

        $userSkills = SkillUser::where('user_id', $user->id)->get();
        $totalRating = PwdFeedback::where('program_id', $program->id)->sum('rating');
        $ratingCount = PwdFeedback::where('program_id', $program->id)->count();
        $averageRating = $ratingCount > 0 ? $totalRating / $ratingCount : 0;

        // Criteria: disability, location
        if ($user->disability_id === $program->disability_id) {
            $similarityScore += $weights['disability'];
        }

        if ($user->city === $program->city) {
            $similarityScore += $weights['location'];
        }

        if($user->age >= $program->start_age && $user->age <= $program->end_age){
            $similarityScore += $weights['age'];
        }

        if($user->educational_id === $program->education_id){
            $similarityScore += $weights['educ'];
        }

        foreach($userSkills as $userSkill){
            if ($userSkill->skill_id === $program->skill_id){
                $similarityScore += $weights['skills'];
            }
        }

        if($averageRating){
            $similarityScore += $averageRating;
        }

        return $similarityScore;
    }

    public function showDetails($id)
    {
        $program = TrainingProgram::with('agency.userInfo', 'disability', 'education', 'crowdfund')->findOrFail($id);
        $userId = auth()->user()->id;
        $application = TrainingApplication::where('user_id', $userId)->get();
        $reviews = PwdFeedback::where('program_id', $id)->with('pwd')->latest()->get();
        $status = Enrollee::where('pwd_id', $userId)->get();
        $isCompletedProgram = Enrollee::where('program_id', $program->id)
        ->where('pwd_id', $userId)
        ->where('completion_status', 'Completed')
        ->exists();

        $enrolleeCount = Enrollee::where('program_id', $program->id)
            ->count();

        $slots = $program->participants - $enrolleeCount;

        // Collect all end dates from the applications
        $endDates = $application->map(function ($app) {
            return $app->program->end;
        })->toArray();

        $nonConflictingPrograms = TrainingProgram::where(function ($query) use ($endDates) {
            foreach ($endDates as $endDate) {
                $query->where('start', '>', $endDate);
            }
        })->pluck('id')->toArray();

        $enrollees = Enrollee::where('program_id', $program->id)->get();

        if ($program->crowdfund) {
            $raisedAmount = $program->crowdfund->raised_amount ?? 0; // Default to 0 if raised_amount is null
            $goal = $program->crowdfund->goal ?? 1; // Default to 1 to avoid division by zero
            $progress = ($goal > 0) ? round(($raisedAmount / $goal) * 100, 2) : 0; // Calculate progress percentage
            $program->crowdfund->progress = $progress;
        }
        return view('pwd.show', compact('program', 'reviews', 'application', 'nonConflictingPrograms', 'enrollees', 'status', 'isCompletedProgram', 'slots'));
    }

    public function showCalendar(Request $request)
    {
        Log::info("calendar reached in showCalendar!");
    
        $userId = Auth()->user()->id;
    
        if ($request->ajax()) {
            // Get the training programs based on the enrollee's application for the authenticated user
            $trainingDates = TrainingProgram::whereIn('id', function ($query) use ($userId) {
                $query->select('program_id')
                    ->from('enrollees')
                    ->where('pwd_id', $userId)
                    ->where('completion_status', 'Ongoing');
            })
            ->get(['id', 'title', 'start', 'end']);
            Log::info("TrainingDataes Ni:", $trainingDates->toArray());
            return response()->json($trainingDates);
        }
        
        return view('pwd.calendar');
    }

    public function showTrainings(Request $request)
    {
        $id = Auth::user()->id;
        $applications = TrainingApplication::where('user_id', $id)->where('application_status', 'Pending')->get();
        $trainings = Enrollee::where('pwd_id', $id)->get();
        $trainingsCount = $trainings->count();
        $ongoingCount = $trainings->where('completion_status', 'Ongoing')->count();
        $completedCount = $trainings->where('completion_status', 'Completed')->count();
        $approvedCount = $applications->where('application_status', 'Approved')->count();
        $pendingsCount = $applications->where('application_status', 'Pending')->count();

        if ($request->has('status') && $request->status != 'all') {
            $trainings = $trainings->where('completion_status', ucfirst($request->status));
        }

        return view('pwd.trainings', compact('applications', 'trainings', 'trainingsCount', 'ongoingCount', 'completedCount', 'approvedCount', 'pendingsCount'));
    }

    public function rateProgram(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:training_programs,id',
            'rating' => 'required|integer|between:1,5',
            'content' => 'nullable|string|max:1000',
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

    public function application(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'training_program_id' => 'required|exists:training_programs,id',
        ]);

        $validatedData['application_status'] = 'Pending';
        $trainingApplication = TrainingApplication::create($validatedData);

        $trainingProgram = TrainingProgram::findOrFail($validatedData['training_program_id']);

        $trainerUser = User::whereHas('userInfo', function ($query) use ($trainingProgram) {
            $query->where('user_id', $trainingProgram->agency_id);
        })->whereHas('role', function ($query) {
            $query->where('role_name', 'Trainer');
        })->first();

        if ($trainerUser) {
            $trainerUser->notify(new PwdApplicationNotification($trainingApplication));
        } else {
            Log::error('No agency user found for training program', ['trainingProgram' => $trainingProgram->id]);
        }

        return back()->with('success', 'Application sent successfully!');
    }
}
