<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Departments;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\TeamDepartmentHead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class TeamController extends Controller
{
    public function index()
    {
        if(!userCan('team.view')) return view('error-page.unauthorized');

        $teams=Team::get();
        $department=Departments::get();
        $organizations = Organization::get();
        return view('team.index', compact('teams', 'organizations', 'department'));
    }
    public function teamMembers(Request $request, $id)
    {
        if(!userCan('team.view')) return view('error-page.unauthorized');
        $team=Team::find($id);
        if($team){
            $action=$request->action;
            if($request->ajax()) {
                if ($action == 'add-employee') {
                    TeamMember::insert(['team_id' => $id, 'emp_id' => $request->emp_id]);
                } else if ($action == 'add-bulk-employee') {
                    $empIds = explode(',', $request->input('emp_id'));
                    $teamArray = [];
                    foreach ($empIds as $item) {
                        $teamArray[] = [
                            'team_id' => $id,
                            'emp_id' => $item,
                        ];
                    }
                    TeamMember::insert($teamArray);
                } else if ($action == 'remove-employee') {
                    TeamMember::where('team_id', $id)->where('emp_id', $request->emp_id)->delete();
                }
            }
            $employee=Employee::where('organization', $team->organization)
                ->where('department', $team->department)
                ->whereNotIn('emp_id', function ($query){
                    $query->select('emp_id')
                        ->from((new TeamMember())->getTable())
                        ->whereNull('deleted_at');
                })
                ->get();
            if($request->ajax()){

                $html=View::make('team.team-members', compact('team', 'employee'))->render();
                $response=[
                    'status'=>1,
                    'teamMemberCount'=>$team->teamMember->count() ?? 0,
                    'html'=>$html,
                    'msg'=>$action ? 'Successfully Added' : ''
                ];
                return response()->json($response);
            }else{
                $departmentHeads = User::whereIn('role_id', function ($query) {
                    $query->select('id')
                        ->from((new Role())->getTable())
                        ->where('slug', 'department-head');
                })->get();
                $department=Departments::get();
                $organizations = Organization::get();
                return view('team.index', compact('team', 'departmentHeads', 'organizations', 'department', 'employee'));
            }

        }else{
            return response()->json(['status' => 0,'msg'=>'Team Not Found!']);
        }



    }

    public function store(Request $request){
        if(!userCan('team.create')) return back()->with('error', 'Unauthorized Access!');
        if(Team::where('name', $request->name)->where('organization',$request->organization)->where('department',$request->department)->count()>0){
            return back()->with('error', "Already Exists!");
        }
        $query=new Team();

        $name=$request->name;
        $departmentHead=$request->departmentHead;
        if(!$departmentHead) return back()->with('error', 'Department Head Not Found!');
        foreach ($departmentHead as $item) {
            if (User::where('emp_id', $item)->first()->role->slug != 'department-head') return back()->with('error', 'Invalid Department Head!');
        }
        $query->name=$name;
        $query->organization=$request->organization;
        $query->department=$request->department;

        if($query->save()){
            $teamId=$query->id;
            $departmentHeadData=[];
            foreach ($departmentHead as $item){
                $departmentHeadData[]=[
                    'team_id'=>$teamId,
                    'department_head_id'=>$item,
                ];
            }
            TeamDepartmentHead::insert($departmentHeadData);
            return redirect()->route('team.index', ['active'=>$teamId])->with('success', "Successfully Created");
        }
        return back()->with('error', "Server Error!");

    }
    public function edit(Request $request, $id)
    {
        if($request->ajax()){
            $team=Team::find($id);
            if($team){
                $department=Departments::get();
                $organizations = Organization::get();
                $html=View::make('team.edit', compact('team', 'organizations', 'department'))->render();
                return response()->json(['status' => 1,'html'=>$html]);
            }else{
                return response()->json(['status' => 0,'msg'=>'Not Found']);
            }
        }
        abort(403);
    }
    public function update(Request $request, $id){
        if(!userCan('team.edit')) return back()->with('error', 'Unauthorized Access!');


        $query=Team::find($id);
        if(!$query) return back()->with('error', 'Team Not Found!');
        $name=$request->name;
        $departmentHead=$request->departmentHead;
        if(!$departmentHead) return back()->with('error', 'Department Head Not Found!');
        foreach ($departmentHead as $item) {
            if (User::where('emp_id', $item)->first()->role->slug != 'department-head') return back()->with('error', 'Invalid Department Head!');
        }
        $query->name=$name;
        $query->organization=$request->organization;
        $query->department=$request->department;

        if($query->save()){
            $teamId=$query->id;
            $departmentHeadData=[];
            foreach ($departmentHead as $item){
                $departmentHeadData[]=[
                    'team_id'=>$teamId,
                    'department_head_id'=>$item,
                ];
            }
            $previousDepartmentHead=TeamDepartmentHead::where('team_id', $teamId)->pluck('id');
            if(TeamDepartmentHead::insert($departmentHeadData)){
                TeamDepartmentHead::whereIn('id', $previousDepartmentHead)->delete();
            }
            return back()->with('success', "Successfully Created");
        }
        return back()->with('error', "Server Error!");

    }
    public function delete(Request $request, $id){
        if(!userCan('team.delete')) return back()->with('error', 'Unauthorized Access!');
        $team = Team::find($id);

        if (!$team) {
            return back()->with('error', 'Team Not Found!');
        }
        DB::beginTransaction();
        try {
            $team->getDepartmentHead()->delete();
            $team->teamMember()->delete();
            $team->delete();
            DB::commit();
            return redirect()->route('team.index')->with('success', 'Successfully Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while deleting the team.');
        }
    }
    public function removeEmployee(Request $request, $id){
        if(!userCan('team.remove-employee')) return back()->with('error', 'Unauthorized Access!');
        $teamMember=TeamMember::find($id);
        if($teamMember){
            if($teamMember->delete()){
                return back()->with('success', "Successfully Removed");
            }else{
                return back()->with('error', "Server Error!");
            }
        }else{
            return back()->with('error', "Not found!");
        }

    }

}
