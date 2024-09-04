@extends('layouts.no-layout')
@section('title', 'Preview Leave Request')
@section('content')
    <div class="bg-white py-4 shadow-md">
        <div class="px-24">
            <div class="flex justify-between items-center">
                <div class="text-xl">NexHRM</div>
                <div class='flex items-center'>
                    <div class="border w-fit rounded-xl shadow-sm">
                        <button class="px-4 py-2 rounded-l-xl text-white m-0 bg-red-500 hover:bg-[#831b94] transition">Login</button><button class="px-4 py-2 rounded-r-xl bg-neutral-50 hover:bg-neutral-100 transition">Register</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="px-24 pt-2 pb-10">
        <x-containers.container-box data="!p-0 overflow-hidden print:border print:border-gray-300 print:text-xs print:align-middle">
            @php
                $editPermission=true;

                if(getUserRole() == 'employee') {
                    if(in_array($leave->approval_status, [2,3])){
                        $editPermission=false;
                    }
                }
                if($leave->approval_status==1) {$bgColor="bg-warning"; $textColor='text-yellow-600';}
                if($leave->approval_status==2) {$bgColor="bg-teal-600"; $textColor='text-teal-600';}
                if($leave->approval_status==3) {$bgColor="bg-[#831b94]"; $textColor='text-[#831b94]';}

            @endphp

            <div class="">
                <div class="{{$bgColor}}">
                    <h3 class="text-xl text-center font-medium p-2 text-white">{{$leave->requisition_type===1 ? 'Leave in Advance' : 'Leave of Absence'}} Request</h3>
                </div>
                <div class="p-4 print:p-2 print:my-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 lg:gap-4 mt-2 justify-between">
                        <div class="flex flex-col sm:flex-row items-center gap-6 print:gap-2">
                            <div class="">
                                @php
                                    $defaultProfileImage=employeeDefaultProfileImage($leave->employee->gender);
                                    $profile_img=employeeProfileImage($leave->employee->emp_id, $leave->employee->profile_photo);
                                @endphp
                                <figure class="w-24 aspect-square object-cover rounded-full overflow-hidden print:w-16">
                                    <img class="w-full h-full object-cover" src="{{$profile_img}}" onerror="this.onerror=null;this.src='{{$defaultProfileImage}}';" alt="{{$employee->name ??''}}"/>
                                </figure>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold flex items-center gap-2 print:gap-1"><i class="ti ti-hash"></i> {{$leave->employee->emp_id ?? ''}}</span>
                                <span class="font-bold flex items-center gap-2 print:gap-1"><i class="ti ti-user"></i> {{$leave->employee->full_name ?? ''}}</span>
                                <span class="flex items-center gap-2 print:gap-1 truncate"><span class="font-medium"><i class="ti ti-at"></i> </span> {{$leave->employee->email ??( $leave->employee->personal_email ?? 'N/A')}}</span>
                                <span class="flex items-center gap-2 print:gap-1"><span class="font-medium"><i class="ti ti-phone"></i> </span>  {{$leave->employee->phone ?? ''}}</span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="flex items-start gap-2 print:gap-1"><span class="font-medium">Organization:</span> {{$leave->employee->empOrganization->name ?? ''}}</span>
                            <span class="flex items-start gap-2 print:gap-1"><span class="font-medium">Department:</span> {{$leave->employee->empDepartment->name ?? ''}}</span>
                            <span class="flex items-start gap-2 print:gap-1"><span class="font-medium">Designation:</span> {{$leave->employee->empDesignation->name ?? ''}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1">
                <div class="col-span-1">
                    <div class=""> <span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Leave Details</span></div>
                    <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                    <div class="p-4 print:my-5">
                        <p><span class="font-medium">Leave Type:</span> {{$leave->leaveType->name??''}}</p>
                        <p><span class="font-medium">Start Date:</span> {{date('d M Y', strtotime($leave->start_date))}}</p>
                        <p><span class="font-medium">End Date:</span> {{date('d M Y', strtotime($leave->end_date))}}</p>
                        <p><span class="font-medium">Total Days:</span> {{\Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1}}</p>
                    </div>
                </div>
                <div class="col-span-1">

                    <div class=""><span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Reliever Details</span></div>
                    <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                    <div class="p-4 print:my-5">
                        @if($reliever)
                            <p class="font-bold flex items-center gap-2"><i class="ti ti-hash"></i> {{$reliever->emp_id ?? ''}}</p>
                            <p class="font-bold flex items-center gap-2"><i class="ti ti-user"></i> {{$reliever->full_name ?? ''}}</p>
                            <p class="flex items-center gap-2"><span class="font-medium"><i class="ti ti-at"></i> </span> {{$reliever->email ??( $reliever->personal_email ?? 'N/A')}}</p>
                            <p class="flex items-center gap-2"><span class="font-medium"><i class="ti ti-phone"></i> </span>  {{$reliever->phone ?? ''}}</p>
                        @else
                            <p>N/A</p>
                        @endif

                    </div>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 ">
                <div class="col-span-1">
                    <div class=""> <span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Leave Reason</span></div>
                    <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                    <p class="font-medium p-4 print:my-5"> {{$leave->leave_reason ?? 'N/A'}}</p>
                </div>
                <div class="col-span-1">
                    <div class=""><span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Leave Remarks</span></div>
                    <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                    <p class="font-medium p-4 print:my-5"> {{$leave->remarks!='' ? $leave->remarks : 'N/A'}}</p>
                </div>
            </div>
            <div class="flex items-end">
                <div class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Leave Approval</div>
                <div class="grow h-[1px] mt-0.5 bg-neutral-300"></div>
            </div>

            <div class="p-4 py-2 print:my-2">

                <p class=""><span class="font-bold">Status:</span> <span class="font-semibold {{ $textColor }}">{{$leave->approvalStatus->name??''}}</span></p>
                <div class="grid grid-cols-1 sm:grid-cols-3 items-center">
                    <div class="col-span-2 flex justify-between gap-10 shrink">
                        @if($leave->approval_status==3)
                            <div class="">
                                @php $rejectedBy=$leave->rejectedBy; @endphp
                                @if($rejectedBy)
                                    <p class="font-medium underline">Rejected By:</p>
                                    <p class="font-semibold italic flex items-center gap-2"><i class="ti ti-user"></i> {{$rejectedBy->full_name ??''}} ({{$rejectedBy->emp_id ??''}})</p>
                                    <p class="font-medium italic flex items-center gap-2"><i class="ti ti-at"></i> {{$rejectedBy->email ??''}}</p>
                                    <div class="italic flex gap-2"><i class="ti ti-notes mt-1"></i> <p><span class="font-medium">Note:</span>{{$leave->rejected_note ??''}}</p></div>
                                @endif
                            </div>
                        @else
                            <div class="">
                                @php $approvedBy=$leave->lineManagerApproved; @endphp
                                @if($approvedBy)
                                    <p class="font-medium underline">Approved By Line Manager</p>
                                    <p class="font-semibold italic flex items-center gap-2"><i class="ti ti-user"></i> {{$approvedBy->full_name ??''}} ({{$approvedBy->emp_id ??''}})</p>
                                    <p class="font-medium italic flex items-center gap-2"><i class="ti ti-at"></i> {{$approvedBy->email ??''}}</p>
                                    <div class="italic flex gap-2"><i class="ti ti-notes mt-1"></i> <p><span class="font-medium">Note:</span>{{$leave->line_manager_approval_note ??''}}</p></div>
                                @endif
                            </div>
                            <div class="">
                                @php $approvedBy=$leave->departmentHeadApproved; @endphp
                                @if($approvedBy)
                                    <p class="font-medium underline">Approved By Department Head</p>
                                    <p class="font-semibold italic flex items-center gap-2"><i class="ti ti-user"></i> {{$approvedBy->full_name ??''}} ({{$approvedBy->emp_id ??''}})</p>
                                    <p class="font-medium italic flex items-center gap-2"><i class="ti ti-at"></i> {{$approvedBy->email ??''}}</p>
                                    <div class="italic flex gap-2"><i class="ti ti-notes mt-1"></i> <p><span class="font-medium">Note:</span>{{$leave->department_head_approval_note ??''}}</p></div>
                                @endif
                            </div>
                        @endif

                    </div>
                    <div class="">
                        @if($leave->approval_status==2)
                            <div class="flex items-center justify-center">
                                <figure class="size-32 print:size-24 aspect-square">
                                    <img src="{{asset('assets/img/approved.png')}}" class="w-full" alt="">
                                </figure>
                            </div>
                        @elseif($leave->approval_status==3)
                            <div class="flex items-center justify-center">
                                <figure class="size-32 print:size-24 aspect-square">
                                    <img src="{{asset('assets/img/rejected.png')}}" class="w-full" alt="">
                                </figure>
                            </div>
                        @endif
                    </div>
                </div>
            </div>



            {{--<div class="flex items-end hidden print:flex">
                <div class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Departmental Use Only</div>
                <hr class="grow h-[1px] bg-neutral-300 dark:bg-neutral-200 print:flex"></hr>
            </div>
            <div class="p-4 hidden print:block print:my-5">
                <div class="grid sm:grid-cols-2 grid-cols-1">
                    <div class="col-span-1 flex items-center gap-x-10 print:gap-6">
                        <p class="font-medium w-48 dark:text-neutral-100 print:w-36">Objections:</p>
                        <div class="flex items-center gap-6">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="shrink-0 border-gray-500 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-400 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-yes">
                                <label for="hs-yes" class="text-sm mt-0.5 text-gray-900 ms-3 dark:text-neutral-400 font-semibold print:ms-0 print:text-xs">Yes</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="shrink-0 border-gray-500 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-400 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-no">
                                <label for="hs-no" class="text-sm mt-0.5 text-gray-900 ms-3 dark:text-neutral-400 font-semibold print:ms-0 print:text-xs">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-1 flex items-center gap-x-10 print:gap-6">
                        <p class="font-medium w-48 dark:text-neutral-100 print:w-36">Leave Approval:</p>
                        <div class="flex items-center gap-6">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="shrink-0 border-gray-500 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-400 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-yes-l">
                                <label for="hs-yes-l" class="text-sm mt-0.5 text-gray-900 ms-3 dark:text-neutral-400 font-semibold print:ms-0 print:text-xs">Yes</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="shrink-0 border-gray-500 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-400 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-no-l">
                                <label for="hs-no-l" class="text-sm mt-0.5 text-gray-900 ms-3 dark:text-neutral-400 font-semibold print:ms-0 print:text-xs">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-1 mt-20">
                        <hr class="border-gray-800 border-dotted dark:border-white w-48">
                        <p class="font-medium min-w-48 dark:text-neutral-100">Signature of Line Manager</p>
                    </div>
                    <div class="col-span-1 mt-20">
                        <hr class="border-gray-800 border-dotted dark:border-white w-64">
                        <p class="font-medium min-w-48 dark:text-neutral-100">Signature of Departmental Head</p>
                    </div>
                </div>
            </div>--}}
        </x-containers.container-box>
        @if($leave->approval_status==1 && $reqInfo->approval_permission==1)
            @php
                $approvalPermission=true;
                if($reqInfo->approve_as=='line-manager' && $leave->lineManagerApproved) $approvalPermission=false;
                if($reqInfo->approve_as=='department-head'){
                    if($leave->lineManagerApproved){
                        if($leave->departmentHeadApproved){
                            $approvalPermission=false;
                        }
                    }else{
                        $approvalPermission=false;
                    }

                }
            @endphp
            @if($approvalPermission)
                <x-containers.container-box>
                    <div class="my-4">
                        <form action="{{route('leaveRequest.email-preview', ['id'=>request('id')])}}" method="post">
                            @csrf
                            <div class="flex items-center justify-center gap-4">
                                <label for="" class="inputLabel !mb-0">
                                    Note
                                </label>
                                <textarea type="text" id="" name="remarks" class="inputField !py-2.5 !mb-0" required></textarea>
                                <button class="submit-button-green" name="action" value="Approve">Approve</button>
                                <button class="submit-button" name="action" value="Reject">Reject</button>
                            </div>
                        </form>
                    </div>
                </x-containers.container-box>
            @endif

        @endif
        {{--@if($leave->approval_status==1)
            <x-containers.container-box>
                <div class="my-4">
                    <form action="{{route('leaveRequest.email-preview', ['id'=>request('id')])}}" method="post">
                        @csrf
                        <div class="flex items-center justify-center gap-4">
                            <label for="" class="inputLabel !mb-0">
                                Remarks
                            </label>
                            <textarea type="text" id="" name="remarks" class="inputField !py-2.5 !mb-0" required></textarea>
                            <button class="submit-button-green" name="action" value="Approve">Approve</button>
                            <button class="submit-button" name="action" value="Reject">Reject</button>
                        </div>
                    </form>
                </div>
            </x-containers.container-box>
        @endif--}}

    </div>

@endsection




