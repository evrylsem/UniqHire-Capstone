<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Enrollee;
use App\Models\Role;
use App\Models\Disability;
use App\Models\EducationLevel;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\UserInfo;
use App\Models\SkillUser;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AuthController extends Controller
{
    public function showProfile()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $levels = EducationLevel::all();
        $disabilities = Disability::all();
        $skills = Skill::all();
        $skilluser = SkillUser::where('user_id', $id)->get();
        $experiences = Experience::where('user_id', $id)->get();
        $certifications = Enrollee::where('pwd_id', $id)->where('completion_status', 'Completed')->get();
        return view('auth.profile', compact('levels', 'disabilities', 'user', 'certifications', 'skills', 'skilluser', 'experiences'));
    }

    public function editProfile(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $userInfo = UserInfo::where('user_id', $id)->firstOrFail();
        $request->validate([
            'name' => 'required|string|max:255',
            'contactnumber' => 'required|string|max:255',
            'age' => 'nullable|integer|min:1|max:99',
            'city' => 'string|max:255',
            'state' => 'string|max:255',
            'founder' => 'nullable|string|max:255',
            'year_established' => 'nullable|integer|min:1000|max:3000',
            'about' => 'nullable|string',
            'awards' => 'nullable|string',
            'affiliations' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'profile_photos/' . $fileName;

            Storage::disk('profile_photos')->put($fileName, file_get_contents($file));

            $userInfo->profile_path = 'storage/' . $filePath;
            $userInfo->save();
        }

        if ($user->hasRole('Training Agency')) {
            $user->userInfo->update([
                'name' => $request->name,
                'contactnumber' => $request->contactnumber,
                'city' => $request->city,
                'state' => $request->state,
                'about' => $request->about,
                'founder' => $request->founder,
                'year_established' => $request->year_established,
                'awards' => $request->awards,
                'affiliations' => $request->affiliations,
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
                'educational_id' => $request->education
            ]);
        }
        return back()->with('success', 'Your profile has been changed successfully!');
    }

    public function removePicture(Request $request)
    {
        $user = auth()->user(); // Assuming user is authenticated
        if (!empty($user->userInfo->profile_path)) {
            Storage::disk('profile_photos')->delete(str_replace('storage/', '', $user->userInfo->profile_path));
        }

        $user->userInfo->profile_path = null;
        $user->userInfo->save();

        return redirect()->back()->with('success', 'Profile picture removed successfully.');
    }

    public function addExperience(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'id' => 'required|exists:user_infos,id'
        ]);

        Experience::create([
            'title' => $request->title,
            'date' => $request->date,
            'user_id' => $request->id
        ]);

        return back()->with('success', 'Work experience successfully added!');
    }

    public function deleteExperience($id)
    {
        $experience = Experience::findOrFail($id);
        $experience->delete();

        return back();
    }

    public function addSkill(Request $request)
    {
        $user = Auth::user()->id;

        $request->validate([
            'skill' => 'required|exists:skills,id',
        ]);

        SkillUser::create([
            'user_id' => $user,
            'skill_id' => $request->skill,
        ]);

        return back()->with('success', 'Skill successfully added!');
    }

    public function deleteSkill($id)
    {
        $skill = SkillUser::findOrFail($id);
        $skill->delete();

        return back()->with('success', 'Skill deleted successfully!');
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
            $query->where('role_name', 'Training Agency');
        })->count();

        $employerCount = User::whereHas('role', function ($query) {
            $query->where('role_name', 'Employer');
        })->count();

        $sponsorCount = User::whereHas('role', function ($query) {
            $query->where('role_name', 'Sponsor');
        })->count();

        return view('homepage', compact('images', 'pwdCount', 'trainerCount', 'employerCount', 'sponsorCount'));
    }

    public function showLanding()
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
            $query->where('role_name', 'Training Agency');
        })->count();

        $employerCount = User::whereHas('role', function ($query) {
            $query->where('role_name', 'Employer');
        })->count();

        $sponsorCount = User::whereHas('role', function ($query) {
            $query->where('role_name', 'Sponsor');
        })->count();

        return view('landing', compact('images', 'pwdCount', 'trainerCount', 'employerCount', 'sponsorCount'));
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
        $levels = EducationLevel::all();
        return view('auth.register', compact('roles', 'disabilities', 'levels'));
    }

    public function register(Request $request)
    {
        if ($request->generate_email || ($request->email && $request->generate_email)) {
            $email = fake()->unique()->safeEmail();
        } else {
            $email = $request->email;
        }

        $request->validate([
            'password' => 'required|string|min:4|max:255|confirmed',
            // 'disability' => 'required|string|exists:disabilities,id',
            // 'education' => 'required|string|exists:education_level,name',
            'name' => 'required|string|max:255',
            'contactnumber' => 'required|string|max:11|min:11',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pwd_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'age' => 'nullable|integer|min:1|max:99',
            'founder' => 'nullable|string|max:255',
            'year_established' => 'nullable|integer|min:1000|max:3000',
            // 'role' => 'required|string|exists:roles,id',
        ]);



        $user = User::create([
            'email' => $email,
            'password' => Hash::make($request->password),
        ]);

        $user->role()->attach($request->role);
        // Log::info('Attaching role ID: ' . $request->role . ' to user ID: ' . $user->id);

        UserInfo::create([
            'user_id' => $user->id,
            'disability_id' => $request->disability,
            'educational_id' => $request->education,
            'name' => $request->name,
            'contactnumber' => $request->contactnumber,
            'state' => $request->state,
            'city' => $request->city,
            'pwd_card' => null,
            'age' => $request->age ?? 0,
            'founder' => $request->founder ?? '',
            'year_established' => $request->year_established ?? 0,
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

    public function downloadCertificate($enrolleeId)
    {
        $enrollee = Enrollee::find($enrolleeId);

        if (!$enrollee) {
            abort(404, 'Enrollee not found');
        }

        $trainingProgram = $enrollee->program;
        $user = User::find($enrollee->pwd_id);

        if (!$user) {
            abort(404, 'User not found');
        }

        $pdf = Pdf::loadView('slugs.certificate', [
            'user' => $user,
            'trainingProgram' => $trainingProgram,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('certificate-' . $user->userInfo->name . ' .pdf');
    }


    public function addPicture(Request $request) {
        $user = UserInfo::where('user_id', Auth::user()->id)->firstOrFail();

        $request->validate([
            'profilePic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('profilePic')) {
            $file = $request->file('profilePic');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'profile_photos/' . $fileName;

            // Save the file to the specified disk
            Storage::disk('profile_photos')->put($fileName, file_get_contents($file));

            // Update the user's profile_path
            $user->profile_path = $filePath;
            $user->save();
        }

        return back()->with('success', 'Profile picture updated successfully.');
    }

}
