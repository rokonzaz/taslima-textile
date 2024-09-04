<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SampleController extends Controller
{
    public function index(Request $request)
    {
        if(!userCan('leave.edit')) return response()->json(['status' => 0,'msg'=>'Unauthorized Access!']);
        if(!userCan('employee.edit')) return back()->with('error', 'Unauthorized Access!');
        if(!userCan('employee.view')) return view('error-page.unauthorized');

        if($request->ajax()){

            $html=View::make('duty-slot-rules.edit', compact('dutySlotRule', 'dutySlots'))->render();
            $response=[
                'status'=>1,
                'html'=>$html,
            ];
            return response()->json($response);
        }
        abort(403);
    }
    public function create()
    {

    }
    public function store()
    {

    }
    public function edit()
    {

    }
    public function update()
    {

    }
    public function delete()
    {

    }
    public function forceDelete()
    {

    }
    public function restore()
    {

    }

}
