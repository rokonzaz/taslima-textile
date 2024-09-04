<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailPreviewRequest;
use App\Models\ApprovalStatus;
use App\Models\Employee;
use App\Models\EmployeeRequest;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class RequestApprovalsController extends Controller
{
    public function index(Request $request)
    {

        if($request->ajax()){
            $reqType=$request->requestType;
            $filter_approval_status=$request->status;
            $searchKey = $request->search['value'] ?? '';
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
            $query = EmployeeRequest::query();

            if($request->a=='my-request'){
                $query->where('emp_id', auth()->user()->emp_id);
            }else{
                if(isRoleIn(['super-admin', 'hr'])){

                }else{
                    $query->whereIn('emp_id', getManageableEmployeesIDs());
                }
            }


            if($reqType=='late-arrival'){
                $query->onlyLateArrivalRequest();
            }
            if($reqType=='early-exit'){
                $query->onlyEarlyExitRequest();
            }
            if($reqType=='home-office'){
                $query->onlyHomeOfficeRequest();
            }
            //$query->onlyPending();


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
                });
            }

            if($filter_approval_status){
                $query->where('approval_status', $filter_approval_status);
            }
            /*if($date){
                $query->where(function ($subquery) use ($date) {
                    $subquery->where(function ($query) use ($date) {
                        $query->where('start_date', '<=', $date)
                            ->where('end_date', '>=', $date);
                    })->orWhere('issue_date', $date);
                });
            }*/


            // ORDERS

            switch ($orderByColumn) {
                case 'name':
                    $query->orderBy(Employee::select('full_name')->whereColumn('id', 'employee_requests.emp_id'), $orderByDirection);
                    break;
                case 'dateTime':
                    $query->orderBy('date', $orderByDirection);
                    $query->orderBy('time', $orderByDirection);
                    break;
                case 'reason':
                    $query->orderBy('reason', $orderByDirection);
                    break;
                case 'status':
                    $query->orderBy('approval_status', $orderByDirection);
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $recordsFiltered = $query->get();  // Filtered Data to count
            $query->limit($limit)->offset($offset);

            $queryData = $query->get();

            $deletePermission=getUserRole()=='super-admin';
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

                $startEndDate=formatCarbonDate($item->start_date);
                $totalDay=dateToDateCount($item->start_date, $item->end_date);
                if($totalDay>1){
                    $startEndDate=formatCarbonDate($item->start_date).'-'.formatCarbonDate($item->end_date);
                }

                $queryDataJson[] = [
                    'id' => $item->id,
                    'emp_id' => $item->emp_id ?? '',
                    'name' => $employee->full_name ?? '',
                    'email' => $email,
                    'phone' => $phone,
                    'organization' => getFirstWord($organization),
                    'profile_img' => $profile_img ?? $defaultProfileImage,
                    'profile_img_default' => $defaultProfileImage,
                    'date'=>formatCarbonDate($item->date),
                    'start_end_date'=>$startEndDate,
                    'total_day'=>$totalDay,
                    'time'=>formatCarbonDate($item->time, 'time'),
                    'reason'=>$item->reason,
                    'remarks'=>$item->remarks,
                    'note'=>$item->note,
                    'roleAs'=>$this->getRoleAs($item),
                    'status' => $item->approvalStatus->name ?? 'Pending',
                    'approvalInfo'=>$this->getApprovalInfo($item),
                    'approvalPermission' => $this->getApprovalPermission($item),
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



        /*$employeeRequests=EmployeeRequest::onlyPending();
        if(isRoleIn(['super-admin', 'hr'])){
            return $employeeRequests->count();
        }else{
            $manageableEmployeesIDs=getManagableEployeeIDs();
            $employeeRequests->whereIn('emp_id', $manageableEmployeesIDs);
            return $employeeRequests->count();
        }
        $requestType=['late-arrival', 'early-exit', 'home-office'];
        return $requestsCount=[
            'late-arrival'=>$employeeRequests->lateArrivalRequest()->count(),
            'early-exit'=>$employeeRequests->earlyExitRequest()->count(),
            'home-office'=>$employeeRequests->homeOfficeRequest()->count(),
        ];*/


        $manageableEmployees=auth()->user()->manageableEmployees;
        $manageableEmployeesIDs = $manageableEmployees->pluck('emp_id');
        $approvalStatus=ApprovalStatus::get();
        return view('request-approvals.index', compact('approvalStatus', 'manageableEmployees', 'manageableEmployeesIDs'));
    }
    public function requestApproval(Request $request, $id)
    {
        if ($request->ajax()){
            $employeeRequest=EmployeeRequest::find($id);
            $approvalType=$request->approvalType;
            if($employeeRequest){
                $html=View::make('request-approvals.approval', compact('employeeRequest', 'approvalType'))->render();
                return response()->json(['status'=>1, 'html'=>$html, 'approvalType'=>$request->approvalType]);
            }
        }
        abort(403);
    }
    public function submitApproval(EmailPreviewRequest $request, $id)
    {
        $employeeRequest=EmployeeRequest::find($id);
        if($employeeRequest){
            $employee=$employeeRequest->employee;
            $employeeLineManager=$employee->line_manager ?? '';
            $employeeDepartmentHead=$employee->department_head ?? '';
            $authEmployeeId=getAuthEmpId();

            if($request->reject==3){
                if($employeeRequest->approval_status=='Rejected') return back()->with('error', 'Already Rejected!');
                $employeeRequest->approval_status=3;
                $employeeRequest->rejected_by=$authEmployeeId;
                $employeeRequest->rejected_at=now();
                $employeeRequest->rejected_note=$request->remarks;

            }
            if($request->approve==1){
                if($employeeRequest->approval_status==2) return back()->with('error', 'Already Approved!');
                $note=$request->remarks;
                $this->approvalProcess($employeeRequest, $authEmployeeId, $employeeLineManager, $employeeDepartmentHead, $note);
                /*if($authEmployeeId==$employeeLineManager && $authEmployeeId==$employeeDepartmentHead){
                    $employeeRequest->approval_status = 2;

                    $employeeRequest->line_manager_approved_by=$authEmployeeId;
                    $employeeRequest->line_manager_approved_at=now();
                    $employeeRequest->line_manager_approval_note=$request->remarks;

                    $employeeRequest->department_head_approved_by=$authEmployeeId;
                    $employeeRequest->department_head_approved_at=now();
                    $employeeRequest->department_head_approval_note=$request->remarks;
                }else{
                    if($authEmployeeId==$employeeLineManager){
                        $employeeRequest->line_manager_approved_by=$authEmployeeId;
                        $employeeRequest->line_manager_approved_at=now();
                        $employeeRequest->line_manager_approval_note=$request->remarks;
                        (new MyRequestsController())->sendRequestEmail($employeeRequest, 'department-head');
                    }
                    if($authEmployeeId==$employeeDepartmentHead){
                        if($employeeRequest->line_manager_approved_by==''){
                            return back()->with('error', 'Must be approved by line manager first!');
                        }else{
                            $employeeRequest->department_head_approved_by=$authEmployeeId;
                            $employeeRequest->department_head_approved_at=now();
                            $employeeRequest->department_head_approval_note=$request->remarks;

                            $employeeRequest->approval_status = 2;
                        }

                    }
                }*/
            }
            if($employeeRequest->save()){
                return back()->with('success', 'Successfully Updated');
            }
        }else{
            return back()->with('error', 'Not Found!');
        }
    }

    public function getRoleAs($employeeRequest)
    {
        $employee=$employeeRequest->employee;
        $employeeLineManager=$employee->line_manager ?? '';
        $employeeDepartmentHead=$employee->department_head ?? '';
        $authEmployeeId=getAuthEmpId();
        if($authEmployeeId==$employeeDepartmentHead) return 'Department Head';
        if($authEmployeeId==$employeeLineManager) return 'Line Manager';
    }

    public function getApprovalPermission($item)
    {
        $userRole = getUserRole();

        if (!in_array($userRole, ['department-head', 'line-manager', 'employee'])) {
            return false;
        }

        $employee = $item->employee;

        if (!$employee) {
            return false;
        }

        $employeeLineManager = $employee->line_manager ?? '';
        $employeeDepartmentHead = $employee->department_head ?? '';
        $authEmployeeId = auth()->user()->emp_id;
        $approvalPermission = false;

        $lineManagerApproval = $item->lineManagerApproved;
        $departmentHeadApproval = $item->departmentHeadApproved;

        if (in_array($item->approval_status, [2, 3])) {
            return false;
        }

        if ($authEmployeeId == $employeeLineManager) {
            return !$lineManagerApproval;
        }

        if ($authEmployeeId == $employeeDepartmentHead) {
            if ($lineManagerApproval) {
                return !$departmentHeadApproval;
            }
            return false;
        }
        return false;
    }
    public function getApprovalInfo($item)
    {

        $lineManagerApproval = $item->lineManagerApproved;
        $line_manager_approved_by = $lineManagerApproval->full_name ?? '';
        $line_manager_approved_at = $item->line_manager_approved_at ? formatCarbonDate($item->line_manager_approved_at, 'datetime') : '';
        $line_manager_approval_note = $item->line_manager_approval_note ?? 'N/A';

        $departmentHeadApproval = $item->departmentHeadApproved;
        $department_head_approved_by = $departmentHeadApproval->full_name ?? '';
        $department_head_approved_at = $item->department_head_approved_at ? formatCarbonDate($item->department_head_approved_at, 'datetime') : '';
        $department_head_approval_note = $item->department_head_approval_note ?? 'N/A';

        $rejectedBy = $item->rejectedBy;
        $rejected_by = $rejectedBy->full_name ?? '';
        $rejected_at = $item->rejected_at ? formatCarbonDate($item->rejected_at, 'datetime') : '';
        $rejected_note = $item->rejected_note ?? 'N/A';


        return [
            'is_line_manager_approved' => !empty($lineManagerApproval),
            'line_manager_approved_by' => $line_manager_approved_by,
            'line_manager_approved_at' => $line_manager_approved_at,
            'line_manager_approval_note' => $line_manager_approval_note,

            'is_department_head_approved' => !empty($departmentHeadApproval),
            'department_head_approved_by' => $department_head_approved_by,
            'department_head_approved_at' => $department_head_approved_at,
            'department_head_approval_note' => $department_head_approval_note,

            'rejected_by' => $rejected_by,
            'rejected_at' => $rejected_at,
            'rejected_note' => $rejected_note,
        ];
    }

    public function emailPreview(Request $request, $id)
    {
        $reqInfo = json_decode(tripleBase64Decode($id));
        if(!isset($reqInfo->request_id)) return view('error-page.forbidden');
        $id=$reqInfo->request_id;
        $employeeRequest=EmployeeRequest::find($id);
        if($employeeRequest){
            if($reqInfo->approve_as=='line-manager' && $employeeRequest->created_at->addHours(1)<now()){
                return view('error-page.link-expired-public-page');
            }

            if($reqInfo->approve_as=='department-head' && Carbon::parse($employeeRequest->line_manager_approved_at)->addHours(1)<now()){
                return view('error-page.link-expired-public-page');
            }
            return view('request-approvals.email-preview', compact('id','employeeRequest', 'reqInfo'));
        }else{
            return 'Not Found!';
        }
    }

    public function emailPreviewSubmit(EmailPreviewRequest $request, $id)
    {
        $reqInfo = json_decode(tripleBase64Decode($id));
        if(!isset($reqInfo->request_id)) return 0;
        $id=$reqInfo->request_id;
        $employeeRequest=EmployeeRequest::find($id);
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
                    $this->approvalProcess($employeeRequest, $authEmployeeId, $employeeLineManager, $employeeDepartmentHead, $note);
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
    }


    public function approvalProcess($employeeRequest, $authEmployeeId, $employeeLineManager, $employeeDepartmentHead, $note, $data=[])
    {
        if($authEmployeeId==$employeeLineManager && $authEmployeeId==$employeeDepartmentHead){
            $employeeRequest->approval_status = 2;

            $employeeRequest->line_manager_approved_by=$authEmployeeId;
            $employeeRequest->line_manager_approved_at=now();
            $employeeRequest->line_manager_approval_note=$note;

            $employeeRequest->department_head_approved_by=$authEmployeeId;
            $employeeRequest->department_head_approved_at=now();
            $employeeRequest->department_head_approval_note=$note;
        }else{
            if($authEmployeeId==$employeeLineManager){
                $employeeRequest->line_manager_approved_by=$authEmployeeId;
                $employeeRequest->line_manager_approved_at=now();
                $employeeRequest->line_manager_approval_note=$note;
                (new MyRequestsController())->sendRequestEmail($employeeRequest, 'department-head', $data);
            }
            if($authEmployeeId==$employeeDepartmentHead){
                if($employeeRequest->line_manager_approved_by==''){
                    return back()->with('error', 'Must be approved by line manager first!');
                }else{
                    $employeeRequest->department_head_approved_by=$authEmployeeId;
                    $employeeRequest->department_head_approved_at=now();
                    $employeeRequest->department_head_approval_note=$note;
                    $employeeRequest->approval_status = 2;
                }
            }
        }
    }


}
