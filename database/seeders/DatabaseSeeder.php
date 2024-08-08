<?php

namespace Database\Seeders;

use App\Models\AllUser;
use App\Models\Disability;
use App\Models\EducationLevel;
use App\Models\Skill;
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
        //ROLES
        $admin = Role::create(['role_name' => 'Admin']);
        $pwd = Role::create(['role_name' => 'PWD']);
        $trainer = Role::create(['role_name' => 'Training Agency']);
        $employer = Role::create(['role_name' => 'Employer']);
        $sponsor = Role::create(['role_name' => 'Sponsor']);

        //DISABILITIES
        $none = Disability::create(['disability_name' => 'Not Applicable']);
        $rightarm = Disability::create(['disability_name' => 'Right Arm Amputee']);
        $leftarm = Disability::create(['disability_name' => 'Left Arm Amputee']);
        $botharm = Disability::create(['disability_name' => "Bilateral Amputee (both arms)"]);
        $rightleg = Disability::create(['disability_name' => 'Right Leg Amputee']);
        $leftleg = Disability::create(['disability_name' => 'Left Leg Amputee']);
        $bothleg = Disability::create(['disability_name' => "Bilateral Amputee (both legs)"]);
        $hear = Disability::create(['disability_name' => 'Hearing Impaired']);
        $speech = Disability::create(['disability_name' => 'Speech Impairment']);
        $visual = Disability::create(['disability_name' => 'Visually Impaired']);

        $not_applicable = EducationLevel::create(['education_name' => 'Not Applicable']);
        $hsgrad = EducationLevel::create(['education_name' => 'High School Graduate']);
        $somecoll = EducationLevel::create(['education_name' => 'Some College']);
        $bachdegree = EducationLevel::create(['education_name' => "Bachelor's Degree"]);
        $vocational = EducationLevel::create(['education_name' => 'Vocational']);

        $programming = Skill::create(['title' => 'Programming']);
        $communication = Skill::create(['title' => 'Communication']);
        $graphic = Skill::create(['title' => 'Graphic Design']);
        $data_analysis = Skill::create(['title' => 'Data Analysis']);
        $carpentry = Skill::create(['title' => 'Carpentry']);


        $adminuser = User::create([
            'email' => 'kler@example.com',
            'password' => Hash::make('qwe1234'),

        ]);

        UserInfo::create([
            'name' => 'Evryl Claire',
            'contactnumber' => '09123456789',
            'state' => 'Cebu',
            'city' => 'City Of Talisay',
            'disability_id' => $none->id,
            'educational_id' => $not_applicable->id,
            'user_id' => $adminuser->id,
        ]);


        $adminuser->role()->attach($admin);

        // $pwduser = User::create([
        //     'email' => 'pwd@example.com',
        //     'password' => Hash::make('qwe1234'),

        // ]);

        // UserInfo::create([
        //     'name' => 'Juan Dela Cruz',
        //     'contactnumber' => '09123456789',
        //     'state' => 'Cebu',
        //     'city' => 'City Of Talisay',
        //     'disability_id' => $leftarm->id,
        //     'educational_id' => $bachdegree->id,
        //     'user_id' => $pwduser->id,
        // ]);


        // $pwduser->role()->attach($pwd);

        // $traineruser1 = User::create([
        //     'email' => 'trainer@example.com',
        //     'password' => Hash::make('sheesh'),

        // ]);

        // UserInfo::create([
        //     'name' => 'BrightFuture Training',
        //     'contactnumber' => '09123456789',
        //     'city' => 'City Of Cebu',
        //     'state' => 'Cebu',
        //     'disability_id' => $none->id,
        //     'educational_id' => $not_applicable->id,
        //     'user_id' => $traineruser1->id,
        // ]);

        // TrainingProgram::create([
        //     'id' => '001',
        //     'agency_id' => $traineruser1->id,
        //     'title' => 'EmpowerTech Skills Development Program',
        //     'description' => 'The EmpowerTech Skills Development Program is a comprehensive training initiative aimed at enhancing the technical and vocational skills of people with disabilities. The program focuses on providing hands-on experience and practical knowledge to enable participants to thrive in the digital economy.',
        //     'state' => 'Cebu',
        //     'city' => 'City Of Cebu',
        //     'participants' => 30,
        //     'start' => date("Y-m-d"),
        //     'end' => date("Y-m-d"),
        //     'disability_id' => $bothleg->id,
        //     'education_id' => $hsgrad->id,
        //     'skill_id' => $programming->id,
        // ]);

        // $traineruser1->role()->attach($trainer);

        // $traineruser2 = User::create([
        //     'email' => 'trainer2@example.com',
        //     'password' => Hash::make('sheesh'),

        // ]);

        // UserInfo::create([
        //     'name' => 'Aspire Training Solutions',
        //     'contactnumber' => '09123456789',
        //     'city' => 'City Of Talisay',
        //     'state' => 'Cebu',
        //     'disability_id' => $none->id,
        //     'educational_id' => $not_applicable->id,
        //     'user_id' => $traineruser2->id,
        // ]);

        // TrainingProgram::create([
        //     'id' => '002',
        //     'agency_id' => $traineruser2->id,
        //     'title' => 'InclusiveTech Career Advancement Program',
        //     'description' => 'The InclusiveTech Career Advancement Program is designed to equip people with disabilities with advanced skills in technology and professional development. This program focuses on bridging the skills gap and providing participants with the knowledge and confidence to pursue high-demand careers in the tech industry.',
        //     'state' => 'Cebu',
        //     'city' => 'City Of Talisay',
        //     'participants' => 30,
        //     'start' => date("Y-m-d"),
        //     'end' => date("Y-m-d"),
        //     'disability_id' => $hear->id,
        //     'education_id' => $somecoll->id,
        //     'skill_id' => $carpentry->id,
        // ]);

        // $traineruser2->role()->attach($trainer);
    }
}
