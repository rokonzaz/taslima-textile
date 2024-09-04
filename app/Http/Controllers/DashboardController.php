<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\DutySlot;
use App\Models\DutySlotRule;
use App\Models\Employee;
use App\Models\EmployeeRequest;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Notice;
use App\Models\Organization;
use App\Models\Weekend;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {




            $a = $request->a;
            $organization = $request->organization;
            $periodType = $request->periodType;
            $date = $request->date ?? date('Y-m-d');
            $payload = [];
            if($a=='my-report'){
                $month = $request->month;
                $myReports=[];
                $authEmpId=getAuthEmpId();
                if($authEmpId){
                    $startDayOfMonth = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
                    $endDayOfMonth = Carbon::parse($month)->endOfMonth()->format('Y-m-d');
                    $empLeave = Leave::onlyApproved()
                        ->where('start_date', '>=', $startDayOfMonth)
                        ->where('end_date', '<=', $endDayOfMonth)
                        ->where('emp_id', $authEmpId)
                        ->orderBy('start_date', 'asc')
                        ->get();
                    $myReports['leaveCount']=count($empLeave);
                    $myReports['leaveHtml']=View::make('dashboard.leave-list', compact('empLeave'))->render();
                    $dateWiseAttendanceData= (new ReportsController())
                        ->getEmployeeReports($request, [
                            'reportType'=>'monthly',
                            'start_date'=>$startDayOfMonth,
                            'end_date'=>$endDayOfMonth,
                            'specific-single-employee'=>true,
                            'emp_id'=>$authEmpId,
                            'onlyLateEmployee'=>true,
                        ]);
                    $myReports['lateList']=$dateWiseAttendanceData;
                    $dateWiseAttendanceData=$dateWiseAttendanceData['data'][0]['dateWiseAttendanceData'] ?? [];
                    $myReports['lateCount']=count($dateWiseAttendanceData);
                    $myReports['lateListHtml']=View::make('dashboard.late-list', compact('dateWiseAttendanceData'))->render();

                    $dateWiseAttendanceData= (new ReportsController())
                        ->getEmployeeReports($request, [
                            'reportType'=>'monthly',
                            'start_date'=>$startDayOfMonth,
                            'end_date'=>$endDayOfMonth,
                            'specific-single-employee'=>true,
                            'emp_id'=>$authEmpId,
                            'onlyLateEmployee'=>false,
                        ]);
                    $attendanceData=$dateWiseAttendanceData['data'][0]['dateWiseAttendanceData'] ?? [];
                    $myReports['attendanceData']=$attendanceData;
                    $summary=$dateWiseAttendanceData['data'][0]['emp_summary'] ?? [];
                    $monthlySummary=[
                        'Present'=>$summary['presentCount']??0,
                        'Weekends'=>$summary['weekendCount']??0,
                        'Absent'=>$summary['absentCount']??0,
                        'Late'=>$summary['lateCount']??0,
                        'Leave'=>$summary['leaveCount']??0,
                        'Early Leave'=>$summary['earlyLeaveCount']??0,
                        'Overtime'=>$summary['overtimeCount'] ? minutesToHour($summary['overtimeCount']) : 0,
                    ];
                    $displayMonth=date('M Y', strtotime($startDayOfMonth));
                    $myReports['monthlyReportSummaryHtml']=View::make('dashboard.monthly-report-summary', compact('monthlySummary', 'attendanceData', 'displayMonth'))->render();
                    return response()->json(['status' => 1, 'data' => $myReports]);
                }else{
                    return response()->json(['status' => 0, 'msg' => 'Employee not found!']);
                }
            }

            $manageableEmployee=getManageableEmployees();
            $manageableEmployeeCount=$manageableEmployee->count();
            $isRoleSuperAdminHr=isRoleIn(['super-admin','hr']);

            if ($isRoleSuperAdminHr || $manageableEmployeeCount>0) {
                $employeeQuery = Employee::query();

                if ($isRoleSuperAdminHr && $organization) {
                    $employeeQuery->where('organization', $organization);
                }

                if(!$isRoleSuperAdminHr && $manageableEmployeeCount>0){
                    $totalEmployeeCount = $manageableEmployeeCount;
                    $manageableEmployees = $manageableEmployee;
                    $manageableEmployeeIds = $manageableEmployees->pluck('emp_id');
                }else{
                    $totalEmployeeCount = $employeeQuery->count();
                    $manageableEmployees = $employeeQuery->get();
                    $manageableEmployeeIds = $manageableEmployees->pluck('emp_id');
                }

                $manageableEmployeeMachineIDs = $manageableEmployees->pluck('biometric_id');

                // Employee Count
                $payload['employeeCount'] = $totalEmployeeCount;

                // Present Employee
                $presentEmployeePins = Attendance::whereIn('PIN', $manageableEmployeeMachineIDs)
                    ->where('DateTime', 'like', "$date %")
                    ->distinct('PIN')
                    ->pluck('PIN');

                $presentEmployees = $manageableEmployees->whereIn('biometric_id', $presentEmployeePins);
                $presentEmployeesIds=$presentEmployees->pluck('emp_id');
                $presentCount = $presentEmployees->count();
                //$payload['presentEmployee'] = $presentEmployees;
                $payload['presentCount'] = $presentCount;

                // Leave Count
                $todayLeave = Leave::onlyApproved()
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->whereIn('emp_id', $manageableEmployeeIds)
                    ->get();
                $todayLeaveEmployeeIds=$todayLeave->pluck('emp_id')->toArray();
                $todayLeaveCount = $todayLeave->count();
                $payload['leaveCount'] = $todayLeaveCount;

                // Absent Employee
                $absentEmployees = $manageableEmployees->whereNotIn('biometric_id', $presentEmployeePins);
                $absentCount = $absentEmployees->count();
                //$payload['absentEmployee'] = $absentEmployees;
                $weekends=Weekend::get()->pluck('days')->toArray();
                $isWeekends=in_array(Carbon::parse($date)->format('l'), $weekends);
                $payload['absentEmployeeHtml'] = View::make('dashboard.absent-employee', compact('absentEmployees', 'todayLeaveEmployeeIds', 'isWeekends'))->render();
                $payload['absentCount'] = $absentCount;


                $lateEmployees= (new ReportsController())
                    ->getEmployeeReports($request, [
                        'reportType'=>'date-wise',
                        'specific-employee'=>true,
                        'employeeIds'=>$presentEmployeesIds,
                        'onlyLateEmployee'=>true,
                    ]);
                $lateEmployeeCount=count($lateEmployees);
                $payload['lateCount']=$lateEmployeeCount;
                $payload['lateEmployee']=$lateEmployees;
                $payload['lateEmployeeHtml']=View::make('dashboard.late-employee', compact('lateEmployees'))->render();
                $payload['attendanceReport']=[
                    'regularCount'=>$presentCount-$lateEmployeeCount,
                    'absentCount'=>$absentCount,
                    'lateCount'=>$lateEmployeeCount,
                ];
            }

            return response()->json(['status' => 1, 'data' => $payload]);
        } else {
            return view('dashboard.index');
        }





        $date=date('Y-m-d');
        $todayLeave=\App\Models\Leave::where('approval_status', 2)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->pluck('emp_id');
        if($request->ajax()){
            $todayLeave=\App\Models\Leave::where('approval_status', 2)
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->pluck('emp_id');
            $a=$request->a;
            if($a=='get-dashboard-basic-data'){
                $data=[];

                $employee=auth()->user()->employee;
                if($employee){
                    $data['myAttendanceReports']=(new ReportsController())->getReports($request, ['a'=>'employee-attendance-report', 'emp_id'=>[$employee=auth()->user()->emp_id]]);
                }

                if(isRoleIn(['super-admin','hr'])){
                    $manageableEmployees=Employee::get();
                }else{
                    $manageableEmployees=getManageableEmployees();
                    $manageableEmployeesAsLineManager=getManageableEmployeesIDs('line-manager');
                    $manageableEmployeesAsDepartmentHead=getManageableEmployeesIDs('department-head');

                }

                $manageableEmployeeIds = $manageableEmployees->pluck('emp_id');
                $manageableEmployeeMachineIDs = $manageableEmployees->pluck('biometric_id');
                $manageableEmployeeIdsCount=count($manageableEmployeeIds);
                if($manageableEmployeeIdsCount>0){
                    $todayLeave=\App\Models\Leave::where('start_date', '<=', $date)
                        ->where('end_date', '>=', $date)
                        ->whereIn('emp_id', $manageableEmployeeIds);

                    $data['manageableEmployeeCount']=$manageableEmployeeIdsCount;
                    $employeeRequests=EmployeeRequest::onlyPending()->whereIn('emp_id', $manageableEmployeeIds);
                    $leaveRequest=$todayLeave->onlyPending();
                    $data['pendingRequestCount']=$employeeRequests->count()+$leaveRequest->count();

                    $presentCount=Attendance::whereIn('PIN', $manageableEmployeeMachineIDs)
                        ->where('DateTime', 'like', "$date %")
                        ->distinct('PIN')
                        ->count();
                    $data['todayPresentCount']=$presentCount;
                    $data['todayAbsentCount']=$manageableEmployeeIdsCount-$presentCount;
                    $data['todayLeaveCount']=$todayLeave->onlyApproved()->count();
                    $data['manageableEmployeesAttendanceReports']=(new ReportsController())->getReports($request, ['a'=>'employee-attendance-report', 'emp_id'=>$manageableEmployeeIds]);
                }
                return response()->json(['status' => 1, 'data' => $data]);
            }
            if($a=='get-late-employee-data'){
                $empIds = $request->id ? explode(',', $request->id) : [];
                $lateEmployee=Employee::whereIn('emp_id', $empIds)->get();
                $attendanceData=[];
                foreach ($lateEmployee as $item){
                    $attendanceData[]=(new AttendanceController())->employeeAttendanceData($item, $date);
                }
                return response()->json(['status' => 1, 'data' => $attendanceData]);
            }
            if($a=='get-attendance-data'){
                $dutySlots=DutySlot::get();
                /*$dutySlotRules=DutySlotRule::where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->get();*/
                $regularDutySlots=$dutySlots->where('is_shifting_duty', 0)->pluck('id');
                $shiftingDutySlots=$dutySlots->where('is_shifting_duty', 1)->pluck('id');


                $notCheckedInList = Employee::whereNotIn('biometric_id', function ($query) use ($date) {
                    $query->select('PIN')
                        ->from((new Attendance())->getTable())
                        ->where('DateTime', 'like', "$date%");
                })->get();
                $notCheckedInEmployee=$notCheckedInList->pluck('emp_id');

                $todayAttendance=Attendance::where('DateTime', 'like', "$date %")
                    ->where('is_manual', 0)
                    ->get();
                $todayEmployeeAttendance = $todayAttendance->groupBy('PIN');
                $todayEmployeeAttendance = $todayEmployeeAttendance->map(function ($attendanceRecords) {
                    $sortedRecords = $attendanceRecords->sortBy('DateTime')->values();
                    $firstEntry = $sortedRecords->first();
                    $lastEntry = $sortedRecords->count() > 1 ? $sortedRecords->last() : null;
                    return compact('firstEntry', 'lastEntry');
                });

                $dutySlotsTime = [];
                foreach ($dutySlots as $item) {
                    $dutySlotRule=$item->dutySlotRule($date);
                    $dutySlotsTime[$item->id] = [
                        'id' => $item->id,
                        'start_time' => $dutySlotRule ? $dutySlotRule->start_time : $item->start_time,
                        'threshold_time' => $dutySlotRule ? $dutySlotRule->threshold_time : $item->threshold_time,
                        'end_time' => $dutySlotRule ? $dutySlotRule->end_time : $item->end_time,
                        'is_shifting_duty' => $item->is_shifting_duty,
                    ];
                }

                $checkedInEmployees=Employee::whereNotIn('emp_id', $notCheckedInEmployee)
                    ->get();
                $lateCount=0;
                $lateEmployee=[];
                $regularCount=0;
                $earlyLeaveCount = 0;
                foreach ($checkedInEmployees as $employee) {
                    $empId = $employee->emp_id;
                    $name = $employee->full_name;
                    $pin = $employee->biometric_id;
                    $dutySlotId = $employee->duty_slot;
                    $dutySlot = isset($dutySlotsTime[$dutySlotId]) ? $dutySlotsTime[$dutySlotId] : null;
                    //echo "$empId  $name <br>";
                    if ($dutySlot) {
                        /*echo "Duty Slot ID: {$dutySlot['id']}<br>";
                        echo "Start Time: {$dutySlot['start_time']}<br>";
                        echo "Threshold Time: {$dutySlot['threshold_time']}<br>";
                        echo "End Time: {$dutySlot['end_time']}<br>";*/
                        if (isset($todayEmployeeAttendance[$pin])) {
                            $attendance = $todayEmployeeAttendance[$pin];

                            if ($attendance['firstEntry']) {
                                $firstEntryTime = Carbon::parse($attendance['firstEntry']->DateTime);
                                if($dutySlot['is_shifting_duty']==1){
                                    $dutySlot=$employee->dutySlot;
                                    if($dutySlot->dutySlotRule($date)){
                                        $dutySlot=$dutySlot->dutySlotRule($date);
                                    }
                                    $thresholdTime=Carbon::parse($date . ' ' . $dutySlot->threshold_time);;
                                }else{
                                    $thresholdTime = Carbon::parse($date . ' ' . $dutySlot['threshold_time']);
                                }
                                if ($firstEntryTime->greaterThan($thresholdTime)) {
                                    $lateCount++;
                                    $lateEmployee[]=$empId;
                                    //echo "Late: Yes<br>";
                                } else {
                                    $regularCount++;
                                    //echo "Late: No<br>";
                                }
                                //echo "First Entry - DateTime: {$attendance['firstEntry']->DateTime}, Attendance ID: {$attendance['firstEntry']->id}<br>";
                            }

                            if ($attendance['lastEntry']) {
                                $lastEntryTime = Carbon::parse($attendance['lastEntry']->DateTime);
                                if($dutySlot['is_shifting_duty']==1){
                                    $dutySlot=$employee->dutySlot;
                                    if($dutySlot->dutySlotRule($date)){
                                        $dutySlot=$dutySlot->dutySlotRule($date);
                                    }
                                    $endTime=Carbon::parse($date . ' ' . $dutySlot->end_time);;
                                }else{
                                    $endTime = Carbon::parse($date . ' ' . $dutySlot['end_time']);
                                }
                                if(now()>$endTime){
                                    if ($lastEntryTime->lessThan($endTime)) {
                                        $earlyLeaveCount++;
                                        //echo "Early Leave: Yes<br>";
                                    } else {
                                        //echo "Early Leave: No<br>";
                                    }
                                }
                                //echo "Last Entry - DateTime: {$attendance['lastEntry']->DateTime}, Attendance ID: {$attendance['lastEntry']->id}<br>";
                            }
                        } else {
                            //echo "No attendance records found.<br>";
                            $regularCount++;
                        }



                    } else {
                        $regularCount++;
                        //echo "Duty Slot not found.<br>";
                    }
                    //echo "<br>"; // Add a line break between employees
                }

                /*    return now();
                echo '<br>Checked In Late Count: '. $lateCount;
                echo '<br>Checked In Regular Count: '. $regularCount;
                echo '<br>Not Checked In Count: '. $notCheckedInList->count();
                echo "Early Leave Count: $earlyLeaveCount<br>";
                echo '<br>Total: '. ($lateCount+$regularCount+$notCheckedInList->count());*/


                //return false;

                $attendanceStats=[
                    'regularCount'=>$regularCount,
                    'notCheckedInCount'=>$notCheckedInList->count(),
                    'notCheckedInList'=>View::make('dashboard.notCheckedInList', compact('notCheckedInList', 'date'))->render(),
                    'lateCount'=>$lateCount,
                    'lateEmployee'=> $lateEmployee,
                    'earlyLeaveCount'=>$earlyLeaveCount,
                    'leaveCount'=>count($todayLeave),
                ];
                return response()->json(['status' => 1, 'data' => $attendanceStats]);
            }
            return response()->json(['status'=>0, 'msg'=>'']);
        }

        $lastSync=Attendance::orderBy('sync_dtime', 'desc')->first()->sync_dtime ?? '';


        $currentMonth = Carbon::now()->startOfMonth();
        $nextMonth = Carbon::now()->addMonth()->startOfMonth();
        $holiday = Holiday::where(function ($query) use ($currentMonth, $nextMonth) {
            $query->whereBetween('start_date', [$currentMonth, $nextMonth])
                ->orWhereBetween('end_date', [$currentMonth, $nextMonth])
                ->orWhere(function ($query) use ($currentMonth, $nextMonth) {
                    $query->where('start_date', '<=', $currentMonth)
                        ->where('end_date', '>=', $nextMonth);
                });
        })
            ->orderBy('start_date', 'desc')
            ->limit(10)
            ->get();

        $weekend=Weekend::get()->pluck('days');
        $notices=Notice::orderBy('notice_date', 'desc')->limit(10)->get();
        $organizations = Organization::get();
        return view('dashboard.index', compact( 'todayLeave', 'lastSync',   'holiday', 'weekend', 'notices','organizations'));
    }
}
