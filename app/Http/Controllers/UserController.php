<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommonRequest;
use App\Mail\NewUserMail;
use App\Mail\ResetPasswordMail;
use App\Models\Organization;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\Employee;
use App\Models\Role;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $roles=Role::get();
        if ($request->ajax()) {
            $searchKey = $request->search['value'] ?? '';
            $role = $request->role;
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
            $query = User::query();
            $recordsTotal = $query->count();
            if ($searchKey) {
                $query->where(function ($query) use ($searchKey) {
                    $query->orWhere('emp_id', 'like', "%{$searchKey}%");
                    $query->orWhereIn('emp_id', function ($subquery) use ($searchKey) {
                        $subquery->select('emp_id')
                            ->from(with(new Employee)->getTable())
                            ->where('full_name', 'like', "%{$searchKey}%")
                            ->orWhere('phone', 'like', "%{$searchKey}%")
                            ->orWhereIn('organization', function ($subquery) use ($searchKey) {
                                $subquery->select('id')
                                    ->from(with(new Organization)->getTable())
                                    ->where('name', 'like', "%{$searchKey}%");
                            })
                            ->orWhereIn('department', function ($subquery) use ($searchKey) {
                                $subquery->select('id')
                                    ->from(with(new Departments)->getTable())
                                    ->where('name', 'like', "%{$searchKey}%");
                            })
                            ->orWhereIn('designation', function ($subquery) use ($searchKey) {
                                $subquery->select('id')
                                    ->from(with(new Designations)->getTable())
                                    ->where('name', 'like', "%{$searchKey}%");
                            })
                            ->orWhere('gender', 'like', "%{$searchKey}%")
                            ->orWhere('personal_email', 'like', "%{$searchKey}%");
                    });
                    $query->orWhereIn('role_id', function ($subquery) use ($searchKey) {
                        $subquery->select('id')
                            ->from(with(new Role())->getTable())
                            ->where('name', 'like', "%{$searchKey}%")
                            ->orWhere('slug', 'like', "%{$searchKey}%");
                    });
                });
                $query->orWhere('email', 'like', "%{$searchKey}%");
            }
            if ($role) $query->where('role_id', $role);

            // ORDERS

            switch ($orderByColumn) {
                case 'name':
                    $query->orderBy('name', $orderByDirection);
                    break;
                case 'role':
                    $query->orderBy(Role::select('name')->whereColumn('id', 'users.role_id'), $orderByDirection);
                    break;
                default:
                    $query->orderBy('id', 'asc');
                    break;
            }

            $recordsFiltered = $query->get();  // Filtered Data to count
            $query->limit($limit)->offset($offset);

            $users = $query->get();


            $editPermission=userCan('user.edit');
            $deletePermission=userCan('user.delete');

            $usersData = [];
            $i=1;
            foreach ($users as $item) {
                $defaultProfileImage=employeeDefaultProfileImage($item->gender);
                $profile_img=employeeProfileImage($item->emp_id, $item->profile_photo);
                $usersData[] = [
                    'id' => $item->id,
                    'sl' => $i,
                    'emp_id' => $item->employee->emp_id ?? '',
                    'user_name' => $item->name ?? '',
                    'full_name' => $item->employee->full_name ?? '',
                    'role' => $item->role->name ?? '',
                    'is_active' => $item->is_active ?? '',
                    'profile_img' => $item->employee ? $profile_img : $item->user_image,
                    'profile_img_default' => $defaultProfileImage,
                    'email' => $item->email ?? '',
                    'phone' => $item->employee->phone ?? '',
                    'gender' => $item->employee->gender ?? '',
                    'designation' => $item->employee->empDesignation->name ?? '',
                    'department' => $item->employee->empDepartment->name ?? '',
                    'organization' => $item->employee->empOrganization->name ?? '',
                    'editPermission'=>$item->role->slug=='super-admin' ? false : $deletePermission,
                    'deletePermission'=>$item->role->slug=='super-admin' || $item->id==auth()->user()->id ? false : $deletePermission,
                ];
                $i++;
            }
            $response = [
                'status' => 1,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered->count(),
                'data' => $usersData,
                'orderableColumns' => $orderByColumn . $orderByDirection
            ];
            return response()->json($response);
        }
        return view('users.index', compact( 'roles'));
    }
    public function usersList()
    {
        $roles = Role::get();
        $organizations = Organization::get();
        $users = User::paginate(50);
        return view('users.users-list', compact('users', 'roles', 'organizations'));
    }
    public function create()
    {
        $roles = Role::get();
        $organizations = Organization::get();
        return view('users.create', compact('roles', 'organizations'));
    }
