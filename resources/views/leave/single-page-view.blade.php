@extends('layouts.app')
@section('title', 'Leave Request')
@section('pageTitle', 'Leave Request')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Leave', 'url'=>route('leave.index')],['title'=>'Leave Request', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection

@section('content')
    <style>
        @font-face {
            font-family: 'signature-1';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url('/assets/fonts/signature-font/CreattionDemo.otf');
        }
    </style>
    <x-containers.container-box data="!p-0 overflow-hidden print:border print:border-gray-300 print:text-xs print:!align-middle">
        @php
            $editPermission=true;

            if(getUserRole() == 'employee') {
                if(in_array($leave->approval_status, [2,3])){
                    $editPermission=false;
                }
            }
            if($leave->approval_status==1) $bgColor="bg-warning";
            if($leave->approval_status==2) $bgColor="bg-teal-600";
            if($leave->approval_status==3) $bgColor="bg-[#831b94]";

        @endphp

        <div class="">
            <div class="{{$bgColor}}">
                <h3 class="text-xl text-center font-medium p-2 text-white">{{$leave->requisition_type===1 ? 'Leave in Advance' : 'Leave of Absence'}}</h3>
            </div>
            <div class="p-4 print:my-3">
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
                <div class="p-4 py-2 print:my-2">
                    <p><span class="font-medium">Leave Type:</span> {{$leave->leaveType->name}}</p>
                    <p><span class="font-medium">Start Date:</span> {{date('d M Y', strtotime($leave->start_date))}}</p>
                    <p><span class="font-medium">End Date:</span> {{date('d M Y', strtotime($leave->end_date))}}</p>
                    <p><span class="font-medium">Total Days:</span> {{\Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1}}</p>
                </div>
            </div>
            <div class="col-span-1">
                <div class=""><span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Reliever Details</span></div>
                <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                <div class="p-4 py-2 print:my-2">
                    @if($leave->reliever_emp_id)
                        <p class="font-bold flex items-center gap-2"><i class="ti ti-hash"></i> {{$reliever->emp_id ?? ''}}</p>
                        <p class="font-bold flex items-center gap-2"><i class="ti ti-user"></i> {{$reliever->full_name ?? ''}}</p>
                        <p class="flex items-center gap-2"><span class="font-medium"><i class="ti ti-at"></i> </span> {{$reliever->email ?? $reliever->personal_email ?? 'N/A'}}</p>
                        <p class="flex items-center gap-2"><span class="font-medium"><i class="ti ti-phone"></i> </span> {{$reliever->phone ?? ''}}</p>
                    @else
                    <p class="flex items-center gap-2">N/A</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="grid sm:grid-cols-2 grid-cols-1">
            <div class="col-span-1">
                <div class=""> <span class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Leave Reason</span></div>
                <div class="h-[1px] mt-0.5 bg-neutral-300"></div>
                <p class="font-medium print:my-4 m-4 line-clamp-3"> {{$leave->leave_reason ?? 'N/A'}}</p>
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
            <div class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Leave Approval</div>
            <div class="grow h-[1px] mt-0.5 bg-neutral-300"></div>
        </div>
        <div class="p-4 py-2 print:my-2">
            <div class="grid grid-cols-1 sm:grid-cols-3 items-center">
                <div class="col-span-2">
                    <div class="col-span-1 flex items-center gap-x-10 print:gap-x-2 print:gap-2 mb-2">
                        <p class="font-medium w-32 dark:text-neutral-100 print:w-24">Leave Approval:</p>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" {{$leave->approval_status==2 ? 'checked' : ''}} class="shrink-0 border-gray-500 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-400 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-yes-l">
                                <label for="hs-yes-l" class="text-sm mt-0.5 text-gray-900 ms-3 dark:text-neutral-400 font-semibold print:ms-0 print:text-xs">Yes</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" {{$leave->approval_status==3 ? 'checked' : ''}} class="shrink-0 border-gray-500 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-400 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-no-l">
                                <label for="hs-no-l" class="text-sm mt-0.5 text-gray-900 ms-3 dark:text-neutral-400 font-semibold print:ms-0 print:text-xs">No</label>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="flex items-center justify-between gap-10 shrink">
                        <div class="">
                            <p class="font-medium">
                                {{ $leave->approval_status == 1 ? 'Updated By:' : ($leave->approval_status == 2 || $leave->approval_status == 1 &&  $leave->line_manager_approved_by!=='' ? 'Approved By:' : 'Rejected By:') }}
                            </p>
                            @if($leave->updatedBy)
                                <p class="font-semibold italic flex items-center gap-2"><i class="ti ti-user"></i> {{$leave->updatedBy->name ??''}}</p>
                                <p class=" font-medium italic flex items-center gap-2"><i class="ti ti-at"></i> {{$leave->updatedBy->email ??''}}</p>
                                <p class="font-semibold italic flex items-center gap-2"><i class="ti ti-user-cog"></i> {{$leave->updatedBy->role->name ??''}}</p>
                            @endif
                        </div>
                        <div class="">
                            <p class="font-medium">
                                {{ $leave->approval_status == 1 ? '' : ($leave->approval_status == 2 || $leave->approval_status == 1 &&  $leave->department_head_approved_by!=='' ? 'Approved By:' : 'Rejected By:') }}
                            </p>
                            @if($leave->department_head_approved_by)
                                <p class="font-semibold italic flex items-center gap-2"><i class="ti ti-user"></i> {{$leave->departmentHeadApproved->full_name ??''}}</p>
                                <p class=" font-medium italic flex items-center gap-2"><i class="ti ti-at"></i> {{$leave->departmentHeadApproved->email ??''}}</p>
                                <p class="font-semibold italic flex items-center gap-2"><i class="ti ti-user-cog"></i> {{$leave->departmentHeadApproved->role->name ??''}}</p>
                            @endif
                        </div>
                    </div> --}}
                    <div class="flex gap-28 print:gap-20 shrink">
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
                        <div class="flex items-center justify-start">
                            <figure class="size-32 print:size-24 aspect-square">
                                <img src="{{asset('assets/img/approved.png')}}" class="w-full" alt="">
                            </figure>
                        </div>
                    @elseif($leave->approval_status==3)
                        <div class="flex items-center justify-start">
                            <figure class="size-32 print:size-24 aspect-square">
                                <img src="{{asset('assets/img/rejected.png')}}" class="w-full" alt="">
                            </figure>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="grid sm:grid-cols-3 grid-cols-1 pb-2 ">
            <div class="col-span-2 flex flex-col justify-between">
                <div class="flex items-end  print:flex">
                    <div class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Department Signatures</div>
                    <hr class="grow h-[1px] bg-neutral-300 dark:bg-neutral-200 print:flex"></hr>
                </div>
                <div class="p-4 col-span-2 flex items-center gap-x-10 print:gap-x-2">
                    <p class="font-medium w-20 dark:text-neutral-100 print:w-20">Objections:</p>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" {{$leave->is_objection === 1 ? 'checked' : ''}} class="shrink-0 border-gray-500 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-400 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-yes">
                            <label for="hs-yes" class="text-sm mt-0.5 text-gray-900 ms-3 dark:text-neutral-400 font-semibold print:ms-0 print:text-xs">Yes</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" {{$leave->is_objection === 0 ? 'checked' : ''}} class="shrink-0 border-gray-500 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-400 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-no">
                            <label for="hs-no" class="text-sm mt-0.5 text-gray-900 ms-3 dark:text-neutral-400 font-semibold print:ms-0 print:text-xs">No</label>
                        </div>
                    </div>
                </div>
                <div class="p-4 py-1 print:block ">
                    <div class="grid sm:grid-cols-2 grid-cols-1 items-end">

                        {{-- @php
                            $defaultDSignature=asset('assets/img/No_signature.png');
                            $digital_signature='';
                            if(($leave->approval_status == 2 || $leave->approval_status == 3 ) && $leave->updatedBy ){
                                $digital_signature=employeeDigitalSignature($leave->updatedBy->emp_id, $leave->updatedBy->digital_signature);
                            }
                            if($leave->approval_status == 2) $digital_signature=employeeDigitalSignature($leave->approved_by, $leave->approvedBy->digital_signature);
                            if($leave->approval_status == 3) $digital_signature=employeeDigitalSignature($leave->rejected_by, $leave->rejectedBy->digital_signature);
                        @endphp --}}
                        <div class="col-span-2 mt-4 flex items-end justify-between">
                            <div class="max-w-64">
                                <p class="font-semibold font-signature-1 text-xl text-center print:font-signature-1">{{$leave->lineManagerApproved->full_name ?? ''}}</p>
                                {{-- <figure class="w-auto h-14 mx-auto">
                                    @if ($digital_signature!='')
                                        <img class="h-full object-cover object-center mx-auto rounded-md ring-2 ring-white dark:ring-gray-800" src="{{$digital_signature}}" alt="Employee Digital Signature" onerror="this.onerror=null;this.src='{{$defaultDSignature}}';">
                                    @endif
                                </figure> --}}
                                <hr class="border-gray-800 print:max-w-32 mx-auto border-dotted dark:border-white">
                                <p class="font-medium dark:text-neutral-100 text-center">Signature of Line Manager</p>
                            </div>
                            <div class="max-w-64 mx-auto">
                                <p class="font-semibold font-signature-1 text-xl text-center print:font-signature-1">{{$leave->departmentHeadApproved->full_name ?? ''}}</p>
                                {{-- <figure class="w-auto h-14 mx-auto">
                                    @if ($digital_signature!='')
                                        <img class="h-full object-cover object-center mx-auto rounded-md ring-2 ring-white dark:ring-gray-800" src="{{$digital_signature}}" alt="Employee Digital Signature" onerror="this.onerror=null;this.src='{{$defaultDSignature}}';">
                                    @endif
                                </figure> --}}
                                <hr class="border-gray-800 print:max-w-48 mx-auto border-dotted dark:border-white">
                                <p class="font-medium dark:text-neutral-100 text-center">Signature of Departmental Head</p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="col-span-1 flex flex-col justify-between">
                <div class="flex items-end  print:flex">
                    <div class="{{$bgColor}} text-white truncate px-2 py-1 rounded-tr-lg print:px-4">Employee Signature</div>
                    <hr class="grow h-[1px] bg-neutral-300 dark:bg-neutral-200 print:flex"></hr>
                </div>
                <div class="grid sm:grid-cols-2 grid-cols-1 items-end px-2 py-1 print:block">
                    <div class="col-span-1 mt-3 self-center">
                        <div class="max-w-48 print:max-w-36">
                            <p class="font-semibold font-signature-1 text-xl text-center print:font-signature-1">{{$leave->employee->full_name ?? ''}}</p>
                            <hr class="border-gray-800 print:max-w-32 mx-auto border-dotted dark:border-white">
                            <p class="font-medium dark:text-neutral-100 text-center">Signature of Employee</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-containers.container-box>

@endsection
@include('leave.create-late-request')
@include('leave.create-leave-request')
@section('scripts')
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
@endsection



