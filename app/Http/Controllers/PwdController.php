<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserInfo;
use App\Models\TrainingProgram;
use App\Models\Disability;
use App\Models\EducationLevel;
use App\Http\Requests\StoreUserInfoRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use Illuminate\Support\Facades\Log;



class PwdController extends Controller
{

    public function showPrograms(Request $request) {
        $user = auth()->user()->userInfo;        
        // $programs = TrainingProgram::where('disability_id', $disability)->get();
        $programs = TrainingProgram::all();
        $disabilities = Disability::all();
        $educations = EducationLevel::all();
        $query = TrainingProgram::query();

        $rankedPrograms = [];

        foreach ($programs as $program) {
            $similarity = $this->calculateSimilarity($user, $program);
            $rankedPrograms[] = [
                'program' => $program->toArray(),
                'similarity' => $similarity
            ];
        }

        Log::info('Ranked Programs:', $rankedPrograms);

        usort($rankedPrograms, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });
        
        

        if(isset($request->disability) && ($request->disability != null))
        {
            $query->whereHas('disability', function($q) use($request){
                $q->whereIn('disability_name', $request->disability);
            });
        }

        if(isset($request->education) && ($request->education != null))
        {
            $query->whereHas('education', function($q) use($request){
                $q->whereIn('education_name', $request->education);
            });
        }

        $filteredPrograms = $query->get();
        Log::info('Ranked Filtered Programs:', $rankedPrograms);
        // dd($rankedPrograms);
        return view('pwd.listPrograms', compact('rankedPrograms','disabilities', 'educations', 'filteredPrograms'));
    }

    private function calculateSimilarity($user, $program)
    {
        $similarityScore = 0;
       
        // Check disability type
        if ($user->disability_id === $program->disability_id) {
            $similarityScore += 1;
        }

        if ($user->city === $program->city) {
            $similarityScore += 1;
        }

        return $similarityScore;
    }
}
