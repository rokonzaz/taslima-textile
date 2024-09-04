<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommonRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Departments;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

class DepartmentController extends Controller
{
    public function create(){
        return view("department.create");
    }
    public function index(Request $request){
        if(!in_array(getUserRole(), ['super-admin', 'hr'])) return view('error-page.unauthorized');
        if ($request->ajax()) {
            $searchKey = $request->search['value'] ?? '';
            $name = $request->name;
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
            $query = Departments::query();
            $recordsTotal = $query->count();
            if ($searchKey) {
                $query->where(function ($query) use ($searchKey) {
                    $query->orWhere('name', 'like', "%{$searchKey}%");
                });
            }

            // ORDERS

            switch ($orderByColumn) {
                case 'name':
                    $query->orderBy('name', $orderByDirection);
                    break;
                case 'qty':
                    $query->orderBy(function ($query) {
                        $query->selectRaw('SUM(employees.id)')
                            ->from('employees')
                            ->whereColumn('employees.department', 'departments.id');
                    }, $orderByDirection);
                    break;
                default:
                    $query->orderBy('name', 'asc');
                    break;
            }

            $recordsFiltered = $query->get();  // Filtered Data to count
            $query->limit($limit)->offset($offset);

            $employees = $query->get();

            $employeeData = [];
            foreach ($employees as $key=>$item) {
                $employeeData[] = [
                    'id' => $item->id,
                    'sl' => $offset+$key+1,
                    'name' => $item->name ?? '',
                    'qty' => $item->employees->count() ?? 0,
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
        $departments = Departments::get();
        return view("department.index", compact('departments'));

    }
    public function store(CommonRequest $request)
    {
        if (!in_array(getUserRole(), ['super-admin', 'hr'])) {
            return back()->with('error', 'Unauthorized Access!');
        }
        $department = new Departments;
        $department->name = $request->name;
        $department->is_active = $request->has('is_active')=='on' ? 1 : 0;
        if ($department->save()) {
            return redirect()->route('department.index')->with('success', 'Department created successfully');
        } else {
            return back()->with('error', 'Failed to create department');
        }
    }

    public function edit($id){
        if(!in_array(getUserRole(), ['super-admin', 'hr'])) return response()->json(['status'=>0, 'msg'=>'Unauthorized Access!']);
        $department = Departments::find($id);
        if($department){
            return response()->json([
                'status'=>1,
                'html'=>View::make('department.edit', compact('department'))->render(),
            ]);
        }else{
            return response()->json(['status'=>0, 'msg'=>'Department Not Found!']);
        }
    }
    public function update(CommonRequest $request, $id)
    {
        // Validation is automatically handled
        $department = Departments::find($id);

        if (!$department) {
            return back()->with('error', 'Department not found.');
        }

        $department->name = $request->name;
        $department->is_active = $request->is_active == 'on' ? 1 : 0;
        $department->save();

        return redirect()->route('department.index')->with('success', 'Department updated successfully');
    }

    public function delete($id)
    {
        $department = Departments::findOrFail($id);
        $empCount=$department->employees->count();
        if($empCount>0) return back()->with('error', "Can not be deleted, $empCount employees found here!");
        $department->delete();
        return back()->with('success', 'Department deleted successfully');
    }
}
