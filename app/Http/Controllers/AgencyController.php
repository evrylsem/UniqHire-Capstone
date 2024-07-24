<?php

namespace App\Http\Controllers;

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
        $programs = TrainingProgram::where('agency_id', $userId)->orderBy('created_at', 'desc')->get();
        foreach ($programs as $program) {
            $endDate = new DateTime($program->end);
            $today = new DateTime();
            $interval = $today->diff($endDate);
            $program->remainingDays = $interval->days;
        }
        return view('agency.manageProg', compact('programs'));
    }

    public function showProgramDetails($id)
    {
        $program = TrainingProgram::findOrFail($id);

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
        }

        return redirect()->route('programs-manage');
    }
}
