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
use Illuminate\Pagination\LengthAwarePaginator;


class PwdController extends Controller
{

    public function showPrograms(Request $request) {
        $user = auth()->user()->userInfo;        
        $disabilities = Disability::all();
        $educations = EducationLevel::all();
        $query = TrainingProgram::query();        
        
        // Filtering the programs through searching program title
        if ($request->filled('search')) {
            $query->where("title", "LIKE", "%" . $request->search . "%");
        }

        // Filtering the programs based on disability [multiple selection]
        if (isset($request->disability) && ($request->disability != null)) {
            $query->whereHas('disability', function ($q) use ($request) {
                $q->whereIn('disability_name', $request->disability);
            });
        }

        if (isset($request->education) && ($request->education != null)) {
            $query->whereHas('education', function ($q) use ($request) {
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

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $currentItems = array_slice($rankedPrograms, ($currentPage - 1) * $perPage, $perPage);
        $paginatedItems = new LengthAwarePaginator($currentItems, count($rankedPrograms), $perPage);
        $paginatedItems->setPath($request->url());        

        $viewName = $request->input('view', 'pwd.listPrograms');

        return view( $viewName, compact('paginatedItems','disabilities', 'educations'));
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
        $program = TrainingProgram::with('agency.userInfo', 'disability', 'education')->findOrFail($id);
        return response()->json($program);
    }

    public function showCalendar(Request $request) {
        log::info("calendar reach in showCalendar!");
        if($request->ajax())
    	{
    		$data = TrainingProgram::whereDate('start', '>=', $request->start)
                       ->whereDate('end',   '<=', $request->end)
                       ->get(['id', 'title', 'start', 'end']);
            return response()->json($data);
    	}
        return view('pwd.calendar');
    }   

    public function action(Request $request)
    {
        log::info("calendar reach in action!");
        if($request->ajax())
    	{
    		if($request->type == 'add')
    		{
    			$event = TrainingProgram::create([
    				'title'		=>	$request->title,
    				'start'		=>	$request->start,
    				'end'		=>	$request->end
    			]);

    			return response()->json($event);
    		}

    		if($request->type == 'update')
    		{
    			$event = TrainingProgram::find($request->id)->update([
    				'title'		=>	$request->title,
    				'start'		=>	$request->start,
    				'end'		=>	$request->end
    			]);

    			return response()->json($event);
    		}

    		if($request->type == 'delete')
    		{
    			$event = TrainingProgram::find($request->id)->delete();

    			return response()->json($event);
    		}
    	}
    }
}
