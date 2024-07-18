<?php

namespace App\Http\Controllers;

use App\Models\Disability;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Role;

class AdminController extends Controller
{
    public function showDashboard()
    {
        $users = User::all();
        return view('admin.dashboard')->with('users');
    }

    public function showPwds()
    {
        $users = User::whereHas('userInfo', function ($query) {
            $query->whereNotNull('disability_id')->where('disability_id', '!=', 1);
        })->get();

        return view('admin.pwdUsers', compact('users'));
    }

    public function showTrainers()
    {
        $trainingID = Role::where('role_name', 'Trainer')->value('id');
        $users = User::whereHas('role', function ($query) use ($trainingID) {
            $query->where('role_id', $trainingID);
        })->get();

        return view('admin.trainingAgencies', compact('users'));
    }

    public function showEmployers()
    {
        $employeeID = Role::where('role_name', 'Employer')->value('id');

        $users = User::whereHas('role', function ($query) use ($employeeID) {
            $query->where('role_id', $employeeID);
        })->get();

        return view('admin.employeeUsers', compact('users'));
    }

    public function showSponsors()
    {
        $sponsorID = Role::where('role_name', 'Sponsor')->value('id');

        $users = User::whereHas('role', function ($query) use ($sponsorID) {
            $query->where('role_id', $sponsorID);
        })->get();

        return view('admin.sponsorUsers', compact('users'));
    }
}