/*     public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
        ]);
        $password = 'NexHRM#' . \Str::password(16, true, true, false, false);
        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            // $user->role_id = $request->role;
            // $user->password = Hash::make($request->password);
            $user->password = Hash::make($password);
            $user->organization_id = $request->organization;

            $user->save();
            $userRole = Role::where('id', $request->role)->first();
            $user->roles()->attach($userRole);

            DB::commit();

            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $password,
            ];

            $email = new NewUserMail($data);
            $email->userData = $data;
            \Mail::to($user->email)->send($email);
            return response()->json(['message' => 'User created successfully', 'status' => 200], 200);

            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }
    } */
    public function store(CommonRequest $request)
    {
        // Create a new user instance
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role;
        $password = 'NexHRM#'.Str::password(16, true, true, false, false).'~SnS';
        $user->password = Hash::make($password);
        $user->is_active = $request->status == 'on' ? 1 : 0;

        // Handle file upload if present
        if ($request->hasFile('user_image')) {
            $profilePhoto = $request->file('user_image');
            $profilePhotoName = time() . '_' . $profilePhoto->getClientOriginalName();
            $profilePhoto->move(public_path('uploads/users/'), $profilePhotoName);
            $user->user_image = 'uploads/users/' . $profilePhotoName;
        }

        // Save the user and assign the role
        if ($user->save()) {
            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $password,
            ];
            Mail::to($user->email)->send(new NewUserMail($data));

            return back()->with('success', 'User created successfully!');
        } else {
            return back()->with('error', 'User creation failed!');
        }
    }

    public function edit($id)
    {
        if(!userCan('user.edit')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
        $user = User::find($id);
        if(!$user) return response()->json(['status' => 0,'msg'=>'User Not Found!']);
        $roles = Role::get();
        $html=View::make('users.edit', compact('user', 'roles'))->render();
        return response()->json(['status' => 1,'html'=>$html]);
    }
    public function update(CommonRequest $request, $id)
    {
        //return $request;
        $user = User::find($id);
        if(!$user) return back()->with('erorr', 'User Not Found!');
        if(!$user->employee){
            $user->name = $request->name;
            if($request->email) $user->email = $request->email;
        }
        if($request->role) $user->role_id = $request->role;
        if ($request->hasFile('user_image')) {
            $profilePhoto = $request->file('user_image');
            $profilePhotoName = $id.'_'.time().'_' . $profilePhoto->getClientOriginalName();
            $profilePhoto->move(public_path('uploads/users/'), $profilePhotoName);
            $user->user_image = 'uploads/users/'.$profilePhotoName;
        }
        $user->is_active = $request->status == 'on' ? 1 : 0;
        $user->save();
        return back()->with('success', 'Successfully Updated!');
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User has been deleted');
    }

    public function forceDelete()
    {
    }
    public function restore()
    {
    }

    public function resetPassword($id)
    {
        $password = 'NexHRM#' . \Str::password(16, true, true, false, false);
        $user = User::find($id);
        $user->password = Hash::make($password);
        $user->save();
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $password,
        ];
        $email = new ResetPasswordMail($data);
        $email->userData = $data;
        \Mail::to($user->email)->send($email);

        return redirect()->route('users.index')->with('success', 'Password reset successfully');
    }

    public function supervisedTeamMembers($userId='')
    {
        if($userId=='') $userId=auth()->user()->id;
        return TeamMember::whereIn('team_id', function ($query) use($userId) {
            $query->select('id')
                ->from('teams')
                ->whereIn('id', function ($query) use($userId) {
                    $query->select('team_id')
                        ->from('team_department_head')
                        ->whereIn('department_head_id', function ($query) use($userId) {
                            $query->select('emp_id')
                                ->from('users')
                                ->where('id', $userId); // Change the user ID as needed
                        });
                });
        })->pluck('emp_id');
    }
}
