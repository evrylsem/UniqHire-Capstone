<?php

namespace App\Http\Controllers;

use App\Models\CrowdfundEvent;
use App\Models\Disability;
use App\Models\EducationLevel;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Models\UserInfo;
use App\Notifications\NewTrainingProgramNotification;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        if ($program->crowdfund) {
            $raisedAmount = $program->crowdfund->raised_amount ?? 0; // Default to 0 if raised_amount is null
            $goal = $program->crowdfund->goal ?? 1; // Default to 1 to avoid division by zero
            $progress = ($goal > 0) ? round(($raisedAmount / $goal) * 100, 2) : 0; // Calculate progress percentage
            $program->crowdfund->progress = $progress;
        }
        return view('agency.showProg', compact('program'));
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
            'city' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            // 'disability' => 'required|exists:disabilities,id',
            // 'education' => 'required|exists:education_levels,id',
            // 'goal' => 'nullable|numeric' 
        ]);

        // Create a new training program
        $trainingProgram = TrainingProgram::create([
            'agency_id' => auth()->id(),
            'title' => $request->title,
            'city' => $request->city,
            'description' => $request->description,
            'start' => $request->start_date,
            'end' => $request->end_date,
            'disability_id' => $request->disability,
            'education_id' => $request->education,
        ]);

        //NOTIFY PWD USERS!!!
        $pwdUsers = User::whereHas('role', function ($query) {
            $query->where('role_name', 'PWD');
        })->get();

        foreach ($pwdUsers as $user) {
            Log::info('Sending notifications to user: ' . $user->id);
            $user->notify(new NewTrainingProgramNotification($trainingProgram));
        }

        // if ($request->has('goal') && $request->goal !== null) {
        //     CrowdfundEvent::create([
        //         'program_id' => $trainingProgram->id,
        //         'goal' => $request->goal,
        //     ]);
        // }

        return redirect()->route('programs-manage');
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
        }

        return redirect()->route('programs-manage');
    }

    public function editProgram($id)
    {
        $program = TrainingProgram::find($id);

        if ($program && $program->agency_id == auth()->id()) {
            $disabilities = Disability::all();
            $levels = EducationLevel::all();

            return view('agency.editProg', compact('program', 'disabilities', 'levels'));
        }

        return redirect()->route('programs-manage');
    }

    public function updateProgram(Request $request, $id)
    {
        $program = TrainingProgram::find($id);

        if ($program && $program->agency_id == auth()->id()) {
            $request->validate([
                'title' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'goal' => 'nullable|numeric'
            ]);

            $program->update([
                'title' => $request->title,
                'city' => $request->city,
                'description' => $request->description,
                'start' => $request->start_date,
                'end' => $request->end_date,
                'disability_id' => $request->disability,
                'education_id' => $request->education,
            ]);

            // if ($request->has('goal') && $request->goal !== null) {
            //     CrowdfundEvent::create([
            //         'program_id' => $program->id,
            //         'goal' => $request->goal,
            //     ]);
            // }

            return redirect()->route('programs-show', $id);
        }
    }

    public function showCalendar(Request $request) {
        log::info("calendar reach in showCalendar!");
        if($request->ajax())
    	{
    		$data = TrainingProgram::whereDate('start', '>=', $request->start)
                       ->whereDate('end',   '<=', $request->end)
                       ->get(['agency_id', 'title', 'start', 'end']);
            return response()->json($data);
    	}
        return view('agency.calendar');
    }   

    public function action(Request $request)
    {
        log::info("calendar reach in action!");
        if($request->ajax())
    	{
    		if($request->type == 'add')
    		{
    			$event = TrainingProgram::create([
    				'title'		=>	$request->title,
    				'start'		=>	$request->start,
    				'end'		=>	$request->end
    			]);

    			return response()->json($event);
    		}

    		if($request->type == 'update')
    		{
    			$event = TrainingProgram::find($request->id)->update([
    				'title'		=>	$request->title,
    				'start'		=>	$request->start,
    				'end'		=>	$request->end
    			]);

    			return response()->json($event);
    		}

    		if($request->type == 'delete')
    		{
    			$event = TrainingProgram::find($request->id)->delete();

    			return response()->json($event);
    		}
    	}
    }
}
