<?php

namespace App\Http\Controllers;

use App\Http\Requests\MyRequest;
use App\Mail\Mailing;
use App\Models\EmployeeRequest;
use App\Models\ApprovalStatus;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\RequisitionType;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class MyRequestsController extends Controller
{
    public function index()
    {
        $approvalStatus=ApprovalStatus::get();
        $leaveType=LeaveType::get();
        $requisitionType=RequisitionType::get();
        $empId=auth()->user()->emp_id;
        return view('my-request.index', compact('empId','leaveType','requisitionType','approvalStatus'));
    }
    public function store(MyRequest $request)
    {
        //$employeeRequest=EmployeeRequest::find(20);
        //return $this->sendRequestEmail($employeeRequest);
        $a=$request->a;
        $employeeId=getAuthEmpId();
        if($employeeId){
            $employeeRequest=new EmployeeRequest();
            $employeeRequest->request_type=$a;
            switch ($a) {
                case 'late-arrival':
                    $date=$request->date;
                    $time=$request->time;
                    $reason=$request->late_reason;
                    $note=$request->late_note;

                    $employeeRequest->emp_id=$employeeId;
                    $employeeRequest->issue_date=now();
                    $employeeRequest->date=$date;
                    $employeeRequest->time=$time;
                    $employeeRequest->reason=$reason;
                    $employeeRequest->note=$note;
                    if($employeeRequest->save()){
                        $this->sendRequestEmail($employeeRequest, 'line-manager');
                        return back()->with('success', 'Successfully Added!');
                    }
                    break;
                case 'early-exit':
                    $date=$request->early_exit_date;
                    $time=$request->early_exit_time;
                    $reason=$request->early_exit_reason;
                    $note=$request->early_exit_note;

                    $employeeRequest->emp_id=$employeeId;
                    $employeeRequest->issue_date=now();
                    $employeeRequest->date=$date;
                    $employeeRequest->time=$time;
                    $employeeRequest->reason=$reason;
                    $employeeRequest->note=$note;
                    if($employeeRequest->save()){
                        $this->sendRequestEmail($employeeRequest, 'line-manager');
                        return back()->with('success', 'Successfully Added!');
                    }
                    break;
                case 'time-track':
                    $type = $request->type;
                    if ($type === 'start') {
                        $employeeRequest->emp_id = $employeeId;
                        $employeeRequest->issue_date = now();
                        $employeeRequest->start_time = now();
                        if ($employeeRequest->save()) {
                            return response()->json(['status' => 1, 'msg' => 'Time tracking started!']);
                        } else {
                            return response()->json(['status' => 0, 'msg' => 'Failed to start time tracking.']);
                        }
                    } elseif ($type === 'stop') {
                        $duration = $request->duration;
                        $employeeRequest = EmployeeRequest::where('emp_id', $employeeId)
                            ->where('request_type', 'time-track')
                            ->whereNull('end_time')
                            ->orderBy('issue_date', 'desc')
                            ->first();

                        if ($employeeRequest) {
                            $employeeRequest->end_time = now();
                            $employeeRequest->duration = $duration;
                            if ($employeeRequest->save()) {
                                return response()->json(['status' => 1, 'msg' => 'Time tracking stopped!']);
                            } else {
                                return response()->json(['status' => 0, 'msg' => 'Failed to stop time tracking.']);
                            }
                        } else {
                            return response()->json(['status' => 0, 'msg' => 'No running time tracker found.']);
                        }
                    } elseif ($type === 'status') {
                        $employeeRequest = EmployeeRequest::where('emp_id', $employeeId)
                            ->where('request_type', 'time-track')
                            ->orderBy('issue_date', 'desc')
                            ->first();

                        if ($employeeRequest && !$employeeRequest->end_time) {
                            return response()->json(['status' => 1, 'isRunning' => true, 'startTime' => $employeeRequest->start_time]);
                        } else {
                            return response()->json(['status' => 1, 'isRunning' => false]);
                        }
                    }
                    // return response()->json(['status'=>1, 'msg'=>'Added!']);
                    break;
                case 'home-office':
                    $startDate = Carbon::parse($request->start_date);
                    $endDate = Carbon::parse($request->end_date);
                    $daysDifference = $endDate->diffInDays($startDate)+1;  //To Do: Can be used to calculate difference between start and end dates.
                    $reason=$request->home_office_reason;
                    $note=$request->home_office_note;

                    $employeeRequest->emp_id=$employeeId;
                    $employeeRequest->issue_date=now();
                    if($startDate>$endDate){
                        return back()->with('error', 'Start date must be before end date');
                    }else{
                        $employeeRequest->start_date=$startDate;
                        $employeeRequest->end_date=$endDate;
                    }
                    if($request->home_office_time){
                        $employeeRequest->time=$request->home_office_time;
                    }
                    $employeeRequest->reason=$reason;
                    $employeeRequest->note=$note;
                    if($employeeRequest->save()){
                        $this->sendRequestEmail($employeeRequest, 'line-manager');
                        return back()->with('success', 'Successfully Added!');
                    }
                    break;

                default:
                    # code...
                    break;
            }
        }else{
            return back()->with('error', 'Employee Not Found!');
        }
    }
    public function sendRequestEmail($employeeRequest, $sendTo='', $data=['type'=>"employee-request"])
    {
        $isSendMailApplicable=Setting::where('name', 'send-email-when-employee-request')->where('is_applicable', 1)->first();
        if($isSendMailApplicable){
            $employee=$employeeRequest->employee;
            $employeeLineManagerId=$employee->line_manager ?? '';
            $employeeDepartmentHeadId=$employee->department_head ?? '';
            if($employeeLineManagerId==$employeeDepartmentHeadId){

            }else{
                if($sendTo=='line-manager'){
                    $employeeLineManager=$employee->empLineManager->user;
                    if($employeeLineManager){
                        $mailData = clone $employeeRequest;
                        $mailData->approvalPermission=$data['approvalPermission'] ?? 1;
                        $userInfo=[
                            'request_id'=>$employeeRequest->id,
                            'approval_permitted_emp_id'=>$employeeLineManagerId,
                            'approve_as'=>'line-manager',
                            'approval_permission'=>1
                        ];
                        if($data['type']=='leave-request') {
                            $mailData->previewUrl=route('leaveRequest.email-preview', ['id'=>tripleBase64Encode(json_encode($userInfo))]);
                            $email = new Mailing([]);
                            $email->mailData = $mailData;
                            $email->subject = "Leave Request"." of ".$employee->full_name."(".$employee->emp_id.")";
                            $email->type = $data['type'];
                            Mail::to($employeeLineManager->email)->send($email);


                        }
                        if($data['type']=='employee-request') {
                            $mailData->previewUrl = route('employeeRequest.email-preview', ['id' => tripleBase64Encode(json_encode($userInfo))]);
                            $email = new Mailing([]);
                            $email->mailData = $mailData;
                            $email->subject = __($employeeRequest->request_type) . " Request"." of ".$employee->full_name."(".$employee->emp_id.")";
                            $email->type = $data['type'];
                            Mail::to($employeeLineManager->email)->send($email);
                        }

                        //return view('mails.employee-request', compact('mailData'));


                        //return $employeeLineManager->email;
                    }
                }
                if($sendTo=='department-head'){
                    $employeeDepartmentHead=$employee->empDepartmentHead->user;
                    if($employeeDepartmentHead){
                        $mailData=$employeeRequest;
                        $mailData->approvalPermission=1;
                        $userInfo=[
                            'request_id'=>$employeeRequest->id,
                            'approval_permitted_emp_id'=>$employeeDepartmentHeadId,
                            'approve_as'=>'department-head',
                            'approval_permission'=>1
                        ];
                        if($data['type']=='leave-request') {
                            $mailData->previewUrl=route('leaveRequest.email-preview', ['id'=>tripleBase64Encode(json_encode($userInfo))]);
                            $email = new Mailing([]);
                            $email->mailData = $mailData;
                            $email->subject = "Leave Request"." of ".$employee->full_name."(".$employee->emp_id.")";
                            $email->type = $data['type'];
                            Mail::to($employeeDepartmentHead->email)->send($email);
                        }
                        if($data['type']=='employee-request') {
                            $mailData->previewUrl = route('employeeRequest.email-preview', ['id' => tripleBase64Encode(json_encode($userInfo))]);
                            $email = new Mailing([]);
                            $email->mailData = $mailData;
                            $email->subject = __($employeeRequest->request_type) . " Request"." of ".$employee->full_name."(".$employee->emp_id.")";
                            $email->type = $data['type'];
                            Mail::to($employeeDepartmentHead->email)->send($email);
                        }

                        //return view('mails.employee-request', compact('mailData'));


                        //return $employeeLineManager->email;
                    }
                }

            }
            if($sendTo=='leave-reliever'){
                $employeeReliever=$employeeRequest->leaveReliever;
                if($employeeReliever){
                    $relieverEmail=$employeeReliever->email ? $employeeReliever->email : $employeeReliever->personal_email;
                    if($relieverEmail) {
                        $mailData = $employeeRequest;
                        $mailData->approvalPermission = 0;
                        $userInfo = [
                            'request_id' => $employeeRequest->id,
                            'approval_permission' => 0,
                        ];
                        $mailData->previewUrl = route('leaveRequest.email-preview', ['id' => tripleBase64Encode(json_encode($userInfo))]);
                        $email = new Mailing([]);
                        $email->mailData = $mailData;
                        $email->othersData = ['mail_as'=>'reliever'];
                        $email->subject = "Leave Request" . " of " . $employee->full_name . "(" . $employee->emp_id . ")";
                        $email->type = $data['type'];
                        Mail::to($relieverEmail)->send($email);
                    }
                }
            }
        }


        //dd($employeeLineManager);
    }
   /*  public function delete($id)
    {
        $employeeRequest=EmployeeRequest::find($id);
        if($employeeRequest){
            if($employeeRequest->approval_status=='' && $employeeRequest->approval_by=='' && $employeeRequest->rejected_by==''){
                $employeeRequest->delete();
                return back()->with('success', 'Successfully deleted!');
            }else return back()->with('error', 'Can not be deleted!');
        }else return back()->with('error', 'Not Found!');
    } */
    public function delete($id)
    {
        $employeeRequest=EmployeeRequest::find($id);
        $leaveRequest=Leave::find($id);
        $userEmpId=getUserEmpId();
        if($employeeRequest){
            if($employeeRequest->approval_status==1  && $employeeRequest->approval_by=='' && $employeeRequest->rejected_by=='' ){
                $employeeRequest->delete();
                return back()->with('success', 'Successfully deleted!');
            }else return back()->with('error', 'Can not be deleted!');
        }else if($leaveRequest){
            if($userEmpId && $userEmpId==$leaveRequest->emp_id && $leaveRequest->approval_status==1 && (!$leaveRequest->line_manager_approved_by || !$leaveRequest->department_head_approved_by) || getUserRole()==='super-admin'){
                $leaveRequest->delete();
                return back()->with('success', 'Successfully deleted!');
            }else return back()->with('error', 'Can not be deleted!');
        }else return back()->with('error', 'Not Found!');
    }
}
