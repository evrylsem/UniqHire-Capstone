<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Role;
use App\Models\Disability;
use App\Models\UserInfo;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showProfile()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $disabilities = Disability::all();
        return view('auth.profile', compact('disabilities', 'user'));
    }

    public function editProfile(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'contactnumber' => 'required|string|max:255',
            'age' => 'nullable|integer|min:1|max:99',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'about' => 'nullable|string'
        ]);

        if ($user->userInfo->disability_id == 1) {
            $user->userInfo->update([
                'name' => $request->name,
                'contactnumber' => $request->contactnumber,
                'city' => $request->city,
                'state' => $request->state,
                'about' => $request->about,
            ]);
        } else {
            $user->userInfo->update([
                'name' => $request->name,
                'contactnumber' => $request->contactnumber,
                'age' => $request->age,
                'city' => $request->city,
                'state' => $request->state,
                'about' => $request->about,
                'disability_id' => $request->disability,
            ]);
        }



        return redirect()->route('profile');
    }

    public function showHomePage()
    {
        $images = [
            'images/18.png',
            'images/19.png',
            'images/20.png',
            'images/21.png',
        ];
        $pwdCount = User::whereHas('role', function ($query) {
            $query->where('role_name', 'PWD');
        })->count();

        $trainerCount = User::whereHas('role', function ($query) {
            $query->where('role_name', 'Trainer');
        })->count();

        $employerCount = User::whereHas('role', function ($query) {
            $query->where('role_name', 'Employer');
        })->count();

        $sponsorCount = User::whereHas('role', function ($query) {
            $query->where('role_name', 'Sponsor');
        })->count();

        return view('homepage', compact('images', 'pwdCount', 'trainerCount', 'employerCount', 'sponsorCount'));
    }

    public function showForgotPass()
    {
        return view('auth.forgotPass');
    }

    //LOGIN PROCESS
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::user()->hasRole('PWD')) {
                return redirect()->intended(route('pwd-list-program'));
            } else {
                return redirect()->intended(route('home'));
            }
        } else {
            return back()->with('error', 'The provided credentials do not match our records');
            // return back()->withErrors([
            //     'email' => 'The provided credentials do not match our records',
            // ])->with('error', 'The provided credentials do not match our records');
        }
    }

    // REGISTRATION PROCESS
    public function showRegistration()
    {
        $roles = Role::all();
        $disabilities = Disability::all();
        return view('auth.register', compact('roles', 'disabilities'));
    }

    public function register(Request $request)
    {
        if ($request->generate_email || ($request->email && $request->generate_email)) {
            $email = fake()->unique()->safeEmail();
        } else {
            $email = $request->email;
        }

        $request->validate([
            'name' => 'required|string|max:255',
            // 'lastname' => 'nullable|string|max:255',
            // 'email' => 'required|string|email|unique:users,email|max:255',
            'contactnumber' => 'required|string|max:255',
            'password' => 'required|string|min:4|max:255|confirmed',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            // 'disability' => 'required|string|exists:disabilities,id',
            'pwd_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            // 'role' => 'required|string|exists:roles,role_name',
        ]);



        $user = User::create([
            'email' => $email,
            'password' => Hash::make($request->password),
        ]);

        $user->role()->attach($request->role);
        // Log::info('Attaching role ID: ' . $request->role . ' to user ID: ' . $user->id);

        UserInfo::create([
            'user_id' => $user->id,
            'name' => $request->name,
            // 'lastname' => $request->lastname,
            'contactnumber' => $request->contactnumber,
            'state' => $request->state,
            'city' => $request->city,
            'disability_id' => $request->disability,
            'pwd_card' => null,
        ]);

        // return redirect()->route('login-page');
        return redirect()->route('login-page')->with('success', 'Account registered successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login-page');
    }
}
