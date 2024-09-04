<?php

namespace App\Http\Controllers;

use App\Http\Requests\HolidayRequest;
use App\Models\Holiday;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\View;

class HolidayController extends Controller
{
    public function create(){
        return view("settings.holiday.create");
    }
//    public function index(){
//        $holidays = Holiday::all();
//        return view("settings.holiday.index", compact('holidays'));
//    }


    public function index(Request $request)
    {
        $holiday=Holiday::get();
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
            $query = Holiday::query();
            $recordsTotal = $query->count();
            if ($searchKey) {
                $query->where(function ($query) use ($searchKey) {
                    $query->orWhere('name', 'like', "%{$searchKey}%");
                    $query->orWhereIn('id', function ($subquery) use ($searchKey) {
                        $subquery->select('id')->from(with(new Holiday())->getTable())->where('name', 'like', "%$searchKey%");
                    });
                });
            }
            // ORDERS

            switch ($orderByColumn) {
                case 'name':
                    $query->orderBy('name', $orderByDirection);
                    break;
                case 'start_date':
                    $query->orderBy('start_date', $orderByDirection);
                    break;
                case 'end_date':
                    $query->orderBy('end_date', $orderByDirection);
                    break;
                case 'total_days':
                    $query->orderBy('total_days', $orderByDirection);
                    break;
                default:
                    $query->orderBy('start_date', 'desc');
                    break;
            }

            $recordsFiltered = $query->get();
            $query->limit($limit)->offset($offset);

            $queryData = $query->get();

            $queryDataJson = [];
            foreach ($queryData as $item) {
                $queryDataJson[] = [
                    'id' => $item->id,
                    'name' => $item->name ?? '',
                    'start_date' => formatCarbonDate($item->start_date),
                    'end_date' => formatCarbonDate($item->end_date),
                    'total_days' => $item->total_days,
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

        return view('settings.holiday.index', compact('holiday'));
    }




    public function store(HolidayRequest $request){
        $holiday=new Holiday();
        $holiday->name=$request->holiday_name;
        $holiday->start_date=$request->start_date;
        $holiday->end_date=$request->end_date;
        $start_date = new DateTime($holiday->start_date);
        $end_date = new DateTime($holiday->end_date);
        $daysDiff = $end_date->diff($start_date)->days + 1;
        $holiday->total_days = $daysDiff;
        if($holiday->save())return back()->with('success', 'Holiday created successfully');
        else return back()->with('error', 'Server Error!');
    }

    public function edit(Request $request, $id)
    {
        if($request->ajax()){
            $holiday=Holiday::find($id);
            if($holiday){
                $html=View::make('settings.holiday.edit', compact('holiday'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                ];
                return response()->json($response);
            }
            return response()->json(['status'=>0,'html'=>'Not Found!']);

        }
        abort(403);
        return view('holiday.edit', compact('holiday'));
    }

    public function update(HolidayRequest $request, $id){
        $holiday = Holiday::find($id);
        $holiday->name = $request->holiday_name;
        $holiday->start_date = $request->start_date;
        $holiday->end_date = $request->end_date;
        $start_date = new DateTime($holiday->start_date);
        $end_date = new DateTime($holiday->end_date);
        $daysDiff = $end_date->diff($start_date)->days + 1;
        $holiday->total_days = $daysDiff;
        $holiday->save();
        if($holiday->save())return back()->with('success', 'Holiday Updated successfully');
        else return back()->with('error', 'Server Error!');
    }

    public function delete($id){
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return back()->with('success', 'Holiday deleted successfully');
    }

}
