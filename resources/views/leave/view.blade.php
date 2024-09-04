<div class="overflow-y-scroll max-h-[70vh]">
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
            <h3 class="text-xl text-center font-medium p-2 text-white">{{$leave->requisition_type===1 ? 'Leave in Advance' : 'Leave of Absence'}}</h3>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 lg:gap-4 mt-2">
                <div class="flex flex-col sm:flex-row items-center gap-6 print:gap-0">
                    <div class="">
                        @php
                            $defaultProfileImage=employeeDefaultProfileImage($leave->employee->gender);
                            $profile_img=employeeProfileImage($leave->employee->emp_id, $leave->employee->profile_photo);
                        @endphp
                        <figure class="w-24 aspect-square object-cover rounded-full overflow-hidden print:w-16">
                            <img class="w-full h-full object-cover" src="{{$profile_img}}" onerror="this.onerror=null;this.src='{{$defaultProfileImage}}';" alt="{{$employee->name ??''}}"/>
                        </figure>
                    </div>
                    <div class="flex flex-col print:grow">
                        <span class="font-bold flex items-center gap-2"><i class="ti ti-hash"></i> {{$leave->employee->emp_id ?? ''}}</span>
                        <span class="font-bold flex items-center gap-2"><i class="ti ti-user"></i> {{$leave->employee->full_name ?? ''}}</span>
                        <span class="flex items-center gap-2"><span class="font-medium"><i class="ti ti-at"></i> </span> {{$leave->employee->email ??( $leave->employee->personal_email ?? 'N/A')}}</span>
                        <span class="flex items-center gap-2"><span class="font-medium"><i class="ti ti-phone"></i> </span>  {{$leave->employee->phone ?? ''}}</span>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="flex items-start gap-2"><span class="font-medium">Organization:</span> {{$leave->employee->empOrganization->name ?? ''}}</span>
                    <span class="flex items-start gap-2"><span class="font-medium">Department:</span> {{$leave->employee->empDepartment->name ?? ''}}</span>
                    <span class="flex items-start gap-2"><span class="font-medium">Designation:</span> {{$leave->employee->empDesignation->name ?? ''}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid sm:grid-cols-2 grid-cols-1">
        <div class="col-span-1">
            <div class=""> <span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Leave Details</span></div>
            <div class="h-[1px] bg-neutral-300"></div>
            <div class="p-4">
                <p><span class="font-medium">Leave Type:</span> {{$leave->leaveType->name}}</p>
                <p><span class="font-medium">Start Date:</span> {{date('d M Y', strtotime($leave->start_date))}}</p>
                <p><span class="font-medium">End Date:</span> {{date('d M Y', strtotime($leave->end_date))}}</p>
                <p><span class="font-medium">Total Days:</span> {{\Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1}}</p>
            </div>
        </div>
        <div class="col-span-1">
            <div class=""><span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Reliever Details</span></div>
            <div class="h-[1px] bg-neutral-300"></div>
            <div class="p-4">
                @if($reliever)
                    <p class="font-bold flex items-center gap-2"><i class="ti ti-hash"></i> {{$reliever->emp_id ?? ''}}</p>
                    <p class="font-bold flex items-center gap-2"><i class="ti ti-user"></i> {{$reliever->full_name ?? ''}}</p>
                    <p class="flex items-center gap-2"><span class="font-medium"><i class="ti ti-at"></i> </span> {{$reliever->email ??( $reliever->personal_email ?? 'N/A')}}</p>
                    <p class="flex items-center gap-2"><span class="font-medium"><i class="ti ti-phone"></i> </span>  {{$reliever->phone ?? ''}}</p>
                @else
                    N/A
                @endif
            </div>
        </div>
    </div>
    <div class="grid sm:grid-cols-2 grid-cols-1">
        <div class="col-span-1">
            <div class=""> <span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Leave Reason</span></div>
            <div class="h-[1px] bg-neutral-300"></div>
            <p class="font-medium p-4"> {{$leave->leave_reason ?? 'N/A'}}</p>
        </div>
        <div class="col-span-1 grid grid-cols-2">
            <div class="">
                <div class=""><span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Line Manager Remarks</span></div>
                <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                <p class="font-medium print:my-4 m-4 line-clamp-3"> {{$leave->line_manager_approval_note!='' ? $leave->line_manager_approval_note : 'N/A'}}</p>
            </div>
            <div class="">
                <div class=""><span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Department Head Remarks</span></div>
                <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                <p class="font-medium print:my-4 m-4 line-clamp-3"> {{$leave->department_head_approval_note!='' ? $leave->department_head_approval_note : 'N/A'}}</p>
            </div>
        </div>
    </div>

    <div class="flex items-end">
        <div class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg">Leave Approval</div>
        <div class="grow h-[1px] bg-neutral-300"></div>
    </div>
    <div class="p-4">
        <div class="grid grid-cols-3">
            <div class="col-span-2">
                <p><span class="font-medium">Status:</span> <span class="font-semibold {{ $textColor }}">{{$leave->approvalStatus->name ??''}}</span></p>
                <div class="flex justify-between gap-10 shrink">
                    @if($leave->approval_status==3)
                        <div class="">
                            @php $rejectedBy=$leave->rejectedBy; @endphp
                            @if($rejectedBy)
                                <p class="font-medium underline">Rejected By:</p>
                                <p class="font-semibold italic flex items-center gap-2"><i class="ti ti-user"></i> {{$rejectedBy->full_name ??''}} ({{$rejectedBy->emp_id ??''}})</p>
                                <p class="font-medium italic flex items-center gap-2"><i class="ti ti-at"></i> {{$rejectedBy->email ??''}}</p>
                                <div class="italic flex gap-2"><i class="ti ti-notes mt-1"></i> <p><span class="font-medium">Note:</span>{{$leave->rejected_note ??'N/A'}}</p></div>
                            @endif
                        </div>
                    @else
                        <div class="">
                            @php $approvedBy=$leave->lineManagerApproved; @endphp
                            @if($approvedBy)
                                <p class="font-medium underline">Approved By Line Manager</p>
                                <p class="font-semibold italic flex items-center gap-2"><i class="ti ti-user"></i> {{$approvedBy->full_name ??''}} ({{$approvedBy->emp_id ??''}})</p>
                                <p class="font-medium italic flex items-center gap-2"><i class="ti ti-at"></i> {{$approvedBy->email ??''}}</p>
                                <div class="italic flex gap-2"><i class="ti ti-notes mt-1"></i> <p><span class="font-medium">Note:</span>{{$leave->line_manager_approval_note ??'N/A'}}</p></div>
                            @endif
                        </div>
                        <div class="">
                            @php $approvedBy=$leave->departmentHeadApproved; @endphp
                            @if($approvedBy)
                                <p class="font-medium underline">Approved By Department Head</p>
                                <p class="font-semibold italic flex items-center gap-2"><i class="ti ti-user"></i> {{$approvedBy->full_name ??''}} ({{$approvedBy->emp_id ??''}})</p>
                                <p class="font-medium italic flex items-center gap-2"><i class="ti ti-at"></i> {{$approvedBy->email ??''}}</p>
                                <div class="italic flex gap-2"><i class="ti ti-notes mt-1"></i> <p><span class="font-medium">Note:</span>{{$leave->department_head_approval_note ??'N/A'}}</p></div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="">
                @if($leave->approval_status==2)
                    <div class="flex items-center justify-center">
                        <figure class="size-32 aspect-square">
                            <img src="{{asset('assets/img/approved.png')}}" class="w-full" alt="">
                        </figure>
                    </div>
                @elseif($leave->approval_status==3)
                    <div class="flex items-center justify-center">
                        <figure class="size-32 aspect-square">
                            <img src="{{asset('assets/img/rejected.png')}}" class="w-full" alt="">
                        </figure>
                    </div>
                @endif
            </div>
        </div>


    </div>
</div>
