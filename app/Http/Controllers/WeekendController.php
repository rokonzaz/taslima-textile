<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommonRequest;
use App\Models\Weekend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class WeekendController extends Controller
{

    public function index(Request $request)
    {
        $weekend=Weekend::get()->pluck('days');
        return view('settings.weekend.index', compact('weekend'));
    }

    public function store(CommonRequest $request){
        $data=[];
        Weekend::truncate();
        if($request->weekends) {
            foreach ($request->weekends as $item) {
                $data[] = [
                    'days' => $item,
                ];
            }
            if(Weekend::insert($data)) return back()->with('success', 'Weekends created successfully');
            else return back()->with('error', 'Server Error!');
        }
        return back()->with('success', 'Successfully Updated');

    }

}
