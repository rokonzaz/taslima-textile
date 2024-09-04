<form action="{{route('request-approvals.submitApproval', ['id'=>$employeeRequest->id])}}" method="post" onsubmit="return validateRequestApproval('{{$employeeRequest->request_type}}')">
    @csrf
    <div class="p-3 overflow-y-scroll max-h-[70vh]">
        @php $employee=$employeeRequest->employee @endphp
        @if($employee)
            <x-details.employee-details :data="$employee"></x-details.employee-details>
            <hr>
        @endif
        @if($employeeRequest->request_type=='late-arrival' || $employeeRequest->request_type=='early-exit')
            <div class="my-2">
                <p><span class="font-medium">Date:</span> {{formatCarbonDate($employeeRequest->date)}}</p>
                <p><span class="font-medium">Time:</span> {{formatCarbonDate($employeeRequest->time, 'time')}}</p>
                <p><span class="font-medium">Reason:</span> {{$employeeRequest->reason}}</p>
                <p><span class="font-medium">Note:</span>
                    @if($employeeRequest->note)
                        <span class="underline">{{$employeeRequest->note}}</span>
                    @else
                        N/A
                    @endif
                </p>
            </div>

        @endif
        @if($employeeRequest->line_manager_approved_by)
            <div class="border rounded p-2 text-sm relative mt-4">
                <p class="absolute top-[-12px] left-3">
                    <span class="bg-white px-1">
                        Approved By Line Manager
                        @if(isset($employeeRequest->lineManagerApproved->full_name))
                            <span class="text-teal-600">({{$employeeRequest->lineManagerApproved->full_name}})</span>
                        @endif
                    </span>
                </p>
                @if($employeeRequest->line_manager_approval_note)
                    <p><span class="font-medium">Note:</span> {{$employeeRequest->line_manager_approval_note}}</p>
                @else
                    <p><span class="font-medium">Note:</span> N/A</p>
                @endif
                @if($employeeRequest->line_manager_approved_at)
                    <p class="text-teal-600 text-xs"><i class="ti ti-clock"></i> {{formatCarbonDate($employeeRequest->line_manager_approved_at, 'datetime')}}</p>
                @endif
            </div>
        @endif
        {{--@if($employeeRequest->supervisor_approved_by)
            <div class="border rounded p-2 text-sm relative mt-4">
                <p class="absolute top-[-12px] left-3">
                    <span class="bg-white px-1">
                        Approved By Line Manager
                        @if(isset($employeeRequest->departmentHeadApproved->full_name))
                            <span class="text-teal-600">({{$employeeRequest->departmentHeadApproved->full_name}})</span>
                        @endif
                    </span>
                </p>
                @if($employeeRequest->supervisor_approval_note)
                    <p><span class="font-medium">Note:</span> {{$employeeRequest->supervisor_approval_note}}</p>
                @else
                    <p><span class="font-medium">Note:</span> N/A</p>
                @endif
                @if($employeeRequest->supervisor_approved_at)
                    <p class="text-teal-600">at {{formatCarbonDate($employeeRequest->supervisor_approved_at, 'datetime')}}</p>
                @endif

            </div>
        @endif--}}
        <div class="">
            <label for="" class="inputLabel">Note:</label>
            <textarea name="remarks" id="remarks" class="inputField" placeholder="keep a note..."></textarea>
        </div>
    </div>
    <div class="sticky bottom-0 z-50 bg-gray-100">
        <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
            <div class="">
                @if($approvalType=='approve')
                    <button id="e_submitBtn_approved" type="submit" name="approve" value="2" class="submit-button-green">
                        Approve
                    </button>
                @endif
                @if($approvalType=='reject')
                    <button id="e_submitBtn_rejected" type="submit" name="reject" value="3" class="submit-button !bg-neutral-800">
                        Reject
                    </button>
                @endif
            </div>
        </div>
    </div>
</form>
