<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class SelectizeController extends Controller
{
    public function index(Request $request, $a)
    {
        if ($request->ajax()) {
            $a = $request->a;

            switch ($a) {
                case 'get-employee-list':
                    $employees = Employee::with('empOrganization')->get();
                    $employeeData = $employees->map(function ($item) {
                        return [
                            'id' => $item->emp_id,
                            //'emp_id' => $item->emp_id,
                            'name' => $item->full_name ?? '',
                            'organization' => $item->empOrganization->name ?? '',
                        ];
                    });
                    return response()->json([
                        'status' => 1,
                        'data' => $employeeData,
                        'count' => $employeeData->count()
                    ]);
                case 'get-employee-leave-balance':
                    $employee = Employee::where('emp_id', $request->emp_id)->first();
                    $employeeLeaveBalance=[];

                    if($employee){
                        $employeeLeaveBalance=[
                            'emp_name' => $employee->full_name,
                            'leaveBalance' => $employee->leaveBalance(date('Y')),
                        ];
                        return response()->json([
                            'status' => 1,
                            'data' => $employeeLeaveBalance,
                            'msg' => 'Successfully Retrieve Employee leave balance',
                        ]);
                    }else{
                        return response()->json([
                            'status' => 0,
                            'msg' => 'Employee Not found!',
                        ]);
                    }

                case 'get-department-head-list':
                    $departmentHeads = User::whereIn('role_id', function ($query) {
                        $query->select('id')
                            ->from((new Role())->getTable())
                            ->where('slug', 'department-head');
                    })->get();
                    $departmentHeadData=[];
                    foreach ($departmentHeads as $item){
                        if($item->employee) {
                            $departmentHeadData[]= [
                                'id' => $item->employee->emp_id,
                                'name' => $item->name ?? '',
                                'organization' => $item->employee->empOrganization->name ?? '',
                            ];
                        }

                    }
                    return response()->json([
                        'status' => 1,
                        'data' => $departmentHeadData,
                        'count' => count($departmentHeadData)
                    ]);

                default:
                    return response()->json([
                        'status' => 0,
                        'message' => 'Invalid action'
                    ]);
            }
        }
        abort(403);
    }
}
