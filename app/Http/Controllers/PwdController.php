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
        $disabilities = Disability::all();
        $educations = EducationLevel::all();
        $query = TrainingProgram::query();        
        
        // Filtering the programs through searching program title
        if($request->filled('search'))
        {
            $query->where("title", "LIKE", "%" . $request->search . "%");
        }

        // Filtering the programs based on disability [multiple selection]
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

        Log::info('Ranked filtered programs:', $rankedPrograms);

        $viewName = $request->input('view', 'pwd.listPrograms');

        return view( $viewName, compact('rankedPrograms','disabilities', 'educations'));
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

    public function showDetails($id) {
        $program = TrainingProgram::findOrFail($id);

        return view('pwd.show', compact('program'));
    }
}
