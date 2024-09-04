<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommonRequest;
use Illuminate\Http\Request;
use App\Models\Designations;
use Illuminate\Support\Facades\View;

class DesignationController extends Controller
{
    public function create(){
        return view("designation.create");
    }
    public function index(Request $request){
        if(!userCan('employee.view')) return view('error-page.unauthorized');
        $designations = Designations::all();
        if ($request->ajax()) {
            $searchKey = $request->search['value'] ?? '';
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
            $query = Designations::query();
            //$query = $query->active();
            $recordsTotal = $query->count();
            if ($searchKey) {
                $query->where(function ($query) use ($searchKey) {
                    $query->orWhere('name', 'like', "%{$searchKey}%");
                });
            }

            // ORDERS

            switch ($orderByColumn) {
                case 'ID':
                    $query->orderBy('id', $orderByDirection);
                    break;
                case 'name':
                    $query->orderBy('name', $orderByDirection);
                    break;
                case 'status':
                    $query->orderBy('is_active', $orderByDirection);
                    break;
                default:
                    $query->orderBy('name', 'asc');
                    break;
            }

            $recordsFiltered = $query->get();  // Filtered Data to count
            $query->limit($limit)->offset($offset);

            $designations = $query->get();

            $designationData = [];
            foreach ($designations as $key=>$item) {
                $designationData[] = [
                    'id' => $item->id,
                    'sl'=> $key + 1,
                    'name' => $item->name ?? '',
                    'is_active' => $item->is_active,
                ];
            }
            $response = [
                'status' => 1,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered->count(),
                'data' => $designationData,
                'orderableColumns' => $orderByColumn . json_encode($orderByDirection)
            ];
            return response()->json($response);
        }
        return view("designation.index", compact('designations'));

    }
    public function store(CommonRequest $request){
        if(!userCan('employee.create')) return back()->with('error', 'Unauthorized Access!');
        $designation = new Designations;
        $designation->name = $request->name;
        $designation->is_active = $request->is_active =='on' ? 1 : 0;
        $designation->save();
        return redirect()->route('designation.index')->with('success', 'Designation created successfully');
    }

    public function edit(Request $request,$id){
        if(!userCan('employee.edit')) return back()->with('error', 'Unauthorized Access!');
        if($request->ajax()){
            $designation = Designations::find($id);
            if($designation){
                $html=View::make('designation.edit', compact('designation'))->render();
                return response()->json(['status' => 1,'html'=>$html]);
            }else{
                return response()->json(['status' => 0,'msg'=>'Not Found']);
            }
        }
        abort(403);
    }

    public function update(CommonRequest $request, $id){
        if(!userCan('employee.edit')) return back()->with('error', 'Unauthorized Access!');
        $designation = Designations::find($id);
        if($designation){
            $previousDesignation=$designation->name;
            $designation->name = $request->name;
            $designation->is_active = $request->is_active =='on' ? 1 : 0;
            if($designation->save()){
                return back()->with('success', "Successfully Updated $previousDesignation!");
            }else{
                return back()->with('error', 'Server Error!');
            }
        }else{
            return back()->with('error', 'Designation Not Found!');
        }
    }

    public function delete($id)
    {
        if(!userCan('employee.delete')) return back()->with('error', 'Unauthorized Access!');
        $designation = Designations::findOrFail($id);
        $previousDesignation = $designation->name;
        $empCount=$designation->employee->count();
        if($empCount>0){
            return back()->with('error', "Can not be Deleted, $empCount employees are tagged with this $previousDesignation.");
        }
        if($designation->delete()){
            return back()->with('success', "$previousDesignation designation deleted successfully.");
        }
        return back()->with('error', 'Sever Error!');
    }
}
