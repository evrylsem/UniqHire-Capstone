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

    public function showPrograms() {
        // $disability = auth()->user()->userInfo->disability_id;
        // $programs = TrainingProgram::where('disability_id', $disability)->get();
        $programs = TrainingProgram::all();
        $disabilities = Disability::all();
        $educations = EducationLevel::all();
    
        return view('pwd.listPrograms', compact('programs','disabilities', 'educations'));
    }
}
