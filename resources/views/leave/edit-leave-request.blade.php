<form class="mb-0" action="{{ route('leaveRequest.update', ['id'=>$leave->id]) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateFormRequest('e_', 'leaveRequest', '{!!getUserRole() !== 'employee' ? 'management' : ''!!}')">
    @csrf
    @php
        $editPermission=true;
        $approvalPermission=(new \App\Http\Controllers\RequestApprovalsController())->getApprovalPermission($leave);

        if((getUserRole() !== 'employee' && in_array($leave->approval_status, [1,2,3])) || (getUserRole() == 'employee' && in_array($leave->approval_status, [2,3]))) {
            $editPermission=false;
        }

        $manageableEmployees=auth()->user()->manageableEmployees;
        $manageableEmployeesIDs = $manageableEmployees->pluck('emp_id')->toArray();
        $authEmployeeId=auth()->user()->emp_id;

        if($leave->approval_status==1) {$bgColor="bg-warning"; $textColor='text-yellow-600';}
        if($leave->approval_status==2) {$bgColor="bg-teal-600"; $textColor='text-teal-600';}
        if($leave->approval_status==3) {$bgColor="bg-[#831b94]"; $textColor='text-[#831b94]';}
    @endphp




    <div class="p-3 overflow-y-scroll max-h-[70vh]">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 lg:gap-4 mt-2">
            <div class="col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-y-2 gap-x-4 lg:gap-x-6">
                <div class="col-span-1">
                    {{-- <h1 class="text-lg font-semibold line-clamp-none mb-2 text-neautal-700 italic ml-2">Employee Detais:</h1> --}}
                    <div class="flex items-center gap-3">
                        <div class="">
                            @php
                                $defaultProfileImage=employeeDefaultProfileImage($leave->employee->gender);
                                $profile_img=employeeProfileImage($leave->employee->emp_id, $leave->employee->profile_photo);
                            @endphp
                            <figure class="w-12 aspect-square object-cover rounded-full overflow-hidden">
                                <img class="w-full h-full object-cover" src="{{$profile_img}}" onerror="this.onerror=null;this.src='{{$defaultProfileImage}}';" alt="{{$employee->name ??''}}"/>
                            </figure>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold">{{$leave->employee->full_name ?? ''}}</span>
                            <span>{{$leave->employee->email ?? ''}}</span>
                            <span>{{$leave->employee->phone ?? ''}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-span-2 self-center justify-self-center {{getUserRole() === 'employee' ? 'ms-3' : 'ms-[-3rem]'}}">
                    <div class="flex items-center gap-2">
                        <p class="font-bold">Requisition Type :</p>
                        <span class="font-semibold italic flex items-center gap-1 text-sm">{!! $leave->requisition_type ? '<i class="ti ti-hourglass"></i>' . $leave->requisitionType->name  : '' !!}</span>
                    </div>
                    {{--@if($editPermission)
                        <label for="leave_typeX" class="inputLabel">
                            Requisition Type <span class="text-[#831b94]">*</span>
                        </label>

                        <ul class="flex flex-col sm:flex-row" id="e_requisition_type">
                            @foreach($requisitionType as $item)
                                <li class="inline-flex items-center gap-x-2 py-3 px-2 text-sm font-medium bg-white border border-gray-400 text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-white disabled:cursor-default">
                                    <div class="relative flex items-start w-full">
                                        <div class="flex items-center h-5">
                                            <input id="hs-horizontal-list-group-item-radio-{{$item->id}}-k" name="requisition_type" value="{{$item->id}}" {{$leave->requisition_type==$item->id ? 'checked' : ''}}  {{$editPermission && $leave->approval_status==1 ? '' : 'disabled'}} type="radio" class="border-gray-400 rounded-full disabled:opacity-70 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                        </div>
                                        <label for="hs-horizontal-list-group-item-radio-{{$item->id}}-k" class="ms-3 block w-full text-sm text-gray-800 dark:text-neutral-500 cursor-pointer disabled:cursor-default">
                                            {{$item->name}}--}}{{-- <span class="text-teal-600 dark:text-teal-300">(0/10)</span> --}}{{--
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="flex items-center gap-2">
                            <p class="font-bold">Requisition Type :</p>
                            <span class="font-semibold italic flex items-center gap-1 text-sm">{!! $leave->requisition_type ? '<i class="ti ti-hourglass"></i>' . $leave->requisitionType->name  : '' !!}</span>
                        </div>
                    @endif--}}
                </div>

                <div class="col-span-3  grid grid-cols-1 sm:grid-cols-2 gap-y-2 {{$leave->approval_status==1 ? 'gap-x-4' : ''}}">
                    <div class="col-span-1 relative">

                            {{--<label for="select_employeeX" class="inputLabel">
                                Select A Leave Reliever
                            </label>
                            <div id="employee-spinner{{$leave->id ??''}}" class="absolute right-10 bottom-4 z-10">
                                <div class="animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-gray-400 rounded-full dark:text-blue-500" role="status" aria-label="loading">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <select id="c_leave_select_reliever_{{$leave->id}}" name="reliever_emp_id" class="disabled:!opacity-80" placeholder="Select a leave reliever..."></select>
                            <span class="error_msg" id="e_error_leave_select_reliever"></span>--}}
                        @if($leave->leaveReliever)
                            <div>
                                <div class=""> <span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4 font-normal">Leave Reliever</span></div>
                                <div class="h-[1px] mt-0.5 bg-neutral-300 font-normal"></div>
                            </div>
                            <div class="flex flex-col text-sm py-4">
                                <span class="font-semibold italic flex items-center gap-2">{!! $leave->leaveReliever && $leave->leaveReliever->emp_id ? '<i class="ti ti-hash"></i>' . $leave->leaveReliever->emp_id : '' !!}</span>
                                <span class="font-semibold italic flex items-center gap-2">{!! $leave->leaveReliever && $leave->leaveReliever->full_name ? '<i class="ti ti-user"></i>' . $leave->leaveReliever->full_name : '' !!}</span>
                                <span class="flex items-center gap-2">{!! $leave->leaveReliever && $leave->leaveReliever->email ? '<i class="ti ti-at"></i>' . $leave->leaveReliever->email : '' !!}</span>
                                <span class="flex items-center gap-2">{!! $leave->leaveReliever && $leave->leaveReliever->phone ? '<i class="ti ti-phone"></i>' . $leave->leaveReliever->phone : '' !!}</span>
                            </div>
                        @else
                            <div>
                                <div class=""> <span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Leave Reliever</span></div>
                                <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                            </div>
                            <div class="font-semibold text-sm py-4">N/A</div>
                        @endif

                    </div>
                    <div class="">
                        {{--@if($leave->approval_status==1)
                            <label for="leave_typeX" class="inputLabel {{$editPermission ? '' : '!font-bold'}}">
                                Leave Type <span class="text-[#831b94]">*</span>
                            </label>

                            <ul class="flex flex-col sm:flex-row" id="e_leave_type">
                                @foreach($leaveType as $item)
                                    <li class="inline-flex items-center gap-x-2 py-3 px-2 text-sm font-medium bg-white border border-gray-400 text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                                        <div class="relative flex items-start w-full">
                                            <div class="flex items-center h-5">
                                                <input id="hs-horizontal-list-group-item-radio-{{$item->id}}-e_" name="leave_type" value="{{$item->id}}" {{$leave->leave_type==$item->id ? 'checked' : ''}}  {{$leave->approval_status==1 ? '' : 'disabled'}} type="radio" class="border-gray-200 rounded-full disabled:opacity-70 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                            </div>
                                            <label for="hs-horizontal-list-group-item-radio-{{$item->id}}-e_" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-500 cursor-pointer">
                                                {{$item->name}}--}}{{-- <span class="text-teal-600 dark:text-teal-300">(0/10)</span> --}}{{--
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else

                        @endif--}}
                        <div>
                            <div class=""> <span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Leave Type</span></div>
                            <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                        </div>
                        <div class="font-semibold italic flex items-center gap-1 text-sm py-4">{!! $leave->leave_type ? '<i class="ti ti-hash"></i>' . $leave->leaveType->name . ' Leave' : '' !!}</div>
                    </div>
                </div>
            </div>

        </div>
        <div class="grid grid-cols-1 {{$editPermission ? 'sm:grid-cols-3 items-center gap-4 lg:gap-6' : 'sm:grid-cols-2 items-start gap-0'}}  mt-2">
            @if ($editPermission)
                <div class="">
                    <label for="leaveRequestX" class="inputLabel">
                        Start Date
                    </label>
                    <input type="date" id="e_start_date" name="start_date" onchange="calculateLeaveDays('e_')" value="{{$leave->start_date}}" class="inputFieldBorder" {{$editPermission  ? '' : 'readonly'}}>
                    <span class="error_msg" id="e_error_start_date"></span>
                </div>
                <div>
                    <label for="leaveRequestY" class="inputLabel">
                        End Date
                    </label>
                    <input type="date" id="e_end_date" name="end_date" onchange="calculateLeaveDays('e_')" value="{{$leave->end_date}}" class="inputFieldBorder"  {{$editPermission ? '' : 'readonly'}}>
                    <span class="error_msg" id="e_error_end_date"></span>
                </div>
                <div class="flex items-center gap-2">
                    <label for="" class="font-semibold">Intend of Leave Days:</label>
                    <span id="e_leave_days_count" class="text-[#831b94] text-[#831b94] italic font-medium">({{ $leave->intended_leave_days < 10 ? '0' . $leave->intended_leave_days : $leave->intended_leave_days }})</span>
                </div>
            @else
                <div class="col-span-1 flex flex-col gap-1">
                    <div>
                        <div class=""> <span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Leave Details</span></div>
                        <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                    </div>
                    <div class="flex items-center gap-2 pt-4">
                        <label for="" class="font-bold">Intend of Leave Days:</label>
                        <span id="e_leave_days_count" class="text-[#831b94] text-[#831b94] italic font-medium">({{ $leave->intended_leave_days < 10 ? '0' . $leave->intended_leave_days : $leave->intended_leave_days }})</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <p class="font-bold">Start Date :</p>
                        <span class="font-semibold italic flex items-center gap-1 text-sm">{!! $leave->start_date ? '<i class="ti ti-calendar-month"></i>' . $leave->start_date : 'N/A' !!}</span>
                    </div>
                    <div class="flex items-center gap-2 pb-4">
                        <p class="font-bold">End Date :</p>
                        <span class="font-semibold italic flex items-center gap-1 text-sm">{!! $leave->end_date ? '<i class="ti ti-calendar-month"></i>' . $leave->end_date : 'N/A' !!}</span>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class=""><span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Objection</span></div>
                    <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                    <div class="flex items-center gap-6">
                        <p class="font-medium py-4 print:my-5"> Is Objection:</p>
                        <div class="flex items-center gap-1">
                            <input type="checkbox" name="is_objection" class="shrink-0 border-gray-500 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-400 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-yes">
                            <label for="hs-yes" class="text-sm mt-0.5 text-gray-900 ms-3 dark:text-neutral-400 font-semibold print:ms-0 print:text-xs">Yes</label>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
            @if ($editPermission)
                <div>
                    <label for="leave_reasonX" class="inputLabel">
                        Leave Reason
                    </label>
                    <textarea id="e_leave_reason" name="reason" class="inputFieldBorder" value="{{$leave->leave_reason}}" rows="2" placeholder="Please tell us your leave reason..." {{$editPermission ? '' : 'disabled'}} >{{$leave->leave_reason ?? ''}}</textarea>

                    <span class="error_msg" id="e_error_leave_reason"></span>
                </div>
            @else
                <div class="col-span-3 grid sm:grid-cols-2 grid-cols-1 {{$leave->approval_status===1 ? 'gap-2' : ''}}">
                    <div class="col-span-1">
                        <div class=""> <span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Leave Reason</span></div>
                        <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                        <p class="font-medium py-4 print:my-5"> {!! $leave->leave_reason ? '<i class="ti ti-note"></i>' . $leave->leave_reason : 'N/A' !!}</p>
                    </div>

                    @if($approvalPermission)
                        <div>
                            <label for="e_leave_remarks" class="inputLabel !font-bold">
                                Leave Remarks <span class="text-[#831b94]">*</span>
                            </label>
                            <textarea id="e_leave_remarks" name="remarks" class="inputFieldBorder" rows="2" placeholder="Remarks..." ></textarea>
                            <span class="error_msg" id="e_error_leave_remarks"></span>
                        </div>
                    @endif
                </div>
            @endif
        </div>
        @if ($editPermission)
            <div class="">
                <!-- component -->
                <label for="" class="inputLabel">Upload attachments</label>
                <div class="">
                    <div x-data="dataFileDnD()" class="relative flex flex-col py-4 text-gray-400 rounded">
                        <div x-ref="dnd"
                            class="relative flex flex-col text-gray-400 border-dashed {{ $editPermission ? ' border-[2px] border-red-600 cursor-pointer' : 'border border-gray-200 animate-none cursor-default'}} rounded">
                            <input id="fileAttachment_edit" {{ $editPermission ? '' : 'disabled'}} accept=".pdf, .doc, .docx, image/*" type="file" multiple
                                class="absolute inset-0 z-50 w-full h-full p-0 m-0 outline-none opacity-0 {{ $editPermission ? 'cursor-pointer' : 'cursor-default'}}"
                                @if ($editPermission)
                                        @change="addFiles($event)"
                                        @dragover="$refs.dnd.classList.add('!border-[3px]'); $refs.dnd.classList.add('animate-pulse');"
                                        @dragleave="$refs.dnd.classList.remove('!border-[3px]'); $refs.dnd.classList.remove('animate-pulse');"
                                        @drop="$refs.dnd.classList.remove('!border-[3px]'); $refs.dnd.classList.remove('animate-pulse');"
                                @endif
                                title=""
                                name="attachments[]"
                            />

                            <div class="flex flex-col items-center justify-center py-10 text-center">
                                <svg class="w-6 h-6 mr-1 text-current-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="m-0">Drag your files here or click in this area.</p>
                            </div>
                        </div>
                        <span id="error_file_upload_edit" class="text-xs font-medium text-red-500 mt-2"></span>
                        <template x-if="files.length > 0">
                            <div class="grid grid-cols-2 gap-4 mt-4 md:grid-cols-6" @drop.prevent="drop($event)"
                                @dragover.prevent="$event.dataTransfer.dropEffect = 'move'">
                                <template x-for="(_, index) in Array.from({ length: files.length })">
                                    <div class="relative flex flex-col items-center overflow-hidden text-center bg-gray-100 border rounded cursor-move select-none"
                                        style="padding-top: 100%;" @dragstart="dragstart($event)" @dragend="fileDragging = null"
                                        :class="{'border-red-600': fileDragging == index}" draggable="true" :data-index="index">
                                        <button class="absolute top-0 right-0 z-50 p-1 bg-white rounded-bl focus:outline-none" type="button" @click="remove(index)">
                                            <svg class="w-4 h-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <div class="absolute bottom-0 left-0 right-0 flex flex-col p-2 text-xs bg-white bg-opacity-50">
                                            <span class="w-full font-bold text-gray-900 truncate"
                                                x-text="files[index].name">Loading</span>
                                            <span class="text-xs text-gray-900" x-text="humanFileSize(files[index].size)">...</span>
                                        </div>

                                        <div class="absolute inset-0 z-40 transition-colors duration-300" @dragenter="dragenter($event)"
                                            @dragleave="fileDropping = null"
                                            :class="{'bg-blue-200 bg-opacity-80': fileDropping == index && fileDragging != index}">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        @endif
        @if($leave->leaveDocument)
            <label for="" class="inputLabel"><span class="font-bold">Previously Uploaded: </span> {{$leave->leaveDocument->count()==0 ? 'N/A' : ''}}</label>
            @if($leave->leaveDocument->count()>0)
                <div class="grid grid-cols-6 gap-4">
                    @foreach($leave->leaveDocument as $item)
                        @php
                            $fileName=$item->file_name;
                            $fileUrl=$item->url;
                            $fileType = getFileType($fileName);
                        @endphp
                        <div class="relative flex flex-col items-center overflow-hidden text-center bg-gray-100 border rounded cursor-move select-none hover:shadow-md duration-200" style="padding-top: 100%;" id="leaveDocumentPreviewWrap_{{$item->id}}" >
                            <div class="w-full flex items-center justify-between absolute top-0 z-10 left-0">
                                <a href="{{asset($fileUrl)}}" download="{{asset($fileUrl)}}" class=" p-2 bg-white rounded-br focus:outline-none hover:bg-[#831b94] hover:text-white duration-200">
                                    <i class="fa-regular fa-circle-down"></i>
                                </a>
                                @if($editPermission && $leave->approval_status==1)
                                    <button class="p-2 bg-white rounded-bl focus:outline-none" type="button" onclick="removeHtmlElement('leaveDocumentPreviewWrap_{{$item->id}}')">
                                        <svg class="w-4 h-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            @if($fileType=='Image')
                                <img src="{{asset($fileUrl)}}" class="absolute inset-0 z-0 object-cover w-full h-full border-4 border-white preview" onerror="this.onerror=null;this.src='{{asset('assets/img/default/default-image.jpg')}}';" alt="">
                            @endif
                            @if($fileType=='Document')
                                <iframe src="{{asset($fileUrl)}}" width="100%" height="100%" class="absolute inset-0 z-0 border-4 border-white preview"></iframe>
                            @endif
                            <div class="absolute bottom-0 left-0 right-0 flex flex-col p-2 text-xs bg-white bg-opacity-50">
                                <span class="w-full font-bold text-gray-900 truncate">{{$fileName}}</span>
                            </div>
                            <input type="hidden" name="prev_file[]" id="prev_file_{{$item->id}}" value="{{$item->id}}">
                        </div>

                    @endforeach
                </div>
            @endif
        @endif

        <div class="flex items-end">
            <div class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg">Leave Approval</div>
            <div class="grow h-[1px] bg-neutral-300"></div>
        </div>
        <div class="py-2 print:my-2">

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
    </div>
    <div class="sticky bottom-0 z-50 bg-gray-100">
        <div class="flex justify-between items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
            <div class="">
                @if($approvalPermission)
                    <button id="e_submitBtn_approved" type="submit" name="approval_status" value="2" class="submit-button-green">
                        Approve
                    </button>
                    <button id="e_submitBtn_rejected" type="submit" name="approval_status" value="3" class="submit-button !bg-neutral-800">
                        Reject
                    </button>
                @endif
            </div>
            <div class="">
                <button type="button" onclick="largeModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    {{$leave->approval_status==1 ? 'Cancel' : 'CLose'}}
                </button>

                @if($editPermission && $leave->approval_status==1)
                    <button id="e_submitBtn_update" type="submit" name="approval_status" value="{{$leave->approval_status}}" class="submit-button">
                        Update
                    </button>
                @endif
            </div>
        </div>
    </div>
</form>
<script src="https://unpkg.com/create-file-list"></script>
<script>
    function dataFileDnD() {
        return {
            files: [],
            fileDragging: null,
            fileDropping: null,
            humanFileSize(size) {
                const i = Math.floor(Math.log(size) / Math.log(1024));
                return (
                    (size / Math.pow(1024, i)).toFixed(2) * 1 +
                    " " +
                    ["B", "kB", "MB", "GB", "TB"][i]
                );
            },
            remove(index) {
            let files = Array.from(this.files);
            files.splice(index, 1);
            // Update the input element's files property
            const dataTransfer = new DataTransfer();
            files.forEach(file => {
                dataTransfer.items.add(file);
            });
            document.getElementById('fileAttachment_edit').files = dataTransfer.files;

            this.files = createFileList(files);
        },
            drop(e) {
                let removed;
                let files = Array.from(this.files);

                removed = files.splice(this.fileDragging, 1);
                files.splice(this.fileDropping, 0, ...removed);

                this.files = createFileList(files);

                this.fileDropping = null;
                this.fileDragging = null;
            },
            dragenter(e) {
                let targetElem = e.target.closest("[draggable]");

                this.fileDropping = targetElem.getAttribute("data-index");
            },
            dragstart(e) {
                this.fileDragging = e.target
                    .closest("[draggable]")
                    .getAttribute("data-index");
                e.dataTransfer.effectAllowed = "move";
            },
            loadFile(file) {
                const preview = document.querySelectorAll(".preview");
                const blobUrl = URL.createObjectURL(file);

                preview.forEach(elem => {
                    elem.onload = () => {
                        URL.revokeObjectURL(elem.src); // free memory
                    };
                });

                return blobUrl;
            },
            addFiles(e) {
                const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp', 'image/webp']

                // Filter out invalid files
                const newFiles = Array.from(e.target.files).filter(file => validTypes.includes(file.type));

                // Show error message if some files were not accepted
                if (newFiles.length < e.target.files.length) {
                    toastr.error('Allowed file types are .pdf, .doc, .docx, and images.');
                    $('#error_file_upload_edit').html('Allowed file types are .pdf, .doc, .docx, and images!');
                } else {
                    $('#error_file_upload_edit').html('');
                }
                // Update the input element's files property only with valid files
                const dataTransfer = new DataTransfer();
                newFiles.forEach(file => {
                    dataTransfer.items.add(file);
                });
                document.getElementById('fileAttachment_edit').files = dataTransfer.files;

                // Update this.files only with valid files
                this.files = createFileList(newFiles);
            }
        };
    }

    function createFileList(...args) {
        const files = new DataTransfer();
        args.forEach(fileArray => {
            fileArray.forEach(file => {
                files.items.add(file);
            });
        });
        return files.files;
    }
</script>
