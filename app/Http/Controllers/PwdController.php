<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserInfo;
use App\Models\TrainingProgram;
use App\Models\Disability;
use App\Models\EducationLevel;
use App\Http\Requests\StoreUserInfoRequest;
use App\Http\Requests\UpdateUserInfoRequest;


class PwdController extends Controller
{

    public function showPrograms(Request $request) {
        $disability = auth()->user()->userInfo->disability_id;
        $programs = TrainingProgram::where('disability_id', $disability)->get();
        $disabilities = Disability::all();
        $educations = EducationLevel::all();
        $query = TrainingProgram::query();
        
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

        return view('pwd.listPrograms', compact('programs','disabilities', 'educations', 'filteredPrograms'));
    }
}
