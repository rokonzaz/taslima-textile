<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ZkTeco\TADFactory;
use App\Mail\Mailing;
use App\Mail\NewUserMail;
use App\Models\Attendance;
use App\Models\Organization;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\DutySlot;
use App\Models\DutySlotRule;
use App\Models\Employee;
use App\Models\EmployeeDutySlot;
use App\Models\EmployeeRequest;
use App\Models\ImportLog;
use App\Models\Leave;
use App\Models\ApprovalStatus;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Facades\Excel;
class TestCntroller extends Controller
{
     public function index()
    {
        $employeeRequest=EmployeeRequest::find(45);
        return $employeeRequest->linaManagerApproved;
        $leave=Leave::find(10);
        $mailData= $leave;
        $mailData->approvalPermission=1;
        $userInfo=['leave_id'=>$leave->id,
            'emp_id'=>'I-1031',
            'approval-permission'=>1
        ];
        $previewUrl=route('leaveRequest.email-preview', ['id'=>tripleBase64Encode(json_encode($userInfo))]);
        return view('leave.mail.leave-request', compact('mailData', 'previewUrl'));

    }
    public function makeJson($data)
    {
        $xmlString = <<<XML
        $data
        XML;
        $xml = simplexml_load_string($xmlString);
        $xmlArray = json_decode(json_encode($xml), true);
        return $xmlArray['Row'];
    }
    public function import(){




        // view('test.import-component');
    }
    public function tabs(){
        return view('test.tablist');
    }
    public function layout(){
        return view('test.layout');
    }
}
