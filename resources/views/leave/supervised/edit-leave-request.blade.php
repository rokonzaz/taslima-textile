@extends('layouts.app')
@section('title', 'Leave Request')
@section('pageTitle', 'Leave Request')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Leave', 'url'=>route('leave.index')],['title'=>'Leave Request', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection

@section('content')
    <x-containers.container-box data="!p-0 overflow-hidden">
        @php
            $editPermission=true;
            if(getUserRole() == 'employee') {
                if(in_array($leave->approval_status, [2,3])){
                    $editPermission=false;
                }
            }
        @endphp

        <div class="">
            <div class="bg-teal-600">
                <h3 class="text-xl text-center font-medium p-2 text-white">Leave Request</h3>
            </div>
            <form action="{{ route('leaveRequest.update', ['id'=>$leave->id]) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateFormRequest('e_', 'leaveRequest')">
                @csrf
                <div class="p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 lg:gap-4 mt-2">
                        <div class="col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-y-2 gap-x-4 lg:gap-x-6">
                            <div class="col-span-1">
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
                            <div class="col-span-3"><hr></div>
                            <div class="col-span-3">
                                <label for="leave_typeX" class="inputLabel">
                                    Leave Type <span class="text-[#831b94]">*</span>
                                </label>

                                <ul class="flex flex-col sm:flex-row" id="e_leave_type">
                                    @foreach($leaveType as $item)
                                        <li class="inline-flex items-center gap-x-2 py-3 px-2 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                                            <div class="relative flex items-start w-full">
                                                <div class="flex items-center h-5">
                                                    <input id="hs-horizontal-list-group-item-radio-{{$item->id}}_1" name="leave_type" value="{{$item->id}}" {{$leave->leave_type==$item->id ? 'checked' : ''}}  {{$editPermission && $leave->approval_status==1 ? '' : 'disabled'}} type="radio" class="e_leave_type border-gray-200 rounded-full disabled:opacity-50 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" >
                                                </div>
                                                <label for="hs-horizontal-list-group-item-radio-{{$item->id}}_1" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-500 cursor-pointer">
                                                    {{$item->name}}{{--<span class="text-teal-600 dark:text-teal-300">(0/10)</span>--}}
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                        <div>
                            <label for="leaveRequestX" class="inputLabel">
                                Start Date
                            </label>
                            <input type="date" id="e_start_date" name="start_date" value="{{$leave->start_date}}" class="inputField" {{$editPermission && $leave->approval_status==1 ? '' : 'disabled'}}>
                            <span class="error_msg" id="e_error_start_date"></span>
                        </div>
                        <div>
                            <label for="leaveRequestY" class="inputLabel">
                                End Date
                            </label>
                            <input type="date" id="e_end_date" name="end_date" value="{{$leave->end_date}}" class="inputField"  {{$editPermission && $leave->approval_status==1 ? '' : 'disabled'}}>
                            <span class="error_msg" id="e_error_end_date"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                        <div>
                            <label for="leave_reasonX" class="inputLabel">
                                Leave Reason
                            </label>
                            <textarea id="e_leave_reason" name="reason" class="inputField" value="{{$leave->leave_reason}}" rows="2" placeholder="Please tell us your leave reason..." {{$editPermission && $leave->approval_status==1 ? '' : 'disabled'}}>{{$leave->leave_reason ?? ''}}</textarea>

                            <span class="error_msg" id="e_error_leave_reason"></span>
                        </div>
                        @if(in_array(getUserRole(), ['super-admin', 'hr']))
                            <div>
                                <label for="e_leave_remarks" class="inputLabel">
                                    Leave Remarks
                                </label>
                                <textarea id="leave_remarks" name="remark" value="{{$leave->remarks}}"  class="inputField" rows="2" placeholder="Remarks..." {{$editPermission && $leave->approval_status==1 ? '' : 'disabled'}}>{{$leave->remarks ?? ''}}</textarea>
                                <span class="error_msg" id="e_error_leave_remarks"></span>
                            </div>
                        @endif
                    </div>
                    <div class="">
                        <!-- component -->
                        <label for="" class="inputLabel">Upload attachments</label>
                        <div class="">
                            <div x-data="dataFileDnD()" class="relative flex flex-col py-4 text-gray-400 rounded">
                                <div x-ref="dnd"
                                     class="relative flex flex-col text-gray-400 border-[2px] border-red-600 border-dashed rounded cursor-pointer">
                                    <input id="fileAttachment_edit" accept=".pdf, .doc, .docx, image/*" type="file" multiple
                                           class="absolute inset-0 z-50 w-full h-full p-0 m-0 outline-none opacity-0 cursor-pointer"
                                           @change="addFiles($event)"
                                           @dragover="$refs.dnd.classList.add('!border-[3px]'); $refs.dnd.classList.add('animate-pulse');"
                                           @dragleave="$refs.dnd.classList.remove('!border-[3px]'); $refs.dnd.classList.remove('animate-pulse');"
                                           @drop="$refs.dnd.classList.remove('!border-[3px]'); $refs.dnd.classList.remove('animate-pulse');"
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
                    @if($leave->leaveDocument)
                        <label for="" class="inputLabel">Previously Uploaded: {{$leave->leaveDocument->count()==0 ? 'N/A' : ''}}</label>
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
                </div>
                <div class="">
                    <div class="mt-5 flex justify-between items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                        <div class="">
                            @if(in_array(getUserRole(), ['super-admin', 'hr']) && $leave->approval_status==1)
                                <button type="submit" name="approval_status" value="2" class="submit-button-green">
                                    Approve
                                </button>
                                <button type="submit" name="approval_status" value="3" class="submit-button !bg-neutral-800">
                                    Reject
                                </button>
                            @endif
                        </div>
                        <div class="">
                            @if($editPermission && $leave->approval_status==1)
                                <button type="submit" name="approval_status" value="{{$leave->approval_status}}" class="submit-button">
                                    Update
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
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



