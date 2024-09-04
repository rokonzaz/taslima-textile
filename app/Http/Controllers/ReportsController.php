<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Organization;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\DutySlot;
use App\Models\DutySlotRule;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Weekend;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ReportsController extends Controller
{
    public $reportType='';
    public function index(Request $request)
    {

        /*$startDate = Carbon::parse('2024-06-01');
        $endDate = Carbon::parse('2024-06-31');

        $startDateTime=Carbon::parse($startDate)->startOfDay();
        $endDateTime=Carbon::parse($endDate)->endOfDay();

        $employee=Employee::active()->get();
        $allLeave=Leave::where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->where('approval_status', 2)
            ->get();
        $allLeave=$this->leaveDataASGroupedArray($allLeave);
        $dutySlots=DutySlot::get();
        $regularDutySlots=$dutySlots->where('is_shifting_duty', 0)->pluck('id');
        $shiftingDutySlots=$dutySlots->where('is_shifting_duty', 1)->pluck('id');

        $dutySlotRules=DutySlotRule::where(function ($query) use($startDate, $endDate){
            $query->where('start_date', '>=', $startDate)->where('start_date', '<=', $endDate);
        })
            ->orWhere(function ($query) use($startDate, $endDate){
                $query->where('end_date', '>=', $startDate)->where('end_date', '<=', $endDate);
            })
            ->get();
        $dutySlotRules=$this->dutySlotRulesGroupedArray($dutySlotRules);

        $allAttendance = Attendance::where('DateTime', '>=', $startDate)
            ->where('DateTime', '<=', $endDateTime)
            ->orderBy('DateTime', 'asc')
            ->get();
        $attendanceData=$this->attendanceDataGroupedArray($allAttendance);
        $weekends=Weekend::get()->pluck('days')->toArray();

        return $attendanceReport=$this->dateWiseAttendanceReport($startDate, $startDateTime, $endDate, $endDateTime,$employee, $allLeave, $dutySlots, $dutySlotRules, $regularDutySlots, $shiftingDutySlots, $attendanceData, $weekends);
        */


        $designations = Designations::all();
        $departments = Departments::all();
        $organizations = Organization::get();
        return view('reports.index', compact('organizations', 'designations', 'departments'));
    }

    public function getEmployeeReports(Request $request, $data=[])
    {

        $organization = $request->organization;
        $department = $request->department;
        $designation = $request->designation;
        $date = $request->date ?? date('Y-m-d');
        if($data['reportType']=='date-wise'){
            $start_date=$date;
            $end_date=$date;
        }elseif($data['reportType']=='monthly'){
            $start_date=$data['start_date'];
            $end_date=$data['end_date'];
            if($end_date>now()) $end_date=date('Y-m-d');
        }else{
            $start_date=$request->start_date ?? date('Y-m-d');
            $end_date=$request->end_date ?? date('Y-m-d');
        }

        $nonParseStartDate=$start_date;
        $nonParseEndDate=$end_date;
        $startDate = Carbon::parse($nonParseStartDate);
        $endDate = Carbon::parse($nonParseEndDate);

        $startDateTime=Carbon::parse($startDate)->startOfDay();
        $endDateTime=Carbon::parse($endDate)->endOfDay();

        if(isset($data['specific-employee']) && $data['specific-employee']==true){
            $employeeIds=$data['employeeIds'];
            $employee=Employee::whereIn('emp_id', $employeeIds)->get();
        }elseif(isset($data['specific-single-employee']) && $data['specific-single-employee']==true){
            $emp_id=$data['emp_id'];
            $employee=Employee::where('emp_id', $emp_id)->get();
        }else{
            $query = Employee::query();
            if ($organization) $query->where('organization', $organization);
            if ($department) $query->where('department', $department);
            if ($designation) $query->where('designation', $designation);
            $employee=$query->get();

        }

        $allLeave=Leave::onlyApproved()
            ->where('start_date', '<=', $nonParseStartDate)
            ->where('end_date', '>=', $nonParseEndDate)
            ->get();
        $allLeave=$this->leaveDataASGroupedArray($allLeave);
        $dutySlots=DutySlot::get();
        $regularDutySlots=$dutySlots->where('is_shifting_duty', 0)->pluck('id');
        $shiftingDutySlots=$dutySlots->where('is_shifting_duty', 1)->pluck('id');

        $dutySlotRules=DutySlotRule::where(function ($query) use($nonParseStartDate, $nonParseEndDate){
            $query->where('start_date', '>=', $nonParseStartDate)->where('start_date', '<=', $nonParseEndDate);
        })
            ->orWhere(function ($query) use($nonParseStartDate, $nonParseEndDate){
                $query->where('end_date', '>=', $nonParseStartDate)->where('end_date', '<=', $nonParseEndDate);
            })
            ->get();
        $dutySlotRules=$this->dutySlotRulesGroupedArray($dutySlotRules);

        $allAttendance = Attendance::where('DateTime', '>=', $startDateTime)
            ->where('DateTime', '<=', $endDateTime)
            ->orderBy('DateTime', 'asc')
            ->get();
        $attendanceData=$this->attendanceDataGroupedArray($allAttendance);
        $weekends=Weekend::get()->pluck('days')->toArray();

        $departments = Departments::pluck('name', 'id')->all();

        $designations = Designations::pluck('name', 'id')->all();

        return $attendanceReport=$this->dateWiseAttendanceReport([
            'additionalData'=>$data,
            'startDate'=>$startDate,
            'startDateTime'=>$startDateTime,
            'endDate'=>$endDate,
            'endDateTime'=>$endDateTime,
            'employee'=>$employee,
            'allLeave'=>$allLeave,
            'dutySlots'=>$dutySlots,
            'dutySlotRules'=>$dutySlotRules,
            'regularDutySlots'=>$regularDutySlots,
            'shiftingDutySlots'=>$shiftingDutySlots,
            'attendanceData'=>$attendanceData,
            'weekends'=>$weekends,
            'departments'=>$departments,
            'designations'=>$designations
        ]);

    }




    public function getReports(Request $request, $data=[])
    {

        $organization = $request->organization;
        $department = $request->department;
        $designation = $request->designation;
        $date = $request->date ?? date('Y-m-d');
        $start_date=$date;
        $end_date=$date;
        /* if(count($data)>0){
            $start_date='2024-07-01';
            $end_date='2024-07-31';
        } */


        if($request->a=='attendance-reports' || $request->a=='get-dashboard-basic-data'){
            $nonParseStartDate=$start_date;
            $nonParseEndDate=$end_date;
            $startDate = Carbon::parse($nonParseStartDate);
            $endDate = Carbon::parse($nonParseEndDate);

            $startDateTime=Carbon::parse($startDate)->startOfDay();
            $endDateTime=Carbon::parse($endDate)->endOfDay();

            $query = Employee::query();
            if ($organization) $query->where('organization', $organization);
            if ($department) $query->where('department', $department);
            if ($designation) $query->where('designation', $designation);
            $employee=$query->get();

            /*$allLeave=Leave::where(function ($query) use($nonParseStartDate, $nonParseEndDate){
                    $query->where('start_date', '>=', $nonParseStartDate)->where('start_date', '<=', $nonParseEndDate);
                })
                ->orWhere(function ($query) use($nonParseStartDate, $nonParseEndDate){
                    $query->where('end_date', '>=', $nonParseStartDate)->where('end_date', '<=', $nonParseEndDate);
                });*/
            $allLeave=Leave::where('start_date', '<=', $nonParseStartDate)
                ->where('end_date', '>=', $nonParseEndDate)
                ->where('approval_status', 2)
                ->get();
            $allLeave=$this->leaveDataASGroupedArray($allLeave);
            $dutySlots=DutySlot::get();
            $regularDutySlots=$dutySlots->where('is_shifting_duty', 0)->pluck('id');
            $shiftingDutySlots=$dutySlots->where('is_shifting_duty', 1)->pluck('id');

            $dutySlotRules=DutySlotRule::where(function ($query) use($nonParseStartDate, $nonParseEndDate){
                $query->where('start_date', '>=', $nonParseStartDate)->where('start_date', '<=', $nonParseEndDate);
            })
                ->orWhere(function ($query) use($nonParseStartDate, $nonParseEndDate){
                    $query->where('end_date', '>=', $nonParseStartDate)->where('end_date', '<=', $nonParseEndDate);
                })
                ->get();
            $dutySlotRules=$this->dutySlotRulesGroupedArray($dutySlotRules);

            $allAttendance = Attendance::where('DateTime', '>=', $startDateTime)
                ->where('DateTime', '<=', $endDateTime)
                ->orderBy('DateTime', 'asc')
                ->get();
            $attendanceData=$this->attendanceDataGroupedArray($allAttendance);
            $weekends=Weekend::get()->pluck('days')->toArray();

            $departments = Departments::pluck('name', 'id')->all();

            $designations = Designations::pluck('name', 'id')->all();

            $attendanceReport=$this->dateWiseAttendanceReport($startDate, $startDateTime, $endDate, $endDateTime,$employee, $allLeave, $dutySlots, $dutySlotRules, $regularDutySlots, $shiftingDutySlots, $attendanceData, $weekends, $departments, $designations);
            if(count($data)>0) return $attendanceReport;
            //try {
            $html = View::make('reports.date-wise-attendance-report', compact('attendanceReport', 'date'))->render();
            return response()->json(['status'=>1, 'html'=>$html]);
            /*}catch (\Exception $e){
                return response()->json(['status'=>0, 'msg'=>'Server Error']);
            }*/
        }

        /*try {
            $html = View::make('reports.attendance-report', compact('employee', 'start_date', 'end_date'))->render();
            return response()->json(['status'=>1, 'html'=>$html]);
        }catch (\Exception $e){
            return response()->json(['status'=>0, 'msg'=>'Server Error']);
        }*/

    }


    public function getReportsXXX(Request $request)
    {
        $company = $request->company;
        $department = $request->department;
        $designation = $request->designation;
        $date = $request->date ?? date('Y-m-d');
        $start_date=$date;
        $end_date=$date;



        if($request->a=='attendance-reports'){
            $nonParseStartDate=$start_date;
            $nonParseEndDate=$end_date;
            $startDate = Carbon::parse($nonParseStartDate);
            $endDate = Carbon::parse($nonParseEndDate);

            $startDateTime=Carbon::parse($startDate)->startOfDay();
            $endDateTime=Carbon::parse($endDate)->endOfDay();

            $query = Employee::query();
            $query = $query->active();
            if ($company) $query->where('company', $company);
            if ($department) $query->where('department', $department);
            if ($designation) $query->where('designation', $designation);
            $employee=$query->get();

            /*$allLeave=Leave::where(function ($query) use($nonParseStartDate, $nonParseEndDate){
                    $query->where('start_date', '>=', $nonParseStartDate)->where('start_date', '<=', $nonParseEndDate);
                })
                ->orWhere(function ($query) use($nonParseStartDate, $nonParseEndDate){
                    $query->where('end_date', '>=', $nonParseStartDate)->where('end_date', '<=', $nonParseEndDate);
                });*/
            $allLeave=Leave::where('start_date', '<=', $nonParseStartDate)
                ->where('end_date', '>=', $nonParseEndDate)
                ->where('approval_status', 2)
                ->get();
            $allLeave=$this->leaveDataASGroupedArray($allLeave);
            $dutySlots=DutySlot::get();
            $regularDutySlots=$dutySlots->where('is_shifting_duty', 0)->pluck('id');
            $shiftingDutySlots=$dutySlots->where('is_shifting_duty', 1)->pluck('id');

            $dutySlotRules=DutySlotRule::where(function ($query) use($nonParseStartDate, $nonParseEndDate){
                $query->where('start_date', '>=', $nonParseStartDate)->where('start_date', '<=', $nonParseEndDate);
            })
                ->orWhere(function ($query) use($nonParseStartDate, $nonParseEndDate){
                    $query->where('end_date', '>=', $nonParseStartDate)->where('end_date', '<=', $nonParseEndDate);
                })
                ->get();
            $dutySlotRules=$this->dutySlotRulesGroupedArray($dutySlotRules);

            $allAttendance = Attendance::where('DateTime', '>=', $startDateTime)
                ->where('DateTime', '<=', $endDateTime)
                ->orderBy('DateTime', 'asc')
                ->get();
            $attendanceData=$this->attendanceDataGroupedArray($allAttendance);
            $weekends=Weekend::get()->pluck('days')->toArray();

            $departments = Departments::pluck('name', 'id')->all();

            $designations = Designations::pluck('name', 'id')->all();

            $attendanceReport=$this->dateWiseAttendanceReport($startDate, $startDateTime, $endDate, $endDateTime,$employee, $allLeave, $dutySlots, $dutySlotRules, $regularDutySlots, $shiftingDutySlots, $attendanceData, $weekends, $departments, $designations);
            //try {
                $html = View::make('reports.date-wise-attendance-report', compact('attendanceReport', 'date'))->render();
                return response()->json(['status'=>1, 'html'=>$html]);
            /*}catch (\Exception $e){
                return response()->json(['status'=>0, 'msg'=>'Server Error']);
            }*/
        }

        /*try {
            $html = View::make('reports.attendance-report', compact('employee', 'start_date', 'end_date'))->render();
            return response()->json(['status'=>1, 'html'=>$html]);
        }catch (\Exception $e){
            return response()->json(['status'=>0, 'msg'=>'Server Error']);
        }*/

    }


    public function leaveDataASGroupedArray($allLeave)
    {
        $leaveGroupByEmpId=$allLeave->groupBy('emp_id');
        $leaveGroupByEmpIdAndDate=[];
        foreach ($leaveGroupByEmpId as $emp_id => $leave) {
            foreach ($leave as $item){
                $sdate=Carbon::parse($item->start_date);
                $edate=Carbon::parse($item->end_date);
                for ($date = $sdate; $date <= $edate; $date->addDay()) {
                    $data=[
                        'leave_type'=>$item->leave_type,
                        'leave_reason'=>$item->leave_reason,
                    ];
                    $leaveGroupByEmpIdAndDate[$emp_id][$date->toDateString()] = $data; // Store the date in the array
                }
            }

        }
        return $leaveGroupByEmpIdAndDate;
    }
    public function dutySlotRulesGroupedArray($dutySlotRules)
    {
        $dutySlotRules=$dutySlotRules->groupBy('duty_slot_id');
        $dutySlotRulesGrouped=[];
        foreach ($dutySlotRules as $slot_id => $slots) {
            foreach ($slots as $item){
                $sdate=Carbon::parse($item->start_date);
                $edate=Carbon::parse($item->end_date);
                for ($date = $sdate; $date <= $edate; $date->addDay()) {
                    $data=[
                        'start_time'=>$item->start_time,
                        'threshold_time'=>$item->threshold_time,
                        'end_time'=>$item->end_time,
                    ];
                    $dutySlotRulesGrouped[$slot_id][$date->toDateString()] = $data; // Store the date in the array
                }
            }

        }
        return $dutySlotRulesGrouped;
    }
    public function attendanceDataGroupedArray($allAttendance)
    {
        $allAttendanceGroupByPin = $allAttendance->groupBy('PIN');
        $allAttendanceGroupByPinAndDate = [];

        foreach ($allAttendanceGroupByPin as $pin => $attendances) {
            $groupByDate = $attendances->groupBy(function ($item) {
                $carbonDate = Carbon::parse($item->DateTime);
                return $carbonDate->format('Y-m-d');
            });
            $formattedAttendance = [];
            foreach ($groupByDate as $date => $entries) {
                $clockIn = $entries->first();
                $clockOut = $entries->last();
                if ($entries->count() === 1) {
                    $clockOut = null;
                }
                $formattedAttendance[$date] = [
                    'clockIn' => $clockIn,
                    'clockOut' => $clockOut,
                ];
            }
            $allAttendanceGroupByPinAndDate[$pin] = $formattedAttendance;
        }
        return $allAttendanceGroupByPinAndDate;
    }
    public function dateWiseAttendanceReport($data=[])
    {

        $startDate=$data['startDate'];
        $startDateTime=$data['startDateTime'];
        $endDate=$data['endDate'];
        $endDateTime=$data['endDateTime'];
        $employee=$data['employee'];
        $allLeave=$data['allLeave'];
        $dutySlots=$data['dutySlots'];
        $dutySlotRules=$data['dutySlotRules'];
        $regularDutySlots=$data['regularDutySlots'];
        $shiftingDutySlots=$data['shiftingDutySlots'];
        $attendanceData=$data['attendanceData'];
        $weekends=$data['weekends'];
        $departments=$data['departments'];
        $designations=$data['designations'];
        $additionalData=$data['additionalData'];


        $reportData=[];
        $globalSummary=[
            'present'=>0,
            'absent'=>0,
            'leave'=>0,
            'late'=>0,
            'early-leave'=>0,
        ];
        if($additionalData['reportType']=='date-wise'){
            $employeeReports=[];
        }
        foreach($employee as $key=>$item){
            $dutySlotId=$item->duty_slot;
            $dutySlot=$dutySlots->find($item->duty_slot);
            $leaveCount=0;
            $presentCount=0;
            $absentCount=0;
            $lateCount=0;
            $earlyLeaveCount=0;
            $overtimeCount=0;
            $weekendCount=0;
            $employeeData=[];
            $currentDate = $startDateTime->copy();
            $dateWiseAttendanceData=[];
            while($currentDate <= $endDateTime){
                $absentCountableInside=true;
                if($additionalData['reportType']=='date-wise') {
                    if ($currentDate != $startDateTime) {
                        $currentDate->addDay();
                        continue;
                    }
                }
                $clockIn = '';
                $clockOut = '';
                $late = '';
                $earlyLeave = '';
                $overtime = '';
                $comment='Present';
                $dutyStartTime='';
                $dutyThresholdTime='';
                $dutyEndTime='';

                $formattedCurrentDate=$currentDate->format('Y-m-d');
                if(in_array($currentDate->format('l'), $weekends)) {
                    $weekendCount++;
                    $comment = 'Weekends';
                }elseif(isset($allLeave[$item->emp_id][$formattedCurrentDate])) {
                    $leaveCount++;
                    $globalSummary['leave']++;
                    $comment='Leave';
                }else {
                    if ($item->biometric_id != '') {
                        if (isset($attendanceData[$item->biometric_id][$formattedCurrentDate])) {
                            $attendance = $attendanceData[$item->biometric_id][$formattedCurrentDate];
                            if (isset($attendance['clockIn']) && $attendance['clockIn']!='') {
                                $globalSummary['present']++;
                                $clockIn = Carbon::parse($attendance['clockIn']['DateTime'])->format('h:i:s A');
                                if ($dutySlot) {
                                    $dutyStartTime = isset($dutySlotRules[$dutySlotId][$formattedCurrentDate]) ? Carbon::parse($dutySlotRules[$dutySlotId][$formattedCurrentDate]['start_time']) : Carbon::parse($dutySlot->start_time);
                                    $dutyThresholdTime = isset($dutySlotRules[$dutySlotId][$formattedCurrentDate]) ? Carbon::parse($dutySlotRules[$dutySlotId][$formattedCurrentDate]['threshold_time']) : Carbon::parse($dutySlot->threshold_time);
                                    if (Carbon::parse($clockIn) > $dutyThresholdTime) {
                                        $dif = Carbon::parse($clockIn)->diffInMinutes($dutyStartTime);
                                        $late = minutesToHour($dif);
                                        $lateCount++;
                                        $globalSummary['late']++;
                                    }
                                }
                                $presentCount++;

                            }else{
                                $absentCount++;
                                $absentCountableInside=false;
                                $globalSummary['absent']++;
                                $comment='Absent';
                            }
                            if (isset($attendance['clockOut'])) {
                                $clockOut = Carbon::parse($attendance['clockOut']['DateTime'])->format('h:i:s A');
                                if ($dutySlot) {
                                    $dutyTime = isset($dutySlotRules[$dutySlotId][$formattedCurrentDate]) ? Carbon::parse($dutySlotRules[$dutySlotId][$formattedCurrentDate]['end_time']) : Carbon::parse($dutySlot->end_time);
                                    $dutyEndTime=$dutyTime;
                                    if (Carbon::parse($clockOut) < $dutyTime) {
                                        $dif = Carbon::parse($clockOut)->diffInMinutes($dutyTime);
                                        $earlyLeave = minutesToHour($dif);
                                        $earlyLeaveCount++;
                                        $globalSummary['early-leave']++;
                                    } else {
                                        $dif = Carbon::parse($clockOut)->diffInMinutes($dutyTime);
                                        $overtimeCount += $dif;
                                        $overtime = minutesToHour($dif);
                                    }
                                }
                            }
                        }else{
                            if($absentCountableInside){
                                $absentCount++;
                                $globalSummary['absent']++;
                                $absentCountableInside=false;
                            }
                            $comment='Absent';
                        }
                    }else{
                        if($absentCountableInside){
                            $absentCount++;
                            $globalSummary['absent']++;
                            $absentCountableInside=false;
                        }
                        $comment='N/A';
                    }
                }
                if(isset($additionalData['onlyLateEmployee']) && $additionalData['onlyLateEmployee']==true){
                    if($late=='') {
                        $currentDate->addDay();
                        continue;
                    }
                }
                if($additionalData['reportType']=='date-wise'){
                    $employeeReports[]=[
                        'emp_id'=>$item->emp_id,
                        'emp_name'=>$item->full_name,
                        'emp_department'=>$departments[$item->department]??'',
                        'emp_designation'=>$designations[$item->designation]??'',
                        'date'=>$formattedCurrentDate,
                        'dutyStartTime'=>$dutyStartTime!='' ? $dutyStartTime->format('h:i A') :'',
                        'dutyThresholdTime'=>$dutyThresholdTime!='' ? $dutyThresholdTime->format('h:i A') : '',
                        'dutyEndTime'=>$dutyEndTime!='' ? $dutyEndTime->format('h:i A') : '',
                        'clockIn'=>$clockIn,
                        'clockOut'=>$clockOut,
                        'late'=>$late,
                        'earlyLeave'=>$earlyLeave,
                        'overtime'=>$overtime,
                        'comment'=>$comment
                    ];
                }else{
                    $dateWiseAttendanceData[$formattedCurrentDate]=[
                        'dutyStartTime'=>$dutyStartTime!='' ? $dutyStartTime->format('h:i A') :'',
                        'dutyThresholdTime'=>$dutyThresholdTime!='' ? $dutyThresholdTime->format('h:i A') : '',
                        'dutyEndTime'=>$dutyEndTime!='' ? $dutyEndTime->format('h:i A') : '',
                        'clockIn'=>$clockIn,
                        'clockOut'=>$clockOut,
                        'late'=>$late,
                        'earlyLeave'=>$earlyLeave,
                        'overtime'=>$overtime,
                        'comment'=>$comment
                    ];
                }

                $currentDate->addDay();
            }

            $reportData[]=[
                'emp_id'=>$item->emp_id,
                'emp_name'=>$item->full_name,
                'emp_department'=>$departments[$item->department]??'',
                'emp_designation'=>$designations[$item->designation]??'',
                'dateWiseAttendanceData'=>$dateWiseAttendanceData,
                'emp_summary'=>[
                    'leaveCount'=>$leaveCount,
                    'lateCount'=>$lateCount,
                    'earlyLeaveCount'=>$earlyLeaveCount,
                    'overtimeCount'=>$overtimeCount,
                    'presentCount'=>$presentCount,
                    'absentCount'=>$absentCount,
                    'weekendCount'=>$weekendCount,
                ]
            ];
        }
        if($additionalData['reportType']=='date-wise'){
            return $employeeReports;
        }else {
            return [
                'data' => $reportData,
                'globalSummary' => $globalSummary
            ];
        }
    }
}
