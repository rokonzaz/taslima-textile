@extends('layouts.no-layout')
@section('title', __($employeeRequest->request_type).' Request')
@section('content')
    <div class="bg-white py-4 shadow-md">
        <div class="px-24">
            <div class="flex justify-between items-center">
                <div class="text-xl">NexHRM</div>
                <div class='flex items-center'>
                    <div class="border w-fit rounded-xl shadow-sm">
                        <button class="px-4 py-2 rounded-l-xl text-white m-0 bg-red-500 hover:bg-[#831b94] transition">
                            Login
                        </button>
                        <button class="px-4 py-2 rounded-r-xl bg-neutral-50 hover:bg-neutral-100 transition">Register
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full md:w-2/3 mx-auto pt-2 pb-10">
        <x-containers.container-box
            data="!p-0 overflow-hidden print:border print:border-gray-300 print:text-xs print:align-middle">
            @php
                if($employeeRequest->approval_status==1) {$bgColor="bg-warning"; $textColor='text-yellow-600';}
                if($employeeRequest->approval_status==2) {$bgColor="bg-teal-600"; $textColor='text-teal-600';}
                if($employeeRequest->approval_status==3) {$bgColor="bg-[#831b94]"; $textColor='text-[#831b94]';}
            @endphp
            <div class="">
                <div class="{{$bgColor}}">
                    <h3 class="text-xl text-center font-medium p-2 text-white">{{__($employeeRequest->request_type)}}
                        Request</h3>
                </div>
                <div class="p-4 print:p-2 print:my-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 lg:gap-4 mt-2 justify-between">
                        <div class="flex flex-col sm:flex-row items-center gap-6 print:gap-2">
                            <div class="">
                                @php
                                    $defaultProfileImage=employeeDefaultProfileImage($employeeRequest->employee->gender);
                                    $profile_img=employeeProfileImage($employeeRequest->employee->emp_id, $employeeRequest->employee->profile_photo);
                                @endphp
                                <figure class="w-24 aspect-square object-cover rounded-full overflow-hidden print:w-16">
                                    <img class="w-full h-full object-cover" src="{{$profile_img}}"
                                         onerror="this.onerror=null;this.src='{{$defaultProfileImage}}';"
                                         alt="{{$employee->name ??''}}"/>
                                </figure>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold flex items-center gap-2 print:gap-1"><i class="ti ti-hash"></i> {{$employeeRequest->employee->emp_id ?? ''}}</span>
                                <span class="font-bold flex items-center gap-2 print:gap-1"><i class="ti ti-user"></i> {{$employeeRequest->employee->full_name ?? ''}}</span>
                                <span class="flex items-center gap-2 print:gap-1 truncate"><span class="font-medium"><i
                                            class="ti ti-at"></i> </span> {{$employeeRequest->employee->email ??( $employeeRequest->employee->personal_email ?? 'N/A')}}</span>
                                <span class="flex items-center gap-2 print:gap-1"><span class="font-medium"><i
                                            class="ti ti-phone"></i> </span>  {{$employeeRequest->employee->phone ?? ''}}</span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="flex items-start gap-2 print:gap-1"><span
                                    class="font-medium">Organization:</span> {{$employeeRequest->employee->empOrganization->name ?? ''}}</span>
                            <span class="flex items-start gap-2 print:gap-1"><span
                                    class="font-medium">Department:</span> {{$employeeRequest->employee->empDepartment->name ?? ''}}</span>
                            <span class="flex items-start gap-2 print:gap-1"><span
                                    class="font-medium">Designation:</span> {{$employeeRequest->employee->empDesignation->name ?? ''}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1">
                <div class="col-span-2">
                    <div class=""><span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4"> Details</span>
                    </div>
                    <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                    <div class="p-4 print:my-5">
                        @if(in_array($employeeRequest->request_type,['late-arrival','early-exit']))
                            <p>
                                <span class="font-medium">Date:</span> {{ formatCarbonDate($employeeRequest->date)}}
                            </p>
                            <p><span
                                    class="font-medium">Time:</span> {{formatCarbonDate($employeeRequest->time, 'time')}}
                            </p>
                            <p>
                                <span class="font-medium">Reason:</span> {{$employeeRequest->reason}}
                            </p>
                            @if($employeeRequest->reason!='')
                                <p>
                                    <span class="font-medium">Note:</span> {{$employeeRequest->note}}
                                </p>
                            @endif
                        @elseif($employeeRequest->request_type=='home-office')
                            <p>
                                <span class="font-medium">Start Date:</span> {{ formatCarbonDate($employeeRequest->start_date)}}
                            </p>
                            <p>
                                <span class="font-medium">End Date:</span> {{ formatCarbonDate($employeeRequest->end_date)}}
                            </p>
                            <p><span
                                    class="font-medium">Total days:</span> {{$employeeRequest->intended_days}} {{$employeeRequest->intended_days > 1 ? 'days' : 'day'}}
                            </p>
                            <p>
                                <span class="font-medium">Reason:</span> {{$employeeRequest->reason}}
                            </p>
                            @if($employeeRequest->reason!='')
                                <p>
                                    <span class="font-medium">Note:</span> {{$employeeRequest->note}}
                                </p>
                            @endif
                        @endif

                    </div>
                </div>
            </div>

            <div class="flex items-end">
                <div class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Approval</div>
                <div class="grow h-[1px] mt-0.5 bg-neutral-300"></div>
            </div>

            <div class="p-4 py-2 print:my-2">

                <p class=""><span class="font-bold">Status:</span> <span class="font-semibold {{ $textColor }}">{{$employeeRequest->approvalStatus->name??''}}</span></p>
                <div class="grid grid-cols-1 sm:grid-cols-3 items-center">
                    <div class="col-span-2 flex justify-between gap-10 shrink">
                        @if($employeeRequest->approval_status==3)
                            <div class="">
                                @php $rejectedBy=$employeeRequest->rejectedBy; @endphp
                                @if($rejectedBy)
                                    <p class="font-medium underline">Rejected By:</p>
                                    <p class="font-semibold italic flex items-center gap-2"><i class="ti ti-user"></i> {{$rejectedBy->full_name ??''}} ({{$rejectedBy->emp_id ??''}})</p>
                                    <p class="font-medium italic flex items-center gap-2"><i class="ti ti-at"></i> {{$rejectedBy->email ??''}}</p>
                                    <div class="italic flex gap-2"><i class="ti ti-notes mt-1"></i> <p><span class="font-medium">Note:</span>{{$employeeRequest->rejected_note ??''}}</p></div>
                                @endif
                            </div>
                        @else
                            <div class="">
                                @php $approvedBy=$employeeRequest->lineManagerApproved; @endphp
                                @if($approvedBy)
                                    <p class="font-medium underline">Approved By Line Manager</p>
                                    <p class="font-semibold italic flex items-center gap-2"><i class="ti ti-user"></i> {{$approvedBy->full_name ??''}} ({{$approvedBy->emp_id ??''}})</p>
                                    <p class="font-medium italic flex items-center gap-2"><i class="ti ti-at"></i> {{$approvedBy->email ??''}}</p>
                                    <div class="italic flex gap-2"><i class="ti ti-notes mt-1"></i> <p><span class="font-medium">Note:</span>{{$employeeRequest->line_manager_approval_note ??''}}</p></div>
                                @endif
                            </div>
                            <div class="">
                                @php $approvedBy=$employeeRequest->departmentHeadApproved; @endphp
                                @if($approvedBy)
                                    <p class="font-medium underline">Approved By Department Head</p>
                                    <p class="font-semibold italic flex items-center gap-2"><i class="ti ti-user"></i> {{$approvedBy->full_name ??''}} ({{$approvedBy->emp_id ??''}})</p>
                                    <p class="font-medium italic flex items-center gap-2"><i class="ti ti-at"></i> {{$approvedBy->email ??''}}</p>
                                    <div class="italic flex gap-2"><i class="ti ti-notes mt-1"></i> <p><span class="font-medium">Note:</span>{{$employeeRequest->department_head_approval_note ??''}}</p></div>
                                @endif
                            </div>
                        @endif

                    </div>
                    <div class="">
                        @if($employeeRequest->approval_status==2)
                            <div class="flex items-center justify-center">
                                <figure class="size-32 print:size-24 aspect-square">
                                    <img src="{{asset('assets/img/approved.png')}}" class="w-full" alt="">
                                </figure>
                            </div>
                        @elseif($employeeRequest->approval_status==3)
                            <div class="flex items-center justify-center">
                                <figure class="size-32 print:size-24 aspect-square">
                                    <img src="{{asset('assets/img/rejected.png')}}" class="w-full" alt="">
                                </figure>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-containers.container-box>
        @if($employeeRequest->approval_status==1 && $reqInfo->approval_permission==1)
            @php
                $approvalPermission=true;
                if($reqInfo->approve_as=='line-manager' && $employeeRequest->lineManagerApproved) $approvalPermission=false;
                if($reqInfo->approve_as=='department-head'){
                    if($employeeRequest->lineManagerApproved){
                        if($employeeRequest->departmentHeadApproved){
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
                        <form action="{{route('employeeRequest.email-preview', ['id'=>request('id')])}}" method="post">
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

    </div>

@endsection




