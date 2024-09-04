<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoticeBoardRequest;
use App\Mail\Mailing;
use App\Models\Designations;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use App\Models\Departments;
use App\Models\Organization;
use App\Models\Employee;



class NoticeController extends Controller
{
    public function create(){
        return view("notice.create");
    }
    public function index(){
        $notice = Notice::orderBy('created_at', 'asc')->get();
        return view("notice.index", compact("notice"));
    }

    public function ajaxNotice(Request $request) {
        if ($request->ajax()) {
            $searchKey = $request->search['value'] ?? '';
            $noticeType = $request->notice_type;
            $noticeBy = $request->notice_status;
            $date = $request->date;
            $limit = $request->length;
            $offset = $request->start;

            // Orders
            $orderByColumn = 'id';
            $orderByDirection = 'asc';

            // Check if the order array exists in the request and has at least one item
            if (isset($request->order) && count($request->order) > 0) {
                $firstOrderItem = $request->order[0];
                $orderByColumn = $request->columns[$firstOrderItem['column']]['name'] ?? 'id';
                $orderByDirection = $firstOrderItem['dir'] ?? 'asc';
            }

            $query = Notice::query();
            $recordsTotal = $query->count();

            if ($searchKey) {
                $query->where(function ($query) use ($searchKey) {
                    $query->orWhere('notice_type', 'like', "%{$searchKey}%")
                          ->orWhere('notice_by', 'like', "%{$searchKey}%")
                          ->orWhere('notice_description', 'like', "%{$searchKey}%");
                });
            }

            if ($noticeType) {
                $query->where('notice_type', $noticeType);
            }

            if ($noticeBy) {
                $query->where('notice_by', $noticeBy);
            }

            if ($date) {
                $query->whereDate('notice_date', $date);
            }

            switch ($orderByColumn) {
                case 'notice_type':
                    $query->orderBy('notice_type', $orderByDirection);
                    break;
                case 'notice_date':
                    $query->orderBy('notice_date', $orderByDirection);
                    break;
                case 'notice_description':
                    $query->orderBy('notice_description', $orderByDirection);
                    break;
                case 'notice_by':
                    $query->orderBy('notice_by', $orderByDirection);
                    break;
                default:
                    $query->orderBy('id', $orderByDirection);
                    break;
            }

            $recordsFiltered = $query->count();
            $query->limit($limit)->offset($offset);
            $notices = $query->get();

            $noticeData = [];
            foreach ($notices as $item) {
                $noticeData[] = [
                    'id' => $item->id,
                    'notice_type' => $item->notice_type,
                    'notice_date' => $item->notice_date,
                    'notice_description' => $item->notice_description,
                    'notice_file' => $item->notice_file,
                    'notice_by' => $item->notice_by,
                ];
            }

            $response = [
                'status' => 1,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $noticeData,
            ];

            return response()->json($response);
        }
    }

    public function store(NoticeBoardRequest $request)
    {

        $notice = new Notice();
        $notice->notice_type = $request->notice_type;
        $notice->notice_date = $request->notice_date;
        $notice->notice_description = $request->notice_description;
        $notice->notice_by = $request->notice_by;
        if($request->has('notice_file')){
            $attachments=$request->file('notice_file');
            if ($attachments) {
                $noticeFileName = time() . '_' . $attachments->getClientOriginalName();
                $uploadPath="uploads/notice_file";
                $fileUrl="uploads/notice_file/".$noticeFileName;
                $attachments->move(public_path($uploadPath), $noticeFileName);
                $notice->notice_file = $fileUrl;
            }
        }
        if($notice->save()){
            return redirect()->route('notice.index')->with('success', 'Notice stored successfully');
        }
        return redirect()->route('notice.index')->with('error', 'Error Occurred!');

    }


    public function edit(Request $request, $id)
    {
        if($request->ajax()){
            $notice=Notice::find($id);
            if($notice){
                $html=View::make('notice.edit', compact('notice'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                ];
                return response()->json($response);
            }
            return response()->json(['status'=>0,'html'=>'Not Found!']);

        }
        abort(403);
        return view('notice.edit', compact('notice'));
    }


    public function update(NoticeBoardRequest $request, $id)
    {
        $notice = Notice::find($id);

        $filePath = null;

        if ($request->hasFile('notice_file')) {
            $file = $request->file('notice_file');
            $noticeFileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('noticefile'), $noticeFileName);
            $filePath = 'noticefile/' . $noticeFileName;

            // Remove the old notice file if it exists
            if ($notice->notice_file) {
                $oldFilePath = public_path($notice->notice_file);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
        }

        $notice->notice_type = $request->notice_type;
        $notice->notice_date = $request->notice_date;
        $notice->notice_description = $request->notice_description;
        $notice->notice_file = $filePath;
        $notice->notice_by = $request->notice_by;

        if ($notice->save()) {
            return back()->with('success', 'Notice updated successfully');
        } else {
            return back()->with('error', 'Server Error!');
        }
    }


    public function delete($id)
    {
        $notice = Notice::findOrFail($id);
        $notice->delete();

        return back()->with('success', 'Notice deleted successfully');
    }



    /* public function sendNotice(Request $request, $id)
    {
        $departments = Departments::all();
        $organizations = Organization::get();
        $notice=Notice::find($id);
        $employees = Employee::all();

        if($request->ajax()){
            if($notice){
                $html=View::make('notice.send', compact('notice', 'departments', 'organizations','employees'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                ];
                return response()->json($response);
            }
            return response()->json(['status'=>0,'html'=>'Not Found!']);
        }
       abort(403);
        // return view('notice.send', compact('notice','departments','organizations'));
    } */

    public function sendNotice(Request $request, $id)
    {
        $departments = Departments::all();
        $organizations = Organization::get();
        $notice = Notice::find($id);
        $employees = Employee::all();
        // Handling AJAX request
        if ($request->ajax()) {

            if ($notice) {
                $html = View::make('notice.send', compact('notice', 'departments', 'organizations', 'employees'))->render();
                $response = [
                    'status' => 1,
                    'html' => $html,
                ];
                return response()->json($response);
            }
            return response()->json(['status' => 0, 'html' => 'Not Found!']);
        }

        abort(403);
    }


    public function sendNoticeList(Request $request, $id)
    {
        $request->validate([
            'emp_id' => 'required|array',
            'emp_id.*' => 'exists:employees,id'
        ]);

        $employeeIds = $request->emp_id;
        $employees = Employee::whereIn('id', $employeeIds)->get();

        $emp_emails = $employees->map(function ($employee) {
            return $employee->email ?? $employee->personal_email;
        })->filter()->toArray();

        if (count($emp_emails) > 0) {
            $notice = Notice::findOrFail($id);
            $filePath = public_path($notice->notice_file);

            if ($notice->notice_file && !file_exists($filePath)) {
                return redirect()->route('notice.index')->with('error', 'Notice file not found.');
            }

            $email = new Mailing($notice);
            $email->subject = $notice->notice_type;
            $email->type = "notice-mail";

            if ($notice->notice_file && file_exists($filePath)) {
                $email->attach($filePath);
            }

            Mail::to(array_shift($emp_emails))
                ->cc($emp_emails)
                ->send($email);

                return back()->with('success', 'Notice send successfully');
        }

        return response()->json(['status' => 0, 'msg' => 'No employees found or no valid email addresses.']);
    }

}
