<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserInfo;
use App\Models\TrainingProgram;
use App\Http\Requests\StoreUserInfoRequest;
use App\Http\Requests\UpdateUserInfoRequest;


class PwdController extends Controller
{
    public function listOfPrograms() {
        return view('pwd.listPrograms');
    }

    public function showPrograms() {
        $disability = auth()->user()->userInfo->disability_id;
        $programs = TrainingProgram::where('disability_id', $disability)->get();
    
        return view('pwd.listPrograms', compact('programs'));
    }
}
