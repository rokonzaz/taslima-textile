<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ZkTeco\Exceptions\ConnectionError;
use App\Models\Attendance;
use App\Models\AttendancesDetails;
use App\Models\Organization;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\Employee;
use App\Models\ImportLog;
use App\Models\Reasons;
use App\Models\Setting;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\ZkTeco\TADFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{

    public function index(Request $request)
    {
        if(!userCan('attendance.view')) return view('error-page.unauthorized');
        $designations = Designations::all();
        $departments = Departments::all();
        $organizations = Organization::get();
        if ($request->ajax()) {
            $searchKey = $request->searchKey ?? '';
            $organization = $request->organization;
            $department = $request->department;
            $designation = $request->designation;
            $date = $request->date ?? date('Y-m-d');
            $start_date=$date;
            $end_date=$date;
            $limit = $request->length;
            if ($limit < 0) $limit = 10;
            $offset = $request->start;
            $orderByColumn = null;
            $orderByDirection = null;

            $lastSync=Attendance::orderBy('sync_dtime', 'desc')->first()->sync_dtime ?? '';

            if (isset($request->order) && count($request->order) > 0) {
                $firstOrderItem = $request->order[0];
                $orderByColumn = $firstOrderItem['name'] ?? null;
                $orderByDirection = $firstOrderItem['dir'] ?? null;
            }
            $query = Employee::query();
            $recordsTotal = $query->count();
            if ($searchKey) {
                $query->where(function ($query) use ($searchKey) {
                    $query->orWhere('emp_id', 'like', "%{$searchKey}%");
                    $query->orWhere('full_name', 'like', "%{$searchKey}%");
                    $query->orWhere('email', 'like', "%{$searchKey}%");
                    $query->orWhere('phone', 'like', "%{$searchKey}%");
                    $query->orWhereIn('organization', function ($subquery) use ($searchKey) {
                        $subquery->select('id')->from(with(new Organization())->getTable())->where('name', 'like', "%$searchKey%");
                    });
                    $query->orWhereIn('department', function ($subquery) use ($searchKey) {
                        $subquery->select('id')->from(with(new Departments())->getTable())->where('name', 'like', "%$searchKey%");
                    });
                    $query->orWhereIn('designation', function ($subquery) use ($searchKey) {
                        $subquery->select('id')->from(with(new Designations())->getTable())->where('name', 'like', "%$searchKey%");
                    });
                    $query->orWhere('gender', 'like', "%{$searchKey}%");

                });
            }
            if ($organization) $query->where('organization', $organization);
            if ($department) $query->where('department', $department);
            if ($designation) $query->where('designation', $designation);

            // ORDERS

            switch ($orderByColumn) {
                case 'employeeID':
                    $query->orderBy('emp_id', $orderByDirection);
                    break;
                case 'name':
                    $query->orderBy('full_name', $orderByDirection);
                    break;
                case 'organization':
                    $query->orderBy(Organization::select('name')->whereColumn('id', 'employees.organization'), $orderByDirection);
                    break;
                case 'department':
                    $query->orderBy(Departments::select('name')->whereColumn('id', 'employees.department'), $orderByDirection);
                    break;
                case 'designation':
                    $query->orderBy(Departments::select('name')->whereColumn('id', 'employees.designation'), $orderByDirection);
                    break;
                case 'phone':
                    $query->orderBy('phone', $orderByDirection);
                    break;
                case 'gender':
                    $query->orderBy('gender', $orderByDirection);
                    break;
                default:
                    $query->orderBy('id', 'asc');
                    break;
            }

            $recordsFiltered = $query->get();  // Filtered Data to count
            $query->limit($limit)->offset($offset);

            $employees = $query->get();

            $employeeData = [];
            foreach ($employees as $item) {
                $employeeData[] = $this->employeeAttendanceData($item, $date);
            }

            $response = [
                'status' => 1,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered->count(),
                'data' => $employeeData,
                'selectedDate' => date('d M Y', strtotime($date)),
                'lastSyncDate' => $lastSync!='' ? date('d M Y', strtotime($lastSync)) : '',
                'lastSyncTime' => $lastSync!='' ? date('H:i:s a', strtotime($lastSync)) : '',
                'orderableColumns' => $orderByColumn . $orderByDirection
            ];
            return response()->json($response);
        }

        return view('attendance.index', compact('organizations', 'designations', 'departments'));
    }

    public function employeeAttendanceData($employee, $date)
    {
        $start_date=$date;
        $end_date=$date;
        $defaultProfileImage=employeeDefaultProfileImage($employee->gender);
        $profile_img=employeeProfileImage($employee->emp_id, $employee->profile_photo);
        $attendance=$employee->attendanceData($start_date, $end_date);
        $attendanceCount = $attendance->count();
        $clockIn=null;
        $clockOut=null;
        $late='';
        $overtime='';
        $earlyLeaving='';
        $dutySlot=[];
        $dutyStartTime='';
        $dutyThresholdTime='';
        $dutyEndTime='';
        $additionalDetails='';
        $isManualClockIn=0;
        $isManualClockOut=0;
        $clockInAtt=[];
        $clockOutAtt=[];
        $dutySlotTitle='';
        $startTime='10:00';
        $thresholdTime='10:15';
        $endTime='18:00';

        $lateEntryNote='';
        $earlyExitNote='';
        $homeOfficeNote='';

        if($employee->dutySlot){
            $dutySlot=$employee->dutySlot;
            if($dutySlot->dutySlotRule($date)){
                $dutySlot=$dutySlot->dutySlotRule($date);
                $dutySlotTitle=$dutySlot->title;
            }
            $startTime=$dutySlot->start_time;
            $thresholdTime=$dutySlot->threshold_time;
            $endTime=$dutySlot->end_time;
        }
        $leave=$employee->leaveDateWise($date, $date);
        $formattedLeaveDate='';
        if($leave){
            $leaveStartDate = Carbon::parse($leave->start_date);
            $leaveEndDate = Carbon::parse($leave->end_date);
            if ($leaveStartDate->isSameDay($leaveEndDate)) {
                $formattedLeaveDate = $leaveStartDate->format('d M Y');
            } elseif ($leaveStartDate->isSameMonth($leaveEndDate)) {
                $formattedLeaveDate = $leaveStartDate->format('d') . '-' . $leaveEndDate->format('d M Y');
            } else {
                $formattedLeaveDate = $leaveStartDate->format('d M Y') . ' - ' . $leaveEndDate->format('d M Y');
            }
        }
        if($attendanceCount>0){
            $sortedAttendances = $attendance->sortBy('DateTime');
            // Clock In
            $clockInAtt=$sortedAttendances->first();
            $clockIn = $clockInAtt['DateTime'];

            // Clock out
            if($attendanceCount == 1){
                $clockOut=null;
            }else{
                $clockOutAtt=$sortedAttendances->last();
                $clockOut=$clockOutAtt['DateTime'];
                $isManualClockOut=$clockOutAtt['is_manual'];
            }

            // Late Count
            $clockInTime = strtotime($clockIn);
            $dutySlotStartTime = strtotime("$date $startTime");
            $dutySlotThresholdTime = strtotime("$date $thresholdTime");
            if($clockInTime>$dutySlotThresholdTime) {
                $lateCount = floor(($clockInTime - $dutySlotStartTime) / 60);
                $hours = floor($lateCount / 60); // Calculate hours
                $minutes = $lateCount % 60;
                if($hours>0) $late = $hours . 'h ' .$minutes.'m';
                else $late = $minutes.'m';
            }
            else{
                //$late = "Not Late";
            }
            // Over Time
            $clockOutTime = strtotime($clockOut);
            $dutySlotEndTime = strtotime("$date $endTime");
            if($clockOut){
                if($clockOutTime>$dutySlotEndTime) {
                    $lateCount = floor(($clockOutTime - $dutySlotEndTime) / 60);
                    $hours = floor($lateCount / 60); // Calculate hours
                    $minutes = $lateCount % 60;
                    if($hours>0) $overtime = $hours . 'h ' .$minutes.'m';
                    else $overtime = $minutes.'m';
                }
            }

            // Early Leaving
            if(time()>$dutySlotEndTime && $clockOut){
                if($clockOutTime<$dutySlotEndTime) {
                    $lateCount = floor(($dutySlotEndTime - $clockOutTime) / 60);
                    $hours = floor($lateCount / 60); // Calculate hours
                    $minutes = $lateCount % 60;
                    if($hours>0) $earlyLeaving = $hours . 'h ' .$minutes.'m';
                    else $earlyLeaving = $minutes.'m';
                }
            }
        }

        $employeeRequest=$employee->employeeApprovedRequest;
        if($employeeRequest){
            $lateEntryRequest=$employeeRequest->where('request_type', 'late-arrival')->where('date', $start_date)->first();
            if($lateEntryRequest){
                $lateEntryNote=$lateEntryRequest->note;
            }

            $earlyExitRequest=$employeeRequest->where('request_type', 'early-exit')->where('date', $start_date)->first();
            if($earlyExitRequest){
                $earlyExitNote=$earlyExitRequest->note;
            }
            $homeOfficeRequest=$employeeRequest->where('request_type', 'home-office')->where('start_date', '<=', $start_date)->where('end_date', '>=', $start_date)->first();
            if($homeOfficeRequest){
                $homeOfficeNote=$homeOfficeRequest->note;
            }
        }
        $attendanceDetails=$employee->attendanceDetails($date);
        $employeeData = [
            'id' => $employee->id,
            'emp_id' => $employee->emp_id ?? '',
            'name' => $employee->full_name ?? '',
            'profile_img' => $profile_img,
            'profile_img_default' => $defaultProfileImage,
            'email' => $employee->email ?? '',
            'phone' => $employee->phone ?? '',
            'designation' => $employee->empDesignation->name ?? '',
            'department' => $employee->empDepartment->name ?? '',
            'organization' => $employee->empOrganization->name ? getFirstWord($employee->empOrganization->name) : '',
            'date' => $date,
            'formattedDate' => date('d M Y', strtotime($date)),
            'biometric_id' => $employee->biometric_id ?? '',
            'additionalDetails'=>$attendanceDetails,
            'attendance' => $attendance,
            'dutySlotName' => $employee->dutySlot->slot_name ?? '',
            'dutySlotTitle' => $dutySlotTitle?? '',
            'dutySlot' => $dutySlot,
            'dutyStartTime' => date('H:ia', strtotime($startTime)),
            'dutyThresholdTime' => date('H:ia', strtotime($thresholdTime)),
            'dutyEndTime' => date('h:ia', strtotime($endTime)),
            'attendanceCount' => $attendanceCount,
            'clockIn' => $clockIn,
            'clockInDetails'=>$clockInAtt,
            'clockOutDetails'=>$clockOutAtt,
            'clockOut' => $clockOut,
            'late' => $late,
            'earlyLeaving' => $earlyLeaving,
            'overtime' => $overtime,
            'leave' => $formattedLeaveDate,
            'lateEntryNote'=>$lateEntryNote,
            'earlyExitNote'=>$earlyExitNote,
            'homeOfficeNote'=>$homeOfficeNote
        ];
        return $employeeData;
    }

    public function syncAttendanceData(Request $request)
    {
        if(!userCan('attendance.sync')) return back()->with('error', 'Unauthorized Access!');
        $startDate=$request->start_date;
        $endDate=$request->end_date;
        if($startDate && $endDate){
            $deviceIp = Setting::where('name', 'fingerprint')
                ->where('title', 'device-ip')
                ->value('ip');
            if(!$deviceIp){
                return back()->with('error', 'Please set device configuration on setting!');
            }

            $tad_factory = new TADFactory(['ip' => $deviceIp]);
            $tad = $tad_factory->get_instance();
            $isConnected=false;
            try {
                $deviceDate=$tad->get_date();
                $isConnected=true;
            } catch (ConnectionError $e) {

            }
            if(!$isConnected){
                return back()->with('error', 'Connection Error!');
            }
            $all_attendance= $tad->get_att_log();
            $filtered_att_logs = $all_attendance->filter_by_date([
                'start' => $startDate,
                'end' => $endDate
            ]);
            $machineAttendance=$this->makeJson($filtered_att_logs);
            try {
                DB::beginTransaction();
                $existingAttendanceQuery = Attendance::whereBetween('DateTime', ["$startDate 00:00:00", "$endDate 23:59:59"])
                    ->where('is_manual', 0)->delete();
                if (Attendance::insert($machineAttendance)) {
                    DB::commit();
                    return back()->with('success', "Successfully Synced");
                } else {
                    DB::rollBack();
                    return back()->with('error', "An Error Occurred!");
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', "An Error Occurred: ");
            }
        }

    }

    public function makeJson($data)
    {
        $xmlString = <<<XML
        $data
        XML;
        $xml = simplexml_load_string($xmlString);
        $xmlArray = json_decode(json_encode($xml), true);
        return $xmlArray['Row'];
    }

    public function bulkTag(Request $request)
    {
        if(!userCan('attendance.bulk-tag')) return back()->with('error', 'Unauthorized Access!');
        return view('attendance.bulk-tag.index');
    }

    public function bulkTagSubmit(Request $request)
    {
        if(!userCan('attendance.bulk-tag')) return back()->with('error', 'Unauthorized Access!');
        if($request->has('file')){
            $file=$request->file('file');
            if (!($file->getClientOriginalExtension() === 'xls' || $file->getClientOriginalExtension() === 'xlsx')) {
                return back()->with('error', "None excel file not accepted");
            }
            $data = Excel::toCollection([], $file);
            $rows = $data[0]->toArray();
            $columnNames = $data->first()->first();

            $rowCount=0;
            $successCount=0;
            $errorCount=0;
            foreach (array_slice($rows, 1) as $row) {
                $rowCount++;
                $emp_id=$row['2'];
                $biometric_id=$row['1'];
                if(!$emp_id) {
                    $errorRow[]=[
                        'data'=>$row,
                        'status'=>0,
                        'msg'=>'Missing: Employee Id'
                    ];
                    $errorCount++;
                    continue;
                }
                $employee=Employee::where('emp_id', $emp_id)->first();
                if($employee){
                    $employee->biometric_id = $biometric_id;
                    if($employee->update()){
                        $errorRow[]=[
                            'data'=>$row,
                            'status'=>1,
                            'msg'=>'Success'
                        ];
                        $successCount++;
                    }
                }else{
                    $errorRow[]=[
                        'data'=>$row,
                        'status'=>0,
                        'msg'=>'Not Exists'
                    ];
                    $errorCount++;
                }
            }
            $importLog=new ImportLog();
            $importLog->name="Employee Biometric ID";
            $importLog->description=json_encode($errorRow);
            $importLog->row_count=$rowCount;
            $importLog->success_count=$successCount;
            $importLog->error_count=$errorCount;
            $importLog->save();
            return back()->with('success', "Tagged");
        }else{
            return back()->with('error', "Invalid Files");
        }
    }
    public function bulkTagMachineId(Request $request)
    {
        if(!userCan('attendance.bulk-tag')) return back()->with('error', 'Unauthorized Access!');
        $employee=Employee::get();
        return view('attendance.bulk-tag.bulk-tag-machine-id', compact('employee'));
    }
    function bulkTagMachineIdSubmit(Request $request)
    {
        $data=$request->id;
        foreach ($data as $empId => $biometricId) {
            Employee::where('emp_id', $empId)
                ->update(['biometric_id' => $biometricId]);
        };
        return back()->with('success', "Successfully Updated!");
    }

    public function addManualAttendance(Request $request, $empId, $date)
    {
        if(!userCan('attendance.add-manual-attendance')) return response()->json(['status'=>0,'msg'=>'Unauthorized Access!']);
        if($request->ajax()){
            $employee=Employee::where('emp_id', $empId)->first();
            $reasons = Reasons::where('category' , 'Attendance Reasons')->get();
            if($employee){
                $clockData=[
                    'clockIn'=>$this->getAttendanceData($employee, $date, 'clockIn')??'',
                    'clockOut'=>$this->getAttendanceData($employee, $date, 'clockOut')??'',
                ];
                $html=View::make('attendance.add-manual-attendance', compact('employee', 'date', 'reasons', 'clockData',))->render();
                $response = [
                    'status' => 1,
                    'html'=>$html,
                ];
                return response()->json($response);
            }else{
                return response()->json(['status'=>0,'msg'=>'Employee not found!']);
            }
        }
        abort(403);
    }
    public function editAttendanceDetails(Request $request, $empId, $date)
    {
        if(!userCan('attendance.edit-attendance-details')) return response()->json(['status'=>0,'msg'=>'Unauthorized Access!']);
        if($request->ajax()){
            $employee=Employee::where('emp_id', $empId)->first();
            $reasons = Reasons::where('category' , 'Attendance Reasons')->get();
            if($employee){
                $html=View::make('attendance.edit-attendance-details', compact('employee', 'date', 'reasons'))->render();
                $response = [
                    'status' => 1,
                    'html'=>$html,
                ];
                return response()->json($response);
            }else{
                return response()->json(['status'=>0,'msg'=>'Employee not found!']);
            }
        }
        abort(403);
    }
    /* public function updateAttendanceDetails(Request $request, $empId, $date)
    {
        if(!userCan('attendance.edit-attendance-details')) return response()->json(['status'=>0,'msg'=>'Unauthorized Access!']);
        if(!userCan('attendance.add-manual-attendance')) return response()->json(['status'=>0,'msg'=>'Unauthorized Access!']);
        if($request->ajax()){
            $employee=Employee::where('emp_id', $empId)->first();
            if($employee){
                if($request->isReasonSection==1){
                    $attDetails=new AttendancesDetails();
                    $attDetails->emp_id=$empId;
                    $attDetails->date=$date;
                    $attDetails->reason=$request->reason;
                    $attDetails->additional_note=$request->additional_note;
                    $attDetails->start_time=$request->start_time;
                    $attDetails->end_time=$request->end_time;
                    if($attDetails->save()){
                        return response()->json(['status'=>1, 'msg'=>'Successfully Updated!']);
                    }else{
                        return response()->json(['status'=>0, 'msg'=>'Error Occurred!']);
                    }
                }

                if($request->isNewAttendance==1){
                    $attendance_type=$request->attendance_type;
                    $time=$request->time;
                    $newDateTime=$date.' '.$time;
                    $pin=$employee->biometric_id;
                    if($attendance_type=='entry-time'){
                        $getAtt=Attendance::where('PIN', $pin)
                            ->where('DateTime', 'like', "$date %")
                            ->orderBy('DateTime', 'asc')
                            ->first();
                        if ($getAtt) {
                            $firstFinger = new DateTime($getAtt->DateTime);
                            $newFinger = new DateTime($newDateTime);
                            if ($newFinger > $firstFinger) {
                                return response()->json(['status'=>0, 'msg'=>'Please provide the time before first finger.']);
                            }
                        }

                    }else{
                        $getAtt=Attendance::where('PIN', $pin)
                            ->where('DateTime', 'like', "$date %")
                            ->orderBy('DateTime', 'desc')
                            ->first();
                        if ($getAtt) {
                            $lastFinger = new DateTime($getAtt->DateTime);
                            $newFinger = new DateTime($newDateTime);
                            if ($newFinger < $lastFinger) {
                                return response()->json(['status'=>0, 'msg'=>'Please provide the time after last finger.']);
                            }
                        }
                    }


                    $att=new Attendance();
                    $att->PIN=$pin;
                    $att->DateTime=$newDateTime;
                    $att->sync_dtime=null;
                    $att->is_manual=1;
                    $att->remarks=$request->finger_note;
                    if($att->save()){
                        return response()->json(['status'=>1, 'msg'=>'Successfully Updated!']);
                    }else{
                        return response()->json(['status'=>0, 'msg'=>'Error Occurred!']);
                    }


                }

            }else{
                return response()->json(['status'=>0,'msg'=>'Employee not found!']);
            }
        }
        abort(403);
    } */

    public function updateAttendanceDetails(Request $request, $empId, $date)
    {

        if(!userCan('attendance.edit-attendance-details')) return response()->json(['status' => 0, 'msg' => 'Unauthorized Access!']);
        if(!userCan('attendance.add-manual-attendance')) return response()->json(['status' => 0, 'msg' => 'Unauthorized Access!']);

        if($request->ajax()){
            $rules = [];
            if ($request->isReasonSection == 1) {
                $rules = [
                    'reason' => 'required|string',
                    'additional_note' => 'required_if:reason,Others|string',
                    'start_time' => 'required|date_format:H:i',
                    'end_time' => 'required|date_format:H:i|after:start_time',
                ];
            }

            if ($request->isNewAttendance == 1) {
                $rules = [
                    'attendance_type' => 'required|in:entry-time,exit-time',
                    'time' => 'required|date_format:H:i',
                    'finger_note' => 'required|string',
                ];
            }

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'msg' => 'Validation Error!', 'errors' => $validator->errors()]);
            }

            $employee = Employee::where('emp_id', $empId)->first();
            if (!$employee) {
                return response()->json(['status' => 0, 'msg' => 'Employee not found!']);
            }

            if ($request->isReasonSection == 1) {
                $attDetails = new AttendancesDetails();
                $attDetails->emp_id = $empId;
                $attDetails->date = $date;
                $attDetails->reason = $request->reason;
                $attDetails->additional_note = $request->additional_note;
                $attDetails->start_time = $request->start_time;
                $attDetails->end_time = $request->end_time;
                if ($attDetails->save()) {
                    return response()->json(['status' => 1, 'msg' => 'Successfully Updated!']);
                } else {
                    return response()->json(['status' => 0, 'msg' => 'Error Occurred!']);
                }
            }

            if ($request->isNewAttendance == 1) {
                $attendance_type = $request->attendance_type;
                $time = $request->time;
                $newDateTime = $date . ' ' . $time;
                $pin = $employee->biometric_id;

                if ($attendance_type == 'entry-time') {
                    $getAtt = Attendance::where('PIN', $pin)
                        ->where('DateTime', 'like', "$date %")
                        ->orderBy('DateTime', 'asc')
                        ->first();

                    if ($getAtt) {
                        $firstFinger = new DateTime($getAtt->DateTime);
                        $newFinger = new DateTime($newDateTime);
                        if ($newFinger > $firstFinger) {
                            return response()->json(['status' => 0, 'msg' => 'Please provide the time before first finger.']);
                        }
                    }

                } else {
                    $getAtt = Attendance::where('PIN', $pin)
                        ->where('DateTime', 'like', "$date %")
                        ->orderBy('DateTime', 'desc')
                        ->first();

                    if ($getAtt) {
                        $lastFinger = new DateTime($getAtt->DateTime);
                        $newFinger = new DateTime($newDateTime);
                        if ($newFinger < $lastFinger) {
                            return response()->json(['status' => 0, 'msg' => 'Please provide the time after last finger.']);
                        }
                    }
                }

                $att = new Attendance();
                $att->PIN = $pin;
                $att->DateTime = $newDateTime;
                $att->sync_dtime = null;
                $att->is_manual = 1;
                $att->remarks = $request->finger_note;
                if ($att->save()) {
                    return response()->json(['status' => 1, 'msg' => 'Successfully Updated!']);
                } else {
                    return response()->json(['status' => 0, 'msg' => 'Error Occurred!']);
                }
            }
        }
        abort(403);
    }

    function getAttendanceData($employee, $date, $content='clockIn')
    {
        $start_date=$date;
        $end_date=$date;
        $attendance=$employee->attendanceData($start_date, $end_date);
        $attendanceCount = $attendance->count();
        $clockIn=null;
        $clockOut=null;
        $late='';
        $overtime='';
        $earlyLeaving='';
        $dutySlot=[];
        $dutyStartTime='';
        $dutyThresholdTime='';
        $dutyEndTime='';
        $additionalDetails='';
        $isManualClockIn=0;
        $isManualClockOut=0;
        $clockInAtt=[];
        $clockOutAtt=[];
        $dutySlotTitle='';
        $startTime='10:00';
        $thresholdTime='10:15';
        $endTime='18:00';
        if($employee->dutySlot){
            $dutySlot=$employee->dutySlot;
            if($dutySlot->dutySlotRule($date)){
                $dutySlot=$dutySlot->dutySlotRule($date);
                $dutySlotTitle=$dutySlot->title;
            }
            $startTime=$dutySlot->start_time;
            $thresholdTime=$dutySlot->threshold_time;
            $endTime=$dutySlot->end_time;
        }
        $leave=$employee->leaveDateWise($date, $date);
        $formattedLeaveDate='';
        if($leave){
            $leaveStartDate = Carbon::parse($leave->start_date);
            $leaveEndDate = Carbon::parse($leave->end_date);
            if ($leaveStartDate->isSameDay($leaveEndDate)) {
                $formattedLeaveDate = $leaveStartDate->format('d M Y');
            } elseif ($leaveStartDate->isSameMonth($leaveEndDate)) {
                $formattedLeaveDate = $leaveStartDate->format('d') . '-' . $leaveEndDate->format('d M Y');
            } else {
                $formattedLeaveDate = $leaveStartDate->format('d M Y') . ' - ' . $leaveEndDate->format('d M Y');
            }
        }
        if($attendanceCount>0){
            $sortedAttendances = $attendance->sortBy('DateTime');
            // Clock In
            $clockInAtt=$sortedAttendances->first();
            $clockIn = $clockInAtt['DateTime'];

            // Clock out
            if($attendanceCount == 1){
                $clockOut=null;
            }else{
                $clockOutAtt=$sortedAttendances->last();
                $clockOut=$clockOutAtt['DateTime'];
                $isManualClockOut=$clockOutAtt['is_manual'];
            }

            // Late Count
            $clockInTime = strtotime($clockIn);
            $dutySlotStartTime = strtotime("$date $startTime");
            $dutySlotThresholdTime = strtotime("$date $thresholdTime");
            if($clockInTime>$dutySlotThresholdTime) {
                $lateCount = floor(($clockInTime - $dutySlotStartTime) / 60);
                $hours = floor($lateCount / 60); // Calculate hours
                $minutes = $lateCount % 60;
                if($hours>0) $late = $hours . 'h ' .$minutes.'m';
                else $late = $minutes.'m';
            }
            else{
                //$late = "Not Late";
            }
            // Over Time
            $clockOutTime = strtotime($clockOut);
            $dutySlotEndTime = strtotime("$date $endTime");
            if($clockOut){
                if($clockOutTime>$dutySlotEndTime) {
                    $lateCount = floor(($clockOutTime - $dutySlotEndTime) / 60);
                    $hours = floor($lateCount / 60); // Calculate hours
                    $minutes = $lateCount % 60;
                    if($hours>0) $overtime = $hours . 'h ' .$minutes.'m';
                    else $overtime = $minutes.'m';
                }
            }

            // Early Leaving
            if(time()>$dutySlotEndTime && $clockOut){
                if($clockOutTime<$dutySlotEndTime) {
                    $lateCount = floor(($dutySlotEndTime - $clockOutTime) / 60);
                    $hours = floor($lateCount / 60); // Calculate hours
                    $minutes = $lateCount % 60;
                    if($hours>0) $earlyLeaving = $hours . 'h ' .$minutes.'m';
                    else $earlyLeaving = $minutes.'m';
                }
            }
        }
        $attendanceDetails=$employee->attendanceDetails($date);
        if($content=='clockIn'){
            return $clockIn;
        }
        if($content=='clockOut'){
            return $clockOut;
        }
        /*$employeeData[] = [
            'id' => $item->id,
            'emp_id' => $item->emp_id ?? '',
            'full_name' => $item->full_name ?? '',
            'profile_img' => $profile_img,
            'profile_img_default' => $defaultProfileImage,
            'email' => $item->email ?? '',
            'phone' => $item->phone ?? '',
            'designation' => $item->empDesignation->name ?? '',
            'department' => $item->empDepartment->name ?? '',
            'organization' => $item->empOrganization->name ?? '',
            'date' => $date,
            'biometric_id' => $item->biometric_id ?? '',
            'additionalDetails'=>$attendanceDetails,
            'attendance' => $attendance,
            'dutySlotName' => $item->dutySlot->slot_name ?? '',
            'dutySlotTitle' => $dutySlotTitle?? '',
            'dutySlot' => $dutySlot,
            'dutyStartTime' => date('H:ia', strtotime($startTime)),
            'dutyThresholdTime' => date('H:ia', strtotime($thresholdTime)),
            'dutyEndTime' => date('h:ia', strtotime($endTime)),
            'attendanceCount' => $attendanceCount,
            'clockIn' => $clockIn,
            'clockInDetails'=>$clockInAtt,
            'clockOutDetails'=>$clockOutAtt,
            'clockOut' => $clockOut,
            'late' => $late,
        ];*/
    }

    function attendanceReporting(Request $request)
    {
        $month='2024-05-';
        $employee=Employee::where('organization', 1)->get();
        return view('attendance.reporting', compact('employee'));

        /*foreach ($employee as $item) {
            $defaultProfileImage=employeeDefaultProfileImage($item->gender);
            $profile_img=employeeProfileImage($item->emp_id, $item->profile_photo);
            $attendance=$item->attendanceData($start_date, $end_date);
            $attendanceCount = $attendance->count();
            $clockIn=null;
            $clockOut=null;
            $late='';
            $overtime='';
            $earlyLeaving='';
            $dutySlot=[];
            $dutyStartTime='';
            $dutyThresholdTime='';
            $dutyEndTime='';
            $additionalDetails='';
            $isManualClockIn=0;
            $isManualClockOut=0;
            $clockInAtt=[];
            $clockOutAtt=[];
            $dutySlotTitle='';
            $startTime='10:00';
            $thresholdTime='10:15';
            $endTime='18:00';
            if($item->dutySlot){
                $dutySlot=$item->dutySlot;
                if($dutySlot->dutySlotRule($date)){
                    $dutySlot=$dutySlot->dutySlotRule($date);
                    $dutySlotTitle=$dutySlot->title;
                }
                $startTime=$dutySlot->start_time;
                $thresholdTime=$dutySlot->threshold_time;
                $endTime=$dutySlot->end_time;
            }
            $leave=$item->leaveDateWise($date, $date);
            $formattedLeaveDate='';
            if($leave){
                $leaveStartDate = Carbon::parse($leave->start_date);
                $leaveEndDate = Carbon::parse($leave->end_date);
                if ($leaveStartDate->isSameDay($leaveEndDate)) {
                    $formattedLeaveDate = $leaveStartDate->format('d M Y');
                } elseif ($leaveStartDate->isSameMonth($leaveEndDate)) {
                    $formattedLeaveDate = $leaveStartDate->format('d') . '-' . $leaveEndDate->format('d M Y');
                } else {
                    $formattedLeaveDate = $leaveStartDate->format('d M Y') . ' - ' . $leaveEndDate->format('d M Y');
                }
            }
            if($attendanceCount>0){
                $sortedAttendances = $attendance->sortBy('DateTime');
                // Clock In
                $clockInAtt=$sortedAttendances->first();
                $clockIn = $clockInAtt['DateTime'];

                // Clock out
                if($attendanceCount == 1){
                    $clockOut=null;
                }else{
                    $clockOutAtt=$sortedAttendances->last();
                    $clockOut=$clockOutAtt['DateTime'];
                    $isManualClockOut=$clockOutAtt['is_manual'];
                }

                // Late Count
                $clockInTime = strtotime($clockIn);
                $dutySlotStartTime = strtotime("$date $startTime");
                $dutySlotThresholdTime = strtotime("$date $thresholdTime");
                if($clockInTime>$dutySlotThresholdTime) {
                    $lateCount = floor(($clockInTime - $dutySlotStartTime) / 60);
                    $hours = floor($lateCount / 60); // Calculate hours
                    $minutes = $lateCount % 60;
                    if($hours>0) $late = $hours . 'h ' .$minutes.'m';
                    else $late = $minutes.'m';
                }
                else{
                    //$late = "Not Late";
                }
                // Over Time
                $clockOutTime = strtotime($clockOut);
                $dutySlotEndTime = strtotime("$date $endTime");
                if($clockOut){
                    if($clockOutTime>$dutySlotEndTime) {
                        $lateCount = floor(($clockOutTime - $dutySlotEndTime) / 60);
                        $hours = floor($lateCount / 60); // Calculate hours
                        $minutes = $lateCount % 60;
                        if($hours>0) $overtime = $hours . 'h ' .$minutes.'m';
                        else $overtime = $minutes.'m';
                    }
                }

                // Early Leaving
                if(time()>$dutySlotEndTime && $clockOut){
                    if($clockOutTime<$dutySlotEndTime) {
                        $lateCount = floor(($dutySlotEndTime - $clockOutTime) / 60);
                        $hours = floor($lateCount / 60); // Calculate hours
                        $minutes = $lateCount % 60;
                        if($hours>0) $earlyLeaving = $hours . 'h ' .$minutes.'m';
                        else $earlyLeaving = $minutes.'m';
                    }
                }
            }
            $attendanceDetails=$item->attendanceDetails($date);
            $employeeData[] = [
                'id' => $item->id,
                'emp_id' => $item->emp_id ?? '',
                'full_name' => $item->full_name ?? '',
                'profile_img' => $profile_img,
                'profile_img_default' => $defaultProfileImage,
                'email' => $item->email ?? '',
                'phone' => $item->phone ?? '',
                'designation' => $item->empDesignation->name ?? '',
                'department' => $item->empDepartment->name ?? '',
                'company' => $item->empOrganization->name ?? '',
                'date' => $date,
                'biometric_id' => $item->biometric_id ?? '',
                'additionalDetails'=>$attendanceDetails,
                'attendance' => $attendance,
                'dutySlotName' => $item->dutySlot->slot_name ?? '',
                'dutySlotTitle' => $dutySlotTitle?? '',
                'dutySlot' => $dutySlot,
                'dutyStartTime' => date('H:ia', strtotime($startTime)),
                'dutyThresholdTime' => date('H:ia', strtotime($thresholdTime)),
                'dutyEndTime' => date('h:ia', strtotime($endTime)),
                'attendanceCount' => $attendanceCount,
                'clockIn' => $clockIn,
                'clockInDetails'=>$clockInAtt,
                'clockOutDetails'=>$clockOutAtt,
                'clockOut' => $clockOut,
                'late' => $late,
                'earlyLeaving' => $earlyLeaving,
                'overtime' => $overtime,
                'leave' => $formattedLeaveDate,
            ];
        }*/
    }

}
