<?php

namespace Database\Seeders;

use App\Models\AllUser;
use App\Models\Disability;
use App\Models\EducationLevel;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\UserInfo;
use App\Models\TrainingProgram;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        //ROLES
        $admin = Role::create(['role_name' => 'Admin']);
        $pwd = Role::create(['role_name' => 'PWD']);
        $trainer = Role::create(['role_name' => 'Trainer']);
        $employer = Role::create(['role_name' => 'Employer']);
        $sponsor = Role::create(['role_name' => 'Sponsor']);

        //DISABILITIES
        $none = Disability::create(['disability_name' => 'Not Applicable']);
        $arm = Disability::create(['disability_name' => 'Arm Amputee']);
        $leg = Disability::create(['disability_name' => 'Leg Amputee']);
        $hear = Disability::create(['disability_name' => 'Hearing Impaired']);
        $speech = Disability::create(['disability_name' => 'Speech Impairment']);
        $visual = Disability::create(['disability_name' => 'Visually Impaired']);

        $hsgrad = EducationLevel::create(['education_name' => 'High School Graduate']);
        $somecoll = EducationLevel::create(['education_name' => 'Some College']);
        $bachdegree = EducationLevel::create(['education_name' => "Bachelor's Degree"]);
        $vocational = EducationLevel::create(['education_name' => 'Vocational']);


        $adminuser = User::create([
            'email' => 'kler@example.com',
            'password' => Hash::make('qwe1234'),

        ]);

        UserInfo::create([
            'name' => 'Evryl Claire',
            // 'lastname' => 'Claire',
            'contactnumber' => '09123456789',
            'state' => 'cebu',
            'city' => 'bulacao',
            'disability_id' => $arm->id, // Assign a disability ID here
            'user_id' => $adminuser->id,
        ]);


        $adminuser->role()->attach($admin);
        // $user->disabilities()->attach($arm);

        $pwduser = User::create([
            'email' => 'pwd@example.com',
            'password' => Hash::make('qwe1234'),

        ]);

        UserInfo::create([
            'name' => 'Juan Dela Cruz',
            // 'lastname' => 'Dela Cruz',
            'contactnumber' => '09123456789',
            'state' => 'cebu',
            'city' => 'bulacao',
            'disability_id' => $arm->id, // Assign a disability ID here
            'user_id' => $pwduser->id,
        ]);


        $pwduser->role()->attach($pwd);

        $traineruser1 = User::create([
            'email' => 'trainer@example.com',
            'password' => Hash::make('sheesh'),

        ]);

        UserInfo::create([
            'name' => 'Bilat Way Hugas',
            // 'lastname' => 'Way Hugas',
            'contactnumber' => '09123456789',
            'city' => 'cebu',
            'state' => 'bulacao',
            'disability_id' => $none->id, // Assign a disability ID here
            'user_id' => $traineruser1->id,
        ]);

        TrainingProgram::create([
            'id' => '001',
            'agency_id' => $traineruser1->id,
            'title' => 'Luto gamit tiil program',
            'description' => 'Wa kay kamot? Wa nay problema kay sa programa namo makat on mog luto gamit tiil',
            'state' => 'Cebu',
            'city' => 'Cebu City',
            'participants' => 30,
            'start' => date("Y-m-d"),
            'end' => date("Y-m-d"),
            'disability_id' => $leg->id,
            'education_id' => $hsgrad->id,
            'created_at' => date("Y-m-d"),
            'updated_at' => date("Y-m-d"),
        ]);

        $traineruser1->role()->attach($trainer);

        $traineruser2 = User::create([
            'email' => 'trainer2@example.com',
            'password' => Hash::make('sheesh'),

        ]);

        UserInfo::create([
            'name' => 'Ungart',
            // 'lastname' => 'Way Hugas',
            'contactnumber' => '09123456789',
            'city' => 'Talisay City',
            'state' => 'Cebu',
            'disability_id' => $none->id, // Assign a disability ID here
            'user_id' => $traineruser2->id,
        ]);

        TrainingProgram::create([
            'id' => '002',
            'agency_id' => $traineruser2->id,
            'title' => 'Tudloan masabaan ang amang',
            'description' => 'Di makatabi? No problem kay sa program namo diha raka kitag amang nga saba',
            'state' => 'Cebu',
            'city' => 'Talisay City',
            'participants' => 30,
            'start' => date("Y-m-d"),
            'end' => date("Y-m-d"),
            'disability_id' => $arm->id,
            'education_id' => $hsgrad->id,
            'created_at' => date("Y-m-d"),
            'updated_at' => date("Y-m-d"),
        ]);

        $traineruser2->role()->attach($trainer);
    }
}
