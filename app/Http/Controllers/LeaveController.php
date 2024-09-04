<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailPreviewRequest;
use App\Http\Requests\LeaveRequestRequest;
use App\Http\Requests\LeaveTypeRequest;
use App\Mail\Mailing;
use App\Models\DutySlot;
use App\Models\DutySlotRule;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveDocument;
use App\Models\ApprovalStatus;
use App\Models\LeaveType;
use App\Models\Setting;
use App\Models\RequisitionType;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class LeaveController extends Controller
{

    public function index(Request $request)
    {

        $approvalStatus=ApprovalStatus::get();
        $leaveType=LeaveType::get();
        $requisitionType=RequisitionType::get();
        if($request->ajax()) {
            $a = $request->a;
            $q = $request->q;
            $searchKey = $request->search['value'] ?? '';
            $filter_leave_type = $request->filter_leave_type;
            $filter_approval_status = $request->filter_approval_status;
            $date = $request->date;
            $limit = $request->length;
            if ($limit < 0) $limit = 10;
            $offset = $request->start;
            // Orders
            $orderByColumn = null;
            $orderByDirection = null;

            // Check if the order array exists in the request and has at least one item
            if (isset($request->order) && count($request->order) > 0) {
                $firstOrderItem = $request->order[0];
                $orderByColumn = $firstOrderItem['name'] ?? null;
                $orderByDirection = $firstOrderItem['dir'] ?? null;
            }
            $query = Leave::query();
            if ($q == 'request-approvals') {
                // $query->where('approval_status', 1);
                $query->whereIn('emp_id', getManageableEmployeesIDs());
            }
            if (getUserRole() == 'employee') {
                $query->where('emp_id', Auth::user()->emp_id);
            }elseif($a=='my-leave-list'){
                $query->where('emp_id', Auth::user()->emp_id);
            }/* elseif (getUserRole() == 'department-head') {
                $supervisedTeamMembers = (new UserController())->supervisedTeamMembers(auth()->user()->id);
                $query->whereIn('emp_id', $supervisedTeamMembers);
            }*/


            $recordsTotal = $query->count();
            if ($searchKey) {
                $query->where(function ($query) use ($searchKey) {
                    $query->orWhere('emp_id', 'like', "%{$searchKey}%");
                    $query->orWhereIn('emp_id', function ($subquery) use($searchKey){
                        $subquery->select('emp_id')->from(with(new Employee())->getTable())
                            ->where('full_name', 'like', "%$searchKey%");
                        $subquery->orWhere('email', 'like', "%$searchKey%");
                        $subquery->orWhere('phone', 'like', "%$searchKey%");
                        $subquery->orWhere('alternative_phone', 'like', "%$searchKey%");
                    });
                    $query->orWhereIn('leave_type', function ($subquery) use($searchKey){
                        $subquery->select('id')->from(with(new LeaveType())->getTable())
                            ->where('name', 'like', "%$searchKey%");
                    });
                    $query->orWhereIn('approval_status', function ($subquery) use($searchKey){
                        $subquery->select('id')->from(with(new ApprovalStatus())->getTable())
                            ->where('name', 'like', "%$searchKey%");
                    });
                });
            }
            if($filter_leave_type){
                $query->where('leave_type', $filter_leave_type);
            }
            if($filter_approval_status){
                $query->where('approval_status', $filter_approval_status);
            }
            if($date){
                $query->where(function ($subquery) use ($date) {
                    $subquery->where(function ($query) use ($date) {
                        $query->where('start_date', '<=', $date)
                            ->where('end_date', '>=', $date);
                    })->orWhere('issue_date', $date);
                });
            }


            // ORDERS

            switch ($orderByColumn) {
                case 'emp_id':
                    $query->orderBy(Employee::select('full_name')->whereColumn('id', 'leaves.emp_id'), $orderByDirection);
                    break;
                case 'leave_type':
                    $query->orderBy(LeaveType::select('id')->whereColumn('id', 'leaves.leave_type'), $orderByDirection);
                    break;
                case 'leave_reason':
                    $query->orderBy('leave_reason', $orderByDirection);
                    break;
                case 'issue_date':
                    $query->orderBy('issue_date', $orderByDirection);
                    break;
                case 'start_date':
                    $query->orderBy('created_at', $orderByDirection);
                    break;
                case 'approval_status':
                    $query->orderBy('approval_status', $orderByDirection);
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $recordsFiltered = $query->get();  // Filtered Data to count
            $query->limit($limit)->offset($offset);

            $queryData = $query->get();

            $queryDataJson = [];
            foreach ($queryData as $item) {
                $defaultProfileImage= employeeDefaultProfileImage();
                $profile_img=null;
                $email='';
                $phone='';
                $organization='';
                $employee=$item->employee;
                if($employee){
                    $defaultProfileImage= employeeDefaultProfileImage($employee->gender);
                    $profile_img=employeeProfileImage($employee->emp_id, $employee->profile_photo);
                    $email=$employee->email ? $employee->email : ($employee->personal_email);
                    $phone=$employee->phone ?? '';
                    $organization=$employee->empOrganization->name ?? '';
                }
                $startDate = Carbon::parse($item->start_date);
                $endDate = Carbon::parse($item->end_date);
                $leaveDate='';
                if ($startDate->isSameDay($endDate)) {
                    $leaveDate = formatCarbonDate($item->start_date);
                } elseif ($startDate->isSameMonth($endDate)) {
                    $leaveDate = formatCarbonDate($item->start_date) . '-' . formatCarbonDate($item->end_date);
                } else {
                    $leaveDate = formatCarbonDate($item->start_date) . '-' . formatCarbonDate($item->end_date);
                }
                $editPermission= false;
                $userEmpId=getUserEmpId();
                $deletePermission= false;
                /* if($userEmpId && $userEmpId==$item->emp_id && $item->approval_status==1 && (!$item->line_manager_approved_by || !$item->department_head_approved_by)){
                    $editPermission=true;
                    $deletePermission= true;
                }else if(getUserRole()==='super-admin'){
                    $deletePermission= true;
                } */

                $isPending=$item->approval_status==1;
                $lineManagerApproval=$item->lineManagerApproved;
                $departmentHeadApproval=$item->departmentHeadApproved;
                if($userEmpId && $userEmpId==$item->emp_id && $item->approval_status==1 && (!$lineManagerApproval || !$departmentHeadApproval)){
                    $editPermission=true;
                    $deletePermission= true;
                }else if(getUserRole()==='super-admin'){
                    $deletePermission= true;
                }

                /*if($employee){
                    $employeeLineManager=$employee->line_manager ?? '';
                    $employeeDepartmentHead=$employee->department_head ?? '';
                    $authEmployeeId=auth()->user()->emp_id;
                    if($authEmployeeId==$employeeLineManager && $lineManagerApproval){
                        $editPermission=0;
                    }
                    if($authEmployeeId==$employeeDepartmentHead && $departmentHeadApproval){
                        $editPermission=0;
                    }
                }*/


                $queryDataJson[] = [
                    'id' => $item->id,
                    'emp_id' => $item->emp_id ?? '',
                    'name' => $employee->full_name ?? '',
                    'email' => $email,
                    'phone' => $phone,
                    'organization' => getFirstWord($organization),
                    'profile_img' => $profile_img ?? $defaultProfileImage,
                    'profile_img_default' => $defaultProfileImage,
                    'leave_type' => $item->leaveType->name ?? '',
                    'leave_reason' => $item->leave_reason ?? '',
                    'issue_date' => formatCarbonDate($item->issue_date),
                    'start_date' => formatCarbonDate($item->start_date),
                    'leave_date' => $leaveDate ?? '',
                    'leave_days' => $item->intended_leave_days,
                    'status' => $item->approvalStatus->name ?? '',
                    'approvalInfo'=>(new RequestApprovalsController())->getApprovalInfo($item),
                    'approvalPermission' => (new RequestApprovalsController())->getApprovalPermission($item),
                    'editPermission' => $editPermission,
                    'deletePermission' => $deletePermission,
                ];
            }
            $request = [
                'status' => 1,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered->count(),
                'data' => $queryDataJson,
                'selectedDate' => $date ? date('d M Y', strtotime($date)) : '',
                'orderableColumns' => $orderByColumn . $orderByDirection
            ];
            return response()->json($request);
        }
        if(!userCan('leave.view')) return back()->with('error', 'Unauthorized Access!');
        return view("leave.index", compact('leaveType','requisitionType','approvalStatus'));
    }
    public function store(LeaveRequestRequest $request){
        //return $request;
        if(!userCan('leave.create')) return back()->with('error', 'Unauthorized Access!');
        $employeeId=getAuthEmpId();
        if($employeeId){
            $leave=new Leave();
            $employee=Employee::where('emp_id', $employeeId)->first();
            $leave->emp_id=$employeeId;
            $leave->reliever_emp_id=$request->reliever_emp_id;
            $leave->approval_status = 1;


            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $daysDifference = $endDate->diffInDays($startDate)+1;

            $leaveType=$request->leave_type;
            $leaveBalances=$employee->leaveBalance(date('Y'));
            if($leaveBalances){
                $leaveTypeBalance=collect($leaveBalances)->firstWhere('id', $leaveType);
                if($leaveTypeBalance){
                    $remaining_days=$leaveTypeBalance['remaining'];
                    $errorMsg='Exceed the '.$leaveTypeBalance['name'].' leave balance!';
                    if($remaining_days<=0){
                        return back()->with('error', $errorMsg);
                    }else{
                        if($daysDifference>$remaining_days) return back()->with('error', $errorMsg);
                    }
                }
            }else{
                return back()->with('error', 'Leave Balance Not Found!');
            }

            if($leave->reliever_emp_id==$employeeId) return back()->with('error', 'Invalid Reliever!');

            $leave->reliever_emp_id=$request->reliever_emp_id ?? '';

            $leave->leave_type=$request->leave_type;
            $leave->requisition_type=$request->requisition_type;
            $leave->issue_date=date('Y-m-d');
            $leave->start_date=$request->start_date;
            $leave->end_date=$request->end_date;
            $leave->leave_reason=$request->reason;
            if($request->remarks) $leave->remarks = $request->remarks;

            $leave->intended_leave_days = $daysDifference;
            $leave->created_by = auth()->user()->id;
            if($leave->save()) {
                $leaveId = $leave->id;
                if ($request->has('attachments')) {
                    $attachments = $request->file('attachments');
                    if ($attachments) {
                        $fileInfo = [];
                        foreach ($attachments as $key => $item) {
                            $fileName = $item->getClientOriginalName();
                            $fileUrl = "uploads/employees/{$employeeId}/leave_documents";
                            $fileInfo[] = [
                                'leave_id' => $leaveId,
                                'file_name' => $fileName,
                                'url' => "$fileUrl/$fileName",
                            ];
                            $item->move(public_path($fileUrl), $fileName);
                        }
                        LeaveDocument::insert($fileInfo);
                    }
                }

                $isSendMailApplicable=Setting::where('name', 'send-email-when-leave-request')->where('is_applicable', 1)->count();
                if($isSendMailApplicable>0){
                        $employeeLineManager=$employee->empLineManager;
                        if($employeeLineManager){
                            (new MyRequestsController())->sendRequestEmail($leave, 'line-manager', ['type'=>'leave-request']);
                        }else{
                            $employeeDepartmentHead=$employee->empDepartmentHead;
                            if($employeeDepartmentHead){
                                (new MyRequestsController())->sendRequestEmail($leave, 'department_head', ['type'=>'leave-request']);
                            }
                        }
                    if($leave->reliever_emp_id){
                        $employeeLeaveReliever=$leave->leaveReliever;
                        if($employeeLeaveReliever){
                            $employeeLeaveRelieverEmail=$employeeLeaveReliever->email??$employeeLeaveReliever->personal_email;
                            if($employeeLeaveRelieverEmail) {
                                (new MyRequestsController())->sendRequestEmail($leave, 'leave-reliever', ['type' => 'leave-request']);
                            }
                        }
                    }
                }
                return back()->with('success', "Successfully Added");


            }

        }
        return back()->with('error', "Server Error!");
        /*$leave=new Leave();
        $employee=[];
        if(in_array(getUserRole(), ['employee', 'team-lead', 'department-head'])) {
            $emp_id=Auth::user()->emp_id;
            $employee=Employee::where('emp_id', $emp_id)->first();
            if($employee){
                $emp_str_id=$employee->emp_id;
                $leave->emp_id=$emp_id;
                $leave->reliever_emp_id=$request->reliever_emp_id;
            }else{
                return back()->with('error', "Missing Employee");
            }
        }else{
            if($request->emp_id) {
                $emp_id=$request->emp_id;
                $employee=Employee::where('emp_id', $emp_id)->first();
                $emp_str_id=$employee->emp_id;
                $leave->emp_id = $emp_id;
            }
            else return back()->with('error', "Missing Employee");
        }
        if($request->approval_status==2){
            if(in_array(getUserRole(), ['super-admin', 'hr'])){
                $leave->approval_status = $request->approval_status;
                if($request->remarks) $leave->remarks = $request->remarks;
            }else{
                $leave->approval_status = 1;
            }
        }else{
            $leave->approval_status = 1;
        }
        //return $request->start_date;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $daysDifference = $endDate->diffInDays($startDate)+1;

        $leaveType=$request->leave_type;
        $leaveBalances=$employee->leaveBalance(date('Y'));
        if($leaveBalances){
            $leaveTypeBalance=collect($leaveBalances)->firstWhere('id', $leaveType);
            if($leaveTypeBalance){
                $remaining_days=$leaveTypeBalance['remaining'];
                $errorMsg='Exceed the '.$leaveTypeBalance['name'].' leave balance!';
                if($remaining_days<=0){
                    return back()->with('error', $errorMsg);
                }else{
                    if($daysDifference>$remaining_days) return back()->with('error', $errorMsg);
                }
            }
        }else{
            return back()->with('error', 'Leave Balance Not Found!');
        }

        $leave->reliever_emp_id=$request->reliever_emp_id ?? '';
        $leave->leave_type=$request->leave_type;
        $leave->requisition_type=$request->requisition_type;
        $leave->issue_date=date('Y-m-d');
        $leave->start_date=$request->start_date;
        $leave->end_date=$request->end_date;
        $leave->leave_reason=$request->reason;
        if($request->remarks) $leave->remarks = $request->remarks;

        $leave->intended_leave_days = $daysDifference;
        $leave->created_by = auth()->user()->id;
        if($leave->save()){
            $leaveId=$leave->id;
            if($request->has('attachments')){
                $attachments=$request->file('attachments');
                if ($attachments) {
                    $fileInfo = [];
                    foreach ($attachments as $key => $item) {
                        $fileName=$item->getClientOriginalName();
                        $fileUrl="uploads/employees/{$emp_str_id}/leave_documents";
                        $fileInfo[] = [
                            'leave_id' => $leaveId,
                            'file_name' => $fileName,
                            'url' => "$fileUrl/$fileName",
                        ];
                        $item->move(public_path($fileUrl), $fileName);
                    }
                    LeaveDocument::insert($fileInfo);
                }
            }
            $isSendMailApplicable=Setting::where('name', 'send-email-when-leave-request')->where('is_applicable', 1)->count();
            if($isSendMailApplicable>0){
                $ccEmail=[];
                $mailData= $leave;

                $
                if($leave->)


                if($employee->supervisors){
                    foreach ($employee->supervisors as $supervisor){
                        $departmentHeadEmail= $departmentHead->employee->email;
                        $mailData->approvalPermission=1;
                        $userInfo=['leave_id'=>$leave->id,
                            'emp_id'=>$departmentHead->employee->emp_id,
                            'approval-permission'=>1
                        ];
                        $mailData->previewUrl=route('leaveRequest.email-preview', ['id'=>tripleBase64Encode(json_encode($userInfo))]);
                        $email = new Mailing([]);
                        $email->mailData= $mailData;
                        $email->subject= "Leave Request";
                        $email->type="leave-request";
                        Mail::to($departmentHeadEmail)->send($email);
                    }
                }

                $hrEmail = Setting::where('name', 'hr-email')->where('is_applicable', 1)->value('descr');
                if($hrEmail){
                    $mailData->approvalPermission=1;
                    $userInfo=['leave_id'=>$leave->id,
                        'emp_id'=>$departmentHead->employee->emp_id,
                        'approval-permission'=>1
                    ];
                    $mailData->previewUrl=route('leaveRequest.email-preview', ['id'=>tripleBase64Encode(json_encode($userInfo))]);
                    $email = new Mailing([]);
                    $email->mailData= $mailData;
                    $email->subject= "Leave Request";
                    $email->type="leave-request";
                    Mail::to($hrEmail)->send($email);
                }
                if($leave->reliever_emp_id!=''){
                    $reliever=Employee::where('emp_id', $leave->reliever_emp_id)->first();
                    if($reliever) {
                        $relieverMail=$reliever->email !='' ? $reliever->email : $reliever->personal_email;
                        if($relieverMail!='') {
                            $mailData->approvalPermission = 0;
                            $userInfo = ['leave_id' => $leave->id,
                                'emp_id' => $leave->reliever_emp_id,
                                'approval-permission' => 0
                            ];
                            $mailData->previewUrl = route('leaveRequest.email-preview', ['id' => tripleBase64Encode(json_encode($userInfo))]);
                            $email = new Mailing([]);
                            $email->mailData = $mailData;
                            $email->subject = "Leave Request";
                            $email->type = "leave-request";
                            Mail::to($relieverMail)->send($email);
                        }
                    }
                }
            }
            return back()->with('success', "Successfully Added");
        }
        return back()->with('error', "Server Error!");*/

    }
    public function view(Request $request, $id)
    {
        if($request->ajax()){
            if(!userCan('leave.view')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
            $leave=Leave::find($id);
            // return $leave;
            if($leave){
                $approvalStatus=ApprovalStatus::get();
                $leaveType=LeaveType::get();
                $requisitionType=RequisitionType::get();
                $reliever_emp_id=$leave->reliever_emp_id;
                $reliever=Employee::where('emp_id', $reliever_emp_id)->first();
                // return $reliever;
                $html=View::make('leave.view', compact('leave', 'leaveType','requisitionType','reliever','reliever_emp_id','approvalStatus'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                ];
                return response()->json($response);
            }
            return response()->json(['status'=>0,'html'=>'Not Found!']);

        }

        if(!userCan('leave.view')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
        $leave=Leave::find($id);
        /* $manageableEmployees=auth()->user()->manageableEmployees;
        $manageableEmployeesIDs = $manageableEmployees->pluck('emp_id')->toArray();
        if(!in_array($leave->emp_id, $manageableEmployeesIDs)){
            if(!userCan('leave.view')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
        } */
        if($leave){
            $request->printView;
            if($request->printView!=1){
                if(!in_array(getUserRole(), ['super-admin', 'hr', 'department-head'])){
                    if($leave->emp_id!=auth()->user()->employee->id){
                        return view('error-page.unauthorized');
                    }
                }
            }
            $approvalStatus=ApprovalStatus::get();
            $leaveType=LeaveType::get();
            $requisitionType=RequisitionType::get();
            $reliever_emp_id=$leave->reliever_emp_id;
            $reliever=Employee::where('emp_id', $reliever_emp_id)->first();
            return view('leave.single-page-view', compact('leave', 'leaveType','reliever','requisitionType','reliever_emp_id','approvalStatus'));
        }else{
            return view('error-page.not-found');
        }
    }
    public function emailPreview(Request $request, $id)
    {
        $reqInfo = json_decode(tripleBase64Decode($id));
        if(!isset($reqInfo->request_id)) return 0;
        $id=$reqInfo->request_id;
        $leave=Leave::find($id);
        if($leave){
            if($leave->created_at->addHours(1)<now()) return view('error-page.link-expired-public-page');
            $reliever_emp_id=$leave->reliever_emp_id;
            $reliever=Employee::where('emp_id', $reliever_emp_id)->first();
            return view('leave.mail.email-preview', compact('id','leave', 'reliever','reliever_emp_id', 'reqInfo'));
        }else{

        }
    }
    public function emailPreviewSubmit(EmailPreviewRequest $request, $id)
    {
        $reqInfo = json_decode(tripleBase64Decode($id));
        if(!isset($reqInfo->request_id)) return view('error-page.forbidden');
        $id=$reqInfo->request_id;
        $employeeRequest=Leave::find($id);
        if($employeeRequest){
            if($employeeRequest->approval_status!=1) return back()->with('error', "Already Changed!");
            if($reqInfo->approve_as=='line-manager' && $employeeRequest->created_at->addHours(1)<now()){
                return view('error-page.link-expired-public-page');
            }

            if($reqInfo->approve_as=='department-head' && Carbon::parse($employeeRequest->line_manager_approved_at)->addHours(1)<now()){
                return view('error-page.link-expired-public-page');
            }
            if(isset($reqInfo->approval_permitted_emp_id) && $reqInfo->approval_permitted_emp_id!=''){
                $action=$request->action;
                if($action=='Approve') {
                    $authEmployeeId=$reqInfo->approval_permitted_emp_id;
                    $employeeLineManager=$reqInfo->approve_as=='line-manager'?$authEmployeeId:'';
                    $employeeDepartmentHead=$reqInfo->approve_as=='department-head'?$authEmployeeId:'';
                    $note=$request->remarks;
                    (new RequestApprovalsController())->approvalProcess($employeeRequest, $authEmployeeId, $employeeLineManager, $employeeDepartmentHead, $note, $data=['type'=>'leave-request']);
                }elseif($action=='Reject') {
                    $employeeRequest->approval_status = 3;
                    $employeeRequest->rejected_by = $reqInfo->approval_permitted_emp_id;
                    $employeeRequest->rejected_at = now();
                    $employeeRequest->rejected_note = $request->remarks;
                }
                if($employeeRequest->save()){
                    return back()->with('success', "Successfully {$action}ed");
                }else{
                    return back()->with('error', "Server Error");
                }
            }else{
                return back()->with('error', "No permission!");
            }
        }else{

        }






        /*$reqInfo = json_decode(tripleBase64Decode($id));
        if(!isset($reqInfo->leave_id)) return 0;
        $id=$reqInfo->leave_id;
        $leave=Leave::find($id);
        if($leave){
            if($leave->approval_status!=1) return back()->with('error', "Already Changed!");
            if($leave->created_at->addHours(1)<now()) return view('error-page.link-expired-public-page');
            if(isset($reqInfo->emp_id) && $reqInfo->emp_id!=''){
                $action=$request->action;
                if($action=='Approve') {
                    $leave->approval_status = 2;
                    $leave->approved_by = $reqInfo->emp_id;
                    $leave->approved_at = now();
                }elseif($action=='Reject') {
                    $leave->approval_status = 3;
                    $leave->rejected_by = $reqInfo->emp_id;
                    $leave->rejected_at = now();
                }
                $leave->remarks = $request->remarks;
                if($leave->save()){
                    return back()->with('success', "Successfully {$action}ed");
                }else{
                    return back()->with('error', "Server Error");
                }
            }else{
                return back()->with('error', "No permission!");
            }
        }else{

        }*/
    }

    public function edit(Request $request, $id)
    {
        if($request->ajax()){

            $leave=Leave::find($id);
            //   return $leave;
            if($leave){
                $manageableEmployees=auth()->user()->manageableEmployees;
                $manageableEmployeesIDs = $manageableEmployees->pluck('emp_id')->toArray();
                if(!in_array($leave->emp_id, $manageableEmployeesIDs)){
                    if(!userCan('leave.edit')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
                }

                $approvalStatus=ApprovalStatus::get();
                $leaveType=LeaveType::get();
                $requisitionType=RequisitionType::get();
                $html=View::make('leave.edit-leave-request', compact('leave', 'leaveType','requisitionType','approvalStatus'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                    'id'=>$id,
                    'reliever_emp_id'=>$leave->reliever_emp_id,
                ];
                return response()->json($response);
            }
            return response()->json(['status'=>0,'html'=>'Not Found!']);

        }
        if(!userCan('leave.edit')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
        $leave=Leave::find($id);
        if($leave){
            if(!in_array(getUserRole(), ['super-admin', 'hr', 'department-head'])){
                if($leave->emp_id!=auth()->user()->employee->id){
                    return view('error-page.unauthorized');
                }
            }
            $approvalStatus=ApprovalStatus::get();
            $leaveType=LeaveType::get();
            $requisitionType=RequisitionType::get();
            return view('leave.supervised.edit-leave-request', compact('leave', 'leaveType', 'requisitionType','approvalStatus'));
        }else{

        }
    }
    public function update(Request $request, $id){
        // return $request;

        $leave=Leave::find($id);
        if(getUserRole()=='employee'){
            if(Auth::user()->emp_id != $leave->emp_id){
                return back()->with('error', "Access Denied");
            }
            if(in_array($leave->approval_status, ['Approved', 'Rejected'])){
                return back()->with('error', "Cant be modified!");
            }
        }
        if($leave) {
            $manageableEmployees=auth()->user()->manageableEmployees;
            $manageableEmployeesIDs = $manageableEmployees->pluck('emp_id')->toArray();
            if(!in_array($leave->emp_id, $manageableEmployeesIDs)){
                if(!userCan('leave.edit')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
            }

            $emp_str_id=$leave->employee->emp_id;
            if($request->reliever_emp_id) $leave->reliever_emp_id = $request->reliever_emp_id;
            if($request->leave_type) $leave->leave_type = $request->leave_type;
            if($request->requisition_type) $leave->requisition_type=$request->requisition_type;
            if($request->start_date) $leave->start_date = $request->start_date;
            if($request->end_date) $leave->end_date = $request->end_date;
            $start_date = new DateTime($leave->start_date);
            $end_date = new DateTime($leave->end_date);
            $daysDiff = $end_date->diff($start_date)->days + 1;
            $leave->intended_leave_days = $daysDiff;
            if($request->reason) $leave->leave_reason = $request->reason;
            if($request->remarks) $leave->remarks = $request->remarks;
            $authEmployeeId=getAuthEmpId();
            $leave->updated_by = auth()->user()->id;



            if(in_array($request->approval_status, [2,3])){

                $employee=$leave->employee;
                $employeeLineManager=$employee->line_manager ?? '';
                $employeeDepartmentHead=$employee->department_head ?? '';
                $authEmployeeId=auth()->user()->emp_id;

                if($request->approval_status==3){
                    if($leave->approval_status==3){
                        $leave->rejected_by=auth()->user()->emp_id;
                        $leave->rejected_at=now();
                    }
                }
                if($request->approval_status==2){

                    $employeeLineManager=$employee->line_manager;
                    $employeeDepartmentHead=$employee->department_head;
                    $note=$request->remarks;
                    (new RequestApprovalsController())->approvalProcess($leave, $authEmployeeId, $employeeLineManager, $employeeDepartmentHead, $note, $data=['type'=>'leave-request']);

                    /*if($authEmployeeId==$employeeLineManager && $authEmployeeId==$employeeDepartmentHead){
                        $leave->approved_by=$authEmployeeId;
                        $leave->approved_at=now();
                        $leave->approval_status = 2;
                    }else{
                        if($authEmployeeId==$employeeLineManager){
                            $leave->line_manager_approved_by=$authEmployeeId;
                            $leave->line_manager_approved_at=now();
                            $leave->line_manager_approval_note=$request->remarks;
                            if($leave->department_head_approved_by!=''){
                                $leave->approval_status = 2;
                                $leave->approved_by=$leave->department_head_approved_by;
                                $leave->approved_at=now();
                            }
                        }
                        if($authEmployeeId==$employeeDepartmentHead){
                            $leave->department_head_approved_by=$authEmployeeId;
                            $leave->department_head_approved_at=now();
                            $leave->department_head_approval_note=$request->remarks;
                            if($leave->line_manager_approved_by!=''){
                                $leave->approval_status = 2;
                                $leave->approved_by=$leave->department_head_approved_by;
                                $leave->approved_at=now();
                            }
                        }
                    }*/
                }

                /*if(in_array(getUserRole(), ['super-admin', 'hr', 'department-head'])){
                    $leave->approval_status = $request->approval_status;
                    if($request->is_objection) $leave->is_objection = $request->is_objection === 'on' ? 1 : 0;
                    if(getUserRole()=='department-head'){
                        if($leave->approval_status==2){
                            $leave->approved_by=auth()->user()->emp_id;
                            $leave->approved_at=now();
                        }

                    }
                }else{
                    $leave->approval_status = 1;
                }*/
            }else{
                $leave->approval_status = 1;
            }
            if ($leave->save()) {
                $prevFiles=$request->prev_file;
                if($prevFiles){
                    LeaveDocument::whereNotIn('id', $prevFiles)->where('leave_id', $id)->delete();
                }else{
                    LeaveDocument::where('leave_id', $id)->delete();
                }
                if($request->has('attachments')){
                    $attachments=$request->file('attachments');
                    if ($attachments) {
                        $fileInfo = [];
                        foreach ($attachments as $key => $item) {
                            $fileName=$item->getClientOriginalName();
                            $fileUrl="uploads/employees/{$emp_str_id}/leave_documents";
                            $fileInfo[] = [
                                'leave_id' => $id,
                                'file_name' => $fileName,
                                'url' => "$fileUrl/$fileName",
                            ];
                            $item->move(public_path($fileUrl), $fileName);
                        }
                        LeaveDocument::insert($fileInfo);
                    }
                }

                return back()->with('success', "Successfully Updated");
            }
            return back()->with('error', "Server Error!");
        }
        return back()->with('error', "Not Found!");

    }


    public function leaveType(Request $request)
    {
        if($request->ajax()){
            $a=$request->a;
            $id=$request->id;
            if($a=='edit'){
                $leaveType=LeaveType::find($id);
                if($leaveType){
                    $html=View::make('leave.leave-type.edit', compact('leaveType'))->render();
                    return response()->json(['status' => 1,'html'=>$html]);
                }else{
                    return response()->json(['status' => 0,'msg'=>'Not Found']);
                }
            }
        }
        $leaveTypes=LeaveType::get();
        return view('leave.leave-type.index', compact('leaveTypes'));
    }
    public function leaveTypeUpdate(LeaveTypeRequest $request, $id)
    {
        $leaveType=LeaveType::find($id);
        $leaveType->days=$request->leave_type_days;
        $leaveType->remarks=$request->leave_type_remarks;
        if($leaveType->save()) return back()->with('success', "Successfully Updated");
        else return back()->with('error', "Server Error");
    }
    public function delete($id)
    {
        $leaveRequest=Leave::find($id);
        if( $leaveRequest){
            $userEmpId=getUserEmpId();
            if(($userEmpId && $userEmpId==$leaveRequest->emp_id && $leaveRequest->approval_status==1 && (!$leaveRequest->line_manager_approved_by || !$leaveRequest->department_head_approved_by) || getUserRole()==='super-admin') ){
                $leaveRequest->delete();
                return back()->with('success', 'Successfully deleted!');
            }else return back()->with('error', 'Can not be deleted!');
        }else return back()->with('error', 'Not Found!');
    }



}
