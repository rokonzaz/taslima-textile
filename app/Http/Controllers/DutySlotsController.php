<?php

namespace App\Http\Controllers;

use App\Http\Requests\DutySlotRequest;
use App\Models\Attendance;
use App\Models\Organization;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\DutySlot;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DutySlotsController extends Controller
{
    public function index(Request $request)
    {
        $designations = Designations::all();
        $departments = Departments::all();
        $organizations = Organization::get();
        if ($request->ajax()) {
            $searchKey = $request->search['value'] ?? '';
            $organization = $request->organization;
            $department = $request->department;
            $designation = $request->designation;
            $date = $request->date ?? date('Y-m-d');
            $start_date=$date;
            $end_date=$date;
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
            $query = DutySlot::query();
            $recordsTotal = $query->count();
            if ($searchKey) {
                $query->where(function ($query) use ($searchKey) {
                    $query->orWhere('slot_name', 'like', "%{$searchKey}%");
                });
            }
            // ORDERS

            switch ($orderByColumn) {
                case 'slot_name':
                    $query->orderBy('slot_name', $orderByDirection);
                    break;
                case 'start_time':
                    $query->orderBy('start_time', $orderByDirection);
                    break;
                case 'threshold_time':
                    $query->orderBy('threshold_time', $orderByDirection);
                    break;
                case 'end_time':
                    $query->orderBy('end_time', $orderByDirection);
                    break;
                default:
                    // Handle unknown column
                    break;
            }

            $recordsFiltered = $query->get();  // Filtered Data to count
            $query->limit($limit)->offset($offset);

            $queryData = $query->get();
            $editPermission=userCan('duty-slot.edit');
            $deletePermission=userCan('duty-slot.delete');
            $queryDataJson = [];
            foreach ($queryData as $item) {
                $queryDataJson[] = [
                    'id' => $item->id,
                    'slot_name' => $item->slot_name ?? '',
                    'threshold_time' => $item->threshold_time ? date('h:i A', strtotime($item->threshold_time)) : '',
                    'start_time' => $item->start_time ? date('h:i A', strtotime($item->start_time)) : '',
                    'end_time' => $item->end_time ? date('h:i A', strtotime($item->end_time)) : '',
                    'editPermission'=>$editPermission,
                    'deletePermission'=>$deletePermission,
                ];
            }
            $request = [
                'status' => 1,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered->count(),
                'data' => $queryDataJson,
                'orderableColumns' => $orderByColumn . $orderByDirection
            ];
            return response()->json($request);
        }

        $trashedEmployees = Employee::onlyTrashed()->get();
        return view('duty-slots.index', compact('organizations', 'designations', 'departments', 'trashedEmployees'));
    }
    public function validateSingleData(Request $request)
    {
        if($request->ajax()){
            $dataContent=$request->a;
            $dataValue=$request->val;
            if(DutySlot::where('slot_name', $dataValue)->count()==0) $response = ['status' => 1,'msg' => 'Usable'];
            else $response = ['status' => 0,'msg' => 'This slot is already exists'];
            return response()->json($response);

        }
        abort(403);
    }
    public function store(DutySlotRequest $request){
        $slotName=$request->slot_name;
        $startTime=$request->start_time;
        $thresholdTime=$request->threshold_time;
        $endTime=$request->end_time;

        if(!$slotName){
            return back()->with('error', 'Slot Name is required!');
        }

        $query=New DutySlot();
        $query->slot_name=$slotName;
        $query->start_time=$startTime;
        $query->threshold_time=$thresholdTime;
        $query->end_time=$endTime;
        if($query->save()){
            return back()->with('success', 'Successfully Created!');
        }
    }

    public function edit(Request $request, $id)
    {
        if($request->ajax()){
            $dutySlot=DutySlot::find($id);
            if($dutySlot){
                $html=View::make('duty-slots.edit', compact('dutySlot'))->render();
                return response()->json(['status' => 1,'html'=>$html]);
            }else{
                return response()->json(['status' => 0,'msg'=>'Not Found']);
            }
        }
        abort(403);
    }

    public function update(DutySlotRequest $request, $id){
        $slotName=$request->slot_name;
        $startTime=$request->start_time;
        $thresholdTime=$request->threshold_time;
        $endTime=$request->end_time;

        if(!$slotName){
            return back()->with('error', 'Slot Name is required!');
        }

        $query=DutySlot::find($id);
        if($query){
            $query->slot_name=$slotName;
            $query->start_time=$startTime;
            $query->threshold_time=$thresholdTime;
            $query->end_time=$endTime;
            if($query->save()){
                return back()->with('success', 'Successfully Updated!');
            }else{
                return back()->with('error', 'Server Error!');
            }
        }else{
            return back()->with('error', 'Not Found!');
        }

    }
    public function delete($id)
    {
        if(!userCan('duty-slot.delete')) return back()->with('error', 'Unauthorized Access!');
        $dutySlot=DutySlot::find($id);
        $empCount=$dutySlot->employee->count();
        if($empCount>0){
            return back()->with('error', "Can not be Deleted, $empCount employees are tagged.");
        }
        if($dutySlot->delete()){
            return back()->with('success', 'Duty Slot deleted successfully.');
        }
        return back()->with('error', 'Sever Error!');
    }
}
