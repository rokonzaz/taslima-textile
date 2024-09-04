<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ZkTeco\TADFactory;
use App\Models\Departments;
use App\Models\LeaveType;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\ZkTeco\Exceptions\ConnectionError;
use App\Http\Requests\CommonRequest;
use Illuminate\Support\Facades\View;

class SettingController extends Controller
{
    public function index(){
        return view('settings.index');
    }
    public function fingerprintMachine()
    {
        $deviceIp = Setting::where('name', 'fingerprint')
            ->where('title', 'device-ip')
            ->value('ip');
        $isConnected=false;
        $tad_factory = new TADFactory(['ip' => $deviceIp]);
        $tad = $tad_factory->get_instance();
        $deviceDate='';
        try {
            $deviceDate=$tad->get_date();
            $deviceDate=(new AttendanceController())->makeJson($deviceDate);
            $isConnected=true;
            $status=$tad->is_device_online($deviceIp);
            $serialNumber=$tad->get_com_key();
            $description=$tad->get_description();
            $produceDate='';
        } catch (ConnectionError $e) {

        }
        return view('settings.fingerprint-machine.index', compact('deviceIp', 'isConnected', 'deviceDate', 'status', 'serialNumber', 'produceDate', 'description'));
    }
    public function fingerprintMachineUpdate(CommonRequest $request)
    {
        $machineIp=Setting::where('name', 'fingerprint')
            ->where('title', 'device-ip')->first();

        if($machineIp){
            $machineIp->ip=$request->device_ip;
        }else{
            $machineIp=new Setting();
            $machineIp->name='fingerprint';
            $machineIp->title='device-ip';
            $machineIp->ip=$request->device_ip;
        }

        if ($machineIp->save()) {
            return back()->with('success', 'Successfully Updated');
        } else {
            return back()->with('error', 'Error Occurred!');
        }
    }

    public function leaveType(Request $request)
    {
        if($request->ajax()){
            $a=$request->a;
            $id=$request->id;
            if($a=='edit'){
                $leaveType=LeaveType::find($id);
                if($leaveType){
                    $html=View::make('settings.leave-type.edit', compact('leaveType'))->render();
                    return response()->json(['status' => 1,'html'=>$html]);
                }else{
                    return response()->json(['status' => 0,'msg'=>'Not Found']);
                }
            }
        }
        $leaveTypes=LeaveType::get();
        return view('settings.leave-type.index', compact('leaveTypes'));
    }
    public function leaveTypeUpdate(Request $request, $id)
    {
        $leaveType=LeaveType::find($id);
        $leaveType->remarks=$request->remarks;
        if($leaveType->save()) return back()->with('success', "Successfully Updated");
        else return back()->with('error', "Server Error");
    }
    public function department(Request $request)
    {
        if($request->ajax()){
            $a=$request->a;
            $id=$request->id;
            if($a=='edit'){
                $department=Departments::find($id);
                if($department){
                    $html=View::make('settings.department.edit', compact('department'))->render();
                    return response()->json(['status' => 1,'html'=>$html]);
                }else{
                    return response()->json(['status' => 0,'msg'=>'Not Found']);
                }
            }
            return response()->json(['status' => 0,'msg'=>'Error']);
        }
        $department=Departments::get();
        return view('settings.department.index', compact('department'));
    }
    public function departmentUpdate(Request $request, $id)
    {
        $query=Departments::find($id);
        $query->name=$request->name;
        if($query->save()) return back()->with('success', "Successfully Updated");
        else return back()->with('error', "Server Error");
    }

}
