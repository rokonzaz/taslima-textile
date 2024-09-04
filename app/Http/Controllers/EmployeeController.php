<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequestValidate;
use App\Mail\NewUserMail;
use App\Models\Organization;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\DocumentType;
use App\Models\DutySlot;
use App\Models\EmployeeDocument;
use App\Models\EmployeeEducation;
use App\Models\ImportLog;
use App\Models\LeaveDaysManual;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        if(!userCan('employee.view')) return view('error-page.unauthorized');

        if ($request->ajax()) {
            $searchKey = $request->search['value'] ?? '';
            $organization = $request->organization;
            $department = $request->department;
            $designation = $request->designation;
            $designation = $request->designation;
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
            $query = Employee::query();
            if(isRoleIn(['super-admin','hr'])){

            }else{
                $query->whereIn('emp_id', getManageableEmployeesIDs());

            }

            $recordsTotal = $query->count();
            if ($searchKey) {
                $query = $query->withInactive();
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

            $viewPermission= userCan('employee.view');
            $editPermission= userCan('employee.edit');
            $deletePermission= userCan('employee.delete');
            $assignRolePermission= userCan('employee.assign-role');
            $is_superAdmin = getUserRole()=='super-admin';
            $is_hr = getUserRole()=='hr';

            $employeeData = [];
            foreach ($employees as $item) {
                $employeeData[] = [
                    'id' => $item->id,
                    'emp_id' => $item->emp_id ?? '',
                    'name' => $item->full_name ?? '',
                    'profile_img' => employeeProfileImage($item->emp_id, $item->profile_photo),
                    'profile_img_default' => employeeDefaultProfileImage($item->gender),
                    'email' => $item->email ?? '',
                    'departmentHead' => $item->empDepartmentHead->full_name ?? '',
                    'line_manager' => $item->empLineManager->full_name ?? '',
                    'phone' => $item->phone ?? '',
                    'gender' => $item->gender ?? '',
                    'designation' => $item->empDesignation->name ?? '',
                    'department' => $item->empDepartment->name ?? '',
                    'organization' => getFirstWord($item->empOrganization->name ?? ''),
                    'is_user' => $item->user ? 1 : 0,
                    'is_active' => $item->is_active,
                    'is_superAdmin'=> $is_superAdmin,
                    'is_hr'=> $is_hr,
                    'viewPermission'=> $viewPermission,
                    'editPermission'=> $editPermission,
                    'deletePermission'=> $deletePermission,
                    'assignRolePermission'=> $assignRolePermission,
                ];
            }
            $response = [
                'status' => 1,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered->count(),
                'data' => $employeeData,
                'orderableColumns' => $orderByColumn . $orderByDirection
            ];
            return response()->json($response);
        }
        $designations = Designations::all();
        $departments = Departments::all();
        $organizations = Organization::get();
        $dutySlots = DutySlot::get();
        return view('employee.index', compact('organizations', 'designations', 'departments', 'dutySlots'));
    }

    public function store(EmployeeRequestValidate $request)
    {
        if(!userCan('employee.create')) return back()->with('error', 'Unauthorized Access!');
        $employee = new Employee();
        $employee->emp_id = $request->emp_id;
        $employee->full_name = $request->full_name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->birth_year = $request->birth_year;
        $employee->gender = $request->gender;
        $employee->blood_group = $request->blood_group;
        $employee->emergency_contact = $request->emergency_contact;
        $employee->permanent_address = $request->permanent_address;
        $employee->present_address = $request->present_address;
        $employee->organization = $request->organization;
        $employee->designation = $request->designation;
        $employee->department = $request->emp_department;
        $employee->joining_date = $request->joining_date;
        $employee->duty_slot = $request->duty_slot;

        if ($employee->save()) {
            $employeeId = $employee->id;
            $isUpdateable=false;
            if ($request->hasFile('profile_photo')) {
                $profilePhoto = $request->file('profile_photo');
                $profilePhotoName = $employeeId.'_'.time().'_' . $profilePhoto->getClientOriginalName();
                $profilePhoto->move(public_path('uploads/employees/'.$employee->emp_id.'/profile'), $profilePhotoName);
                $employee->profile_photo = $profilePhotoName;
                $isUpdateable=true;
            }
            if ($request->hasFile('employee_resume')) {
                $resumeFile = $request->file('employee_resume');
                $resumeFileName = $employeeId.'_'.time().'_'.$resumeFile->getClientOriginalName();
                $resumeFile->move(public_path('uploads/employees/resume'), $resumeFileName);
                $employee->employee_resume = $resumeFileName;
                $isUpdateable=true;
            }
            if($isUpdateable){
                $employee->save();
            }
            return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        }
        abort(403);

    }

    public function edit(Request $request, $id)
    {
        if($request->ajax()){
            if(getUserRole()=='employee'){
                if(auth()->user()->employee->id!=$id){
                    if(!userCan('employee.edit')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
                }
            }else{
                if(!userCan('employee.edit')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
            }
            $a=$request->a;
            if($a=='personal'){
                $employee = Employee::find($id);
                $designations = Designations::all();
                $departments = Departments::all();
                $organizations = Organization::get();
                $dutySlots = DutySlot::get();
                $html=View::make('employee.edit', compact('a', 'employee', 'organizations', 'designations', 'departments', 'dutySlots'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                ];
                return $response;
            }
            if($a=='address'){
                $employee = Employee::find($id);
                $html=View::make('employee.edit', compact('a', 'employee'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                ];
                return $response;
            }
            if($a=='add-manual-leave-form'){
                $employee = Employee::find($id);
                $leaveType=LeaveType::get();
                $html=View::make('employee.edit', compact('a', 'employee', 'leaveType'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                ];
                return $response;
            }
            if($a=='leave-manual-count-edit'){
                $leaveType=LeaveType::get();
                $manualLeaveCount=leaveDaysManual::find($id);
                $html=View::make('employee.edit', compact('manualLeaveCount', 'a', 'leaveType'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                ];
                return $response;
            }
            if($a=='dsignature'){
                $employee = Employee::find($id);
                $html=View::make('employee.edit', compact('a', 'employee'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                ];
                return $response;
            }
            if($a=='security'){
                $employee = Employee::find($id);
                $html=View::make('employee.edit', compact('a', 'employee'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                ];
                return $response;
            }
            if($a=='select-department-head-form'){
                $employee = Employee::find($id);
                if(!$employee) return response()->json(['status'=>1, 'msg'=>'Employee Not Found!']);
                $allEmployees=Employee::get();
                $html=View::make('employee.edit', compact('a', 'employee', 'allEmployees'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                ];
                return $response;
            }
            if($a=='select-line-manager-form'){
                $employee = Employee::find($id);
                if(!$employee) return response()->json(['status'=>1, 'msg'=>'Employee Not Found!']);
                $allEmployees=Employee::get();
                $html=View::make('employee.edit', compact('a', 'employee', 'allEmployees'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                ];
                return $response;
            }
        }
    }

    public function assignRoleForm(Request $request, $empId)
    {
        if($request->ajax()){
            if(!userCan('employee.assign-role')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
            $employee = Employee::find($empId);
            if($employee){
                if(!($employee->email || $employee->personal_email)) return response()->json(['status'=>0, 'msg'=>'Employee has no email!']);
                if(!$employee->user){
                    $roles=Role::get();
                    $html=View::make('employee.partials.assignRole', compact('employee', 'roles'))->render();
                    return response()->json(['status'=>1, 'html'=>$html]);
                }else{
                    return response()->json(['status'=>0, 'msg'=>'Already User']);
                }
            }else{
                return response()->json(['status'=>0, 'msg'=>'Employee not found!']);
            }
        }
        abort(403);

    }
    public function assignRole(Request $request, $empId)
    {
        if($request->ajax()){
            if(!userCan('employee.assign-role')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
            if(!($request->role!='' && $request->email!='')) return response()->json(['status'=>0, 'msg'=>'There is no email provided']);
            $employee = Employee::find($empId);
            if($employee){
                if($employee->user) return response()->json(['status'=>0, 'msg'=>'Already Assigned as  User']);
                if(User::where('emp_id', $employee->emp_id)->orWhere('email', $request->email)->count()!=0) return response()->json(['status'=>0, 'msg'=>'Already Assigned as  User']);
                $trashedUser=User::where('emp_id', $employee->emp_id)->orWhere('email', $request->email)->withTrashed()->first();
                if($trashedUser){
                    $trashedUser->role_id=$request->role;
                    $trashedUser->save();
                    $trashedUser->restore();
                    return response()->json(['status'=>1, 'msg'=>'Successfully Assigned as  User']);
                }else{
                    $user=new User();
                    $user->emp_id=$employee->emp_id;
                    $user->name=$employee->full_name;
                    $user->role_id=$request->role;
                    $user->email=$request->email;
                    $generatedPassword=strongPasswordGenerator();
                    $user->password=Hash::make($generatedPassword);
                    if($user->save()){
                        $data = [
                            'name' => $user->name,
                            'email' => $user->email,
                            'password' => $generatedPassword,
                        ];
                        $email = new NewUserMail($data);
                        $email->userData = $data;
                        //Mail::to($user->email)->send($email);
                        return response()->json(['status'=>1, 'msg'=>'Successfully Assigned as  User', 'psw'=>$generatedPassword]);
                    }
                }

                return response()->json(['status'=>0, 'msg'=>'Error Occurred!']);

            }else{
                return response()->json(['status'=>0, 'msg'=>'Employee not found!']);
            }
        }
        abort(403);

    }
    public function changeActiveStatusForm(Request $request, $empId)
    {
        if($request->ajax()){
            if(!in_array(getUserRole(),['super-admin','hr'])) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
            $employee = Employee::withInactive()->find($empId);
            if($employee){
                $roles=Role::get();
                $html=View::make('employee.partials.change-status', compact('employee', 'roles'))->render();
                return response()->json(['status'=>1, 'html'=>$html]);
            }else{
                return response()->json(['status'=>0, 'msg'=>'Employee not found!']);
            }
        }
        abort(403);

    }
    public function changeActiveStatus(Request $request, $empId)
    {
        // return $request;
        if($request->ajax()){
             if(!in_array(getUserRole(),['super-admin','hr'])) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
            // if(!($request->role!='' && $request->email!='')) return response()->json(['status'=>0, 'msg'=>'There is no email provided']);
            $employee = Employee::withInactive()->find($empId);
            if($employee){
                $employee->is_active=$request->is_active;


                $statusNote=[
                    'status'=>$request->is_active==1 ? 'Active' : 'Inactive',
                    'datetime'=>now(),
                    'reason'=>$request->status_reason,
                ];
                if($employee->is_active_note!=''){
                    $previousStatus=$employee->is_active_note;
                    $statusNote=$previousStatus.','.json_encode($statusNote);
                }
                $employee->is_active_note=$statusNote;
                if($employee->save()){
                    if($request->is_active==0) {
                        if ($employee->user) {
                            $user = $employee->user;
                            $user->is_active = 0;
                            $user->save();
                        }
                    }
                    return response()->json(['status'=>1, 'msg'=>'Successfully Change Active Status']);
                }else{
                    return response()->json(['status'=>0, 'msg'=>'Error Occurred!']);
                }

            }else{
                return response()->json(['status'=>0, 'msg'=>'Employee not found!']);
            }
        }
        abort(403);

    }

    public function update(Request $request, $id)
    {

        if(getUserRole()=='employee'){
            if(auth()->user()->employee->id!=$id){
                if(!userCan('employee.edit')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
            }
        }else{
            if(!userCan('employee.edit')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
        }
        $employee = Employee::find($id);
        if($employee){
            $a=$request->a;
            switch ($a) {
                case 'profile-image':
                    if ($request->hasFile('profile_photo')) {
                        $profilePhoto = $request->file('profile_photo');
                        $profilePhotoName = $id.'_'.time().'_' . $profilePhoto->getClientOriginalName();
                        $profilePhoto->move(public_path('uploads/employees/'.$employee->emp_id.'/profile'), $profilePhotoName);
                        $employee->profile_photo = $profilePhotoName;
                    }
                    if($employee->save()){
                        return back()->with('success', 'Employee Profile Image updated successfully.');
                    }
                    break;
                case 'personal':
                    if(!in_array(getUserRole(), ['super-admin', 'hr'])){
                        return back()->with('error', 'Unauthorized Access!');
                    }
                    $employee->full_name = $request->full_name;
                    $employee->email = $request->email;
                    $employee->phone = $request->phone;
                    $employee->alternative_phone = $request->alternative_phone;
                    $employee->birth_year = $request->birth_year;
                    $employee->gender = $request->gender;
                    $employee->blood_group = $request->blood_group;
                    $employee->organization = $request->organization_name;
                    $employee->designation = $request->designation;
                    $employee->department = $request->department;
                    $employee->joining_date = $request->joining_date;
                    $employee->emergency_contact = $request->emergency_contact;
                    $employee->personal_email = $request->personal_email;
                    $employee->duty_slot = $request->duty_slot;
                    break;
                case 'address':
                    if(!in_array(getUserRole(), ['super-admin', 'hr'])){
                        return back()->with('error', 'Unauthorized Access!');
                    }
                    $employee->present_address = $request->present_address;
                    $employee->permanent_address = $request->permanent_address;
                    break;
                case 'skills':
                    if(!in_array(getUserRole(), ['super-admin', 'hr'])){
                        return back()->with('error', 'Unauthorized Access!');
                    }
                    if($request->skills) {
                        $skills = implode(', ', $request->skills);
                        $employee->skills = $skills;
                    }else{
                        $employee->skills = '';
                    }
                    break;

                case 'education':
                    if(!in_array(getUserRole(), ['super-admin', 'hr'])){
                        return back()->with('error', 'Unauthorized Access!');
                    }
                    $education=new EmployeeEducation();
                    $education->emp_id = $employee->emp_id;
                    $education->institution_name = $request->input('institution_name') ?? '';
                    $education->degree = $request->input('degree') ?? '';
                    $education->department = $request->input('department') ?? '';
                    $education->passing_year = $request->input('passing_year') ?? '0';
                    $education->result = $request->input('result') ?? '0.00';
                    if($education->save()){
                        return back()->with('success', 'Employee updated successfully.');
                    }
                    break;
                case 'documents':
                    if(!in_array(getUserRole(), ['super-admin', 'hr'])){
                        return back()->with('error', 'Unauthorized Access!');
                    }
                    $doc=new EmployeeDocument();
                    $doc->emp_id = $employee->emp_id;
                    $doc->title = $request->input('title') ?? '';
                    $doc->document_type = $request->input('document_type') ?? '';
                    if ($request->hasFile('documentFile')) {
                        $file = $request->file('documentFile');
                        $fileExtension = $file->getClientOriginalExtension();
                        $fileName = $id.'_'.time().'_' . Str::slug($request->title).'.'.$fileExtension;
                        $file->move(public_path("uploads/employees/{$employee->emp_id}/documents"), $fileName);
                        $fileExtension = strtolower($fileExtension);
                        $fileTypes = [
                            'jpg' => 'image',
                            'jpeg' => 'image',
                            'png' => 'image',
                            'gif' => 'image',
                            'pdf' => 'pdf',
                            'doc' => 'document',
                            'docx' => 'document',
                            'xls' => 'document',
                            'xlsx' => 'document',
                            'ppt' => 'document',
                            'pptx' => 'document',
                            'txt' => 'text',
                            'zip' => 'compressed',
                            'rar' => 'compressed',
                        ];
                        $fileType = isset($fileTypes[$fileExtension]) ? $fileTypes[$fileExtension] : 'others';
                        $doc->file_name = $fileName;
                        $doc->file_type = $fileType;
                        if($doc->save()){
                            return back()->with('success', 'Employee added documents successfully.');
                        }
                    }
                    break;
                case 'add-manual-leave-form':
                    if(!in_array(getUserRole(), ['super-admin', 'hr'])){
                        return back()->with('error', 'Unauthorized Access!');
                    }
                    $employee=Employee::find($id);
                    if(LeaveDaysManual::where('emp_id', $employee->emp_id)->where('year', $request->year)->count()>0) return back()->with('error', 'Already Exists!');
                    $leaveDaysManual=new LeaveDaysManual();
                    $leaveDaysManual->emp_id=$employee->emp_id;

                    $counting=$request->leaveCount;
                    $total=0;
                    foreach ($counting as $key=>$val){
                        if($key==1) $leaveDaysManual->casual_leave=$val;
                        if($key==2) $leaveDaysManual->sick_leave=$val;
                        if($key==3) $leaveDaysManual->annual_leave=$val;
                        $total+=$val;
                    }
                    $leaveDaysManual->total=$total;
                    $leaveDaysManual->year=$request->year;
                    $leaveDaysManual->remarks=$request->remarks;
                    if($leaveDaysManual->save()) return back()->with('success', 'Successfully added!');
                    else return back()->with('error', 'Server Error!');
                    break;
                case 'leave-manual-count-edit':
                    if(!in_array(getUserRole(), ['super-admin', 'hr'])){
                        return back()->with('error', 'Unauthorized Access!');
                    }
                    $leaveDaysManual=LeaveDaysManual::find($id);
                    $employee=Employee::where('emp_id', $leaveDaysManual->emp_id)->first();
                    if(LeaveDaysManual::where('emp_id', $employee->emp_id)->where('year', $request->year)->whereNotIn('id', [$id])->count()>0) return back()->with('error', 'Already Exists!');

                    $counting=$request->leaveCount;
                    $total=0;
                    foreach ($counting as $key=>$val){
                        if($key==1) $leaveDaysManual->casual_leave=$val;
                        if($key==2) $leaveDaysManual->sick_leave=$val;
                        if($key==3) $leaveDaysManual->annual_leave=$val;
                        $total+=$val;
                    }
                    $leaveDaysManual->total=$total;
                    $leaveDaysManual->year=$request->year;
                    $leaveDaysManual->remarks=$request->remarks;
                    if($leaveDaysManual->save()) return back()->with('success', 'Successfully updated!');
                    else return back()->with('error', 'Server Error!');
                    break;
                case 'dsignature':
                    if ($request->hasFile('digital_signature')) {
                        $signaturePhoto = $request->file('digital_signature');
                        $signaturePhotoName = $id.'_'.time().'_' . $signaturePhoto->getClientOriginalName();
                        $signaturePhoto->move(public_path('uploads/employees/'.$employee->emp_id.'/signature'), $signaturePhotoName);
                        $employee->digital_signature = $signaturePhotoName;
                    }
                    if($employee->save()) return back()->with('success', 'Successfully updated!');
                    else return back()->with('error', 'Server Error!');
                    break;
                case 'biometric':
                    if(!in_array(getUserRole(), ['super-admin', 'hr'])){
                        return back()->with('error', 'Unauthorized Access!');
                    }
                    $employee->biometric_id = $request->biometric_id;
                    break;
                case 'security':
                    $request->validate([
                        'current_pass' => 'required',
                        'new_pass' => 'required|min:8|confirmed',
                    ], [
                        'new_pass.confirmed' => 'The new password confirmation does not match.',
                    ]);
                    // Retrieve the employee record
                    $employee = User::where('emp_id', $id)->first();

                    if (!$employee) {
                        return response()->json(['error' => 'User not found'], 404);
                    }

                    // Verify the current password
                    if (!Hash::check($request->current_pass, $employee->password)) {
                        return response()->json(['error' => 'Current password is incorrect'], 400);
                    }

                    // Hash the new password
                    $employee->password = Hash::make($request->new_pass);

                    break;

                case 'select-department-head':
                case 'select-line-manager':
                        if($request->ajax()) {
                            $type = $request->type;
                            $selected_emp_id = $request->empId;
                            if(!$selected_emp_id) return  response()->json(['status'=>0, 'msg'=>'Please Select an Employee']);
                            $selected_emp=Employee::where('emp_id', $selected_emp_id)->first();
                            if(!$selected_emp) return  response()->json(['status'=>0, 'msg'=>'Please Select Valid Employee']);
                            if ($type=='department-head'){
                                $employee->department_head=$selected_emp_id;
                            }elseif ($type=='line-manager'){
                                $employee->line_manager=$selected_emp_id;
                            }
                            if($employee->save()){
                                return  response()->json(['status'=>1, 'msg'=>'Successfully Updated!', 'name'=>$selected_emp->full_name]);
                            }else{
                                return  response()->json(['status'=>0, 'msg'=>'Server Error!']);
                            }
                        }
                    break;
                default:
                    // Default case if $a doesn't match any of the above cases
                    break;
            }
            if($employee->save()){
                return back()->with('success', 'Employee updated successfully.');
            }


        }
    }


    public function view($id)
    {
        if(auth()->user()->emp_id!=$id){
            if(!userCan('employee.view')) return back()->with('error', 'Unauthorized Access!');
        }
        $employee = Employee::withInactive()->where('emp_id', $id)->first();
        if($employee){
            $designations = Designations::all();
            $departments = Departments::all();
            $documentType = DocumentType::all();
            // return $employee;
            return view('employee.view', compact('employee', 'designations', 'departments', 'documentType'));
        }else{
            return view('employee.partials.not-found');
        }
    }


    public function delete($id)
    {
        if(!userCan('employee.delete')) return back()->with('error', 'Unauthorized Access!');
        $employee = Employee::find($id);
        if($employee){
            if(isset($employee->user)){
                if($employee->user->id==auth()->id()) return back()->with('error', 'You cant delete yourself.');
            }
            $employee->delete();
            if(isset($employee->user)){
                $employee->user->delete();
            }
            return back()->with('success', 'Employee deleted successfully.');
        }
        return back()->with('error', 'You cant delete yourself.');
    }
    public function deleteDocument($id)
    {
        if(!userCan('employee.deleteDocument')) return back()->with('error', 'Unauthorized Access!');
        $document = EmployeeDocument::find($id);
        if($document->delete()){
            return back()->with('success', 'Deleted successfully.');
        }
        return back()->with('error', 'Error Occurred!');
    }
    public function deleteEducation($id)
    {
        if(!userCan('employee.deleteDocument')) return back()->with('error', 'Unauthorized Access!');
        $education = EmployeeEducation::find($id);
        if($education->delete()){
            return back()->with('success', 'Deleted successfully.');
        }
        return back()->with('error', 'Error Occurred!');
    }

    public function forceDelete($id)
    {
        $employee = Employee::withTrashed()->findOrFail($id);
        $employee->forceDelete();

        return redirect()->back()->with('success', 'Employee permanently deleted.');
    }


    public function validateSingleData(Request $request)
    {
        if($request->ajax()){
            $dataContent=$request->a;
            $dataValue=$request->val;
            if($dataContent=='email'){
                if(Employee::where('email', $dataValue)->count()==0) $response = ['status' => 1,'msg' => 'Usable'];
                else $response = ['status' => 0,'msg' => 'This email is already exists'];
                return response()->json($response);
            }
            if($dataContent=='phone'){
                if(Employee::where('phone', $dataValue)->count()==0) $response = ['status' => 1,'msg' => 'Usable'];
                else $response = ['status' => 0,'msg' => 'This Phone is already exists'];
                return response()->json($response);
            }
            if($dataContent=='emp_id'){
                if(Employee::where('emp_id', $dataValue)->count()==0) $response = ['status' => 1,'msg' => 'Usable'];
                else $response = ['status' => 0,'msg' => 'This Employee ID is already exists'];
                return response()->json($response);
            }

        }
        abort(403);
    }
    public function importEmployees(Request $request)
    {
        if(!userCan('employee.import')) return view('error-page.unauthorized');
        return view('employee.import.index');
    }
    public function importEmployeesSubmit(Request $request)
    {
        if(!userCan('employee.import')) return back()->with('error', 'Unauthorized Access!');
        if($request->has('file')){
            $file=$request->file('file');
            if (!($file->getClientOriginalExtension() === 'xls' || $file->getClientOriginalExtension() === 'xlsx')) {
                return view('employee.import.invalid');
            }
            $data = Excel::toCollection([], $file);
            $rows = $data[0]->toArray();
            $columnNames = $data->first()->first();
            $colDefs='["Sl No.","ID Number","Name","NID Number","Organization","Department","Designation","Joining Date","Cell Number","Emergency Cell Number","Blood Group","Personal Mail Address","Official Mail Address","Gender","Resign Date"]';
            if($colDefs!=(string)$columnNames){
                return view('employee.import.invalid');
            }
            $organization=[];
            $departments=[];
            $designation=[];
            $phone=[];
            foreach (array_slice($rows, 1) as $row) {
                if(!in_array(trim($row['4']), $organization)) $organization[]=trim($row['4']);
                if(!in_array(trim($row['5']), $departments)) $departments[]=trim($row['5']);
                if(!in_array(trim($row['6']), $designation)) $designation[]=trim($row['6']);
            }
            foreach ($organization as $name) {
                if($name) Organization::firstOrCreate(['name' => $name]);
            }
            foreach ($departments as $name) {
                if($name) Departments::firstOrCreate(['name' => $name]);
            }
            foreach ($designation as $name) {
                if($name) Designations::firstOrCreate(['name' => $name]);
            }
            $errorRow=[];
            $rowCount=0;
            $successCount=0;
            $errorCount=0;
            foreach (array_slice($rows, 1) as $row) {
                $rowCount++;
                $emp_id=trim($row['1']);
                $name=trim($row['2']);
                $nid=trim($row['3']);
                $comp=trim($row['4']);
                $dept=trim($row['5']);
                $desig=trim($row['6']);
                $joiningDate=null; if($row['7']!='') $joiningDate = date('Y-m-d', Date::excelToTimestamp($row['7']));

                $phoneString=$row['8'];
                $numericString = preg_replace("/[^0-9,]/", "", $phoneString);
                $numericArray=explode(',', $numericString);
                $phone='';
                $alternative_phone='';
                if(count($numericArray)==0){
                    $phone=$this->makeElevenDigitNumber($numericString);
                }else{
                    $phone=$this->makeElevenDigitNumber($numericArray[0]);
                    $alternative_phone=isset($numericArray[1]) ? $this->makeElevenDigitNumber($numericArray[1]) : '';
                }
                $emergencyPhone=$row['9'];
                $bloodGroup=$row['10'];
                $personal_email=$row['11'];
                $email=$row['12'];
                $gender=$row['13'];
                $resignData=null; if($row['14']!='') $resignData = date('Y-m-d', Date::excelToTimestamp($row['14']));

                if(!$emp_id) {
                    $errorRow[]=[
                        'data'=>$row,
                        'status'=>0,
                        'msg'=>'Missing: Employee Id'
                    ];
                    $errorCount++;
                    continue;
                }
                if(Employee::where('emp_id', $emp_id)->count()==0){
                    $employee=new Employee();
                    $employee->emp_id = $emp_id ?? '';
                    $employee->full_name = $name;
                    $employee->email = $email;
                    $employee->phone = $phone;
                    $employee->alternative_phone = $alternative_phone;
                    $employee->blood_group = $bloodGroup;
                    $employee->emergency_contact = $emergencyPhone;
                    $employee->organization = Organization::select('id')->where('name', $comp)->pluck('id')->first();
                    $employee->designation = Designations::select('id')->where('name', $desig)->pluck('id')->first();
                    $employee->department = Departments::select('id')->where('name', $dept)->pluck('id')->first();
                    $employee->joining_date = $joiningDate;
                    $employee->resign_date = $resignData;
                    $employee->personal_email = $personal_email;
                    $employee->gender = $gender;
                    $employee->nid = $nid;
                    if($employee->save()){
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
                        'msg'=>'Exists'
                    ];
                    $errorCount++;
                }
            }
            $importLog=new ImportLog();
            $importLog->name="Employee Import";
            $importLog->description=json_encode($errorRow);
            $importLog->row_count=$rowCount;
            $importLog->success_count=$successCount;
            $importLog->error_count=$errorCount;
            $importLog->save();
            return view('employee.import.summary', compact('errorRow', 'importLog'));
        }else{
            return view('employee.import.invalid');
        }
    }

    public function makeElevenDigitNumber($string)
    {
        if (strlen($string) < 11) {
            $string = '0' . $string;
        }
        if (strlen($string) > 11) {
            $string = substr($string, -11);
        }
        return $string;
    }
    public function getEmployeeByNotice(Request $request){
        $searchKey = $request->search['value'] ?? '';
        $organization = $request->organization;
        $department = $request->department;
        if ($request->ajax()) {
            $query = Employee::query();
            $recordsTotal = $query->count();
            if ($searchKey) {
                $query = $query->withInactive();
                $query->where(function ($query) use ($searchKey) {
                    $query->orWhere('emp_id', 'like', "%{$searchKey}%");
                    $query->orWhere('full_name', 'like', "%{$searchKey}%");
                    $query->orWhere('email', 'like', "%{$searchKey}%");
                    $query->orWhere('personal_email', 'like', "%{$searchKey}%");
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
            $employees = $query->get();
            $employeeData = [];
            foreach ($employees as $item) {
                $employeeData[] = [
                    'id' => $item->id,
                    'emp_id' => $item->emp_id ?? '',
                    'full_name' => $item->full_name ?? '',
                    'profile_img' => employeeProfileImage($item->emp_id, $item->profile_photo),
                    'profile_img_default' => employeeDefaultProfileImage($item->gender),
                    'email' => $item->email ?? $item->personal_email,
                    'departmentHead' => $item->empDepartmentHead->full_name ?? '',
                    'line_manager' => $item->empLineManager->full_name ?? '',
                    'phone' => $item->phone ?? '',
                    'gender' => $item->gender ?? '',
                    'designation' => $item->empDesignation->name ?? '',
                    'department' => $item->empDepartment->name ?? '',
                    'organization' => getFirstWord($item->empOrganization->name ?? ''),
                    'is_user' => $item->user ? 1 : 0,
                    'is_active' => $item->is_active,
                ];
            }
            $employees=$employeeData;
            return response()->json($employees);
        }
        /* $employees = Employee::where('department', $department)->where('organization', $organization)->get();
        return response()->json($employees); */
    }



}
