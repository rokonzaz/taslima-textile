<x-modals.large-modal id="createLeaveRequestModal" title="Create a Leave Request">
    <form class="mb-0" action="{{ route('leaveRequest.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateFormRequest('c_', 'leaveRequest')">
        @csrf
        <div class="p-4 overflow-y-scroll max-h-[70vh]">
            @php
                $leaveBalances=[];
                if(auth()->user()->employee){
                    $leaveBalances=auth()->user()->employee->leaveBalance(date('Y'));
                }
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 lg:gap-4 mt-2">
                <div class="col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-y-2 gap-x-4 lg:gap-x-6">
                    {{--@if(in_array(getUserRole(), ['super-admin', 'hr']))
                        <div class="col-span-1 relative">
                            <label for="select_employeeX" class="inputLabel">
                                Select An Employee <span class="text-[#831b94]">*</span>
                            </label>
                            <div id="employee-spinner" class="absolute right-10 bottom-4 z-10">
                                <div class="animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-gray-400 rounded-full dark:text-blue-500" role="status" aria-label="loading">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <select id="c_leave_select_employee" name="emp_id" class="" placeholder="Select an employee..."></select>
                            <span class="error_msg" id="c_error_leave_select_employee"></span>
                        </div>
                    @else
                        --}}{{-- <div class="hidden"><select id="c_leave_select_employee"></select></div> --}}{{--
                    @endif--}}
                    <div class="col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-y-2 lg:gap-x-6">
                        <div class="">
                            <label for="leave_typeX" class="inputLabel">
                                Requisition Type <span class="text-[#831b94]">*</span>
                            </label>

                            <ul class="flex flex-col sm:flex-row" id="c_requisition_type">
                                @foreach($requisitionType as $item)
                                    <li class="inline-flex items-center gap-x-2 py-3 px-2 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                                        <div class="relative flex items-start w-full">
                                            <div class="flex items-center h-5">
                                                <input id="requision-type-item-radio-{{$item->id}}" name="requisition_type" value="{{$item->id}}" onchange="changeRequisitionType()" type="radio" class="border-gray-200 rounded-full disabled:opacity-50 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" {{($item->id === 1) ? "checked" : ""}}>
                                            </div>
                                            <label for="requision-type-item-radio-{{$item->id}}" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-500 cursor-pointer">
                                                {{$item->name}}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="">
                            <label for="leave_typeX" class="inputLabel">
                                Leave Type <span class="text-[#831b94]">*</span>
                            </label>

                            <ul class="flex flex-col sm:flex-row" id="c_leave_type">
                                @foreach($leaveType as $item)
                                    @php
                                        $leaveBalance = collect($leaveBalances)->firstWhere('id', $item->id);
                                        $allowance = $leaveBalance ? $leaveBalance['allowance'] : 0;
                                        $taken = $leaveBalance ? $leaveBalance['taken'] : 0;
                                        $remaining = $leaveBalance ? $leaveBalance['remaining'] : 0;
                                    @endphp
                                    <li class="inline-flex items-center gap-x-2 py-3 px-2 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                                        <div class="relative flex items-start w-full">
                                            <div class="flex items-center h-5">
                                                <input id="leave-type-item-radio-{{$item->id}}-c_" name="leave_type" value="{{$item->id}}" data-remaining="{{$remaining}}" onchange="calculateLeaveDays('c_')" type="radio" class="c_leave-type -mt-0.5 border-gray-200 rounded-full disabled:opacity-50 disabled:bg-gray-500 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                            </div>
                                            <label for="leave-type-item-radio-{{$item->id}}-c_" class="ps-3 block w-full text-sm text-gray-600 dark:text-neutral-500 cursor-pointer disabled:opacity-50 disabled:bg-gray-500">
                                                {{$item->name}}
                                                @if(in_array(getUserRole(), ['employee', 'line-manager', 'department-head']))
                                                    <span class="text-teal-600">{{$remaining}}/{{$allowance}}</span>
                                                    {{-- <input type="hidden" data-remaining="{{$remaining}}" data-allowance="{{$allowance}}"> --}}
                                                @else
                                                    <span class="text-teal-600 dark:text-teal-300 text-xs" id="leave_balance_{{$item->id}}"></span>
                                                    {{-- <input id="remaining-leave_{{$item->id}}" type="hidden"> --}}
                                                @endif
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <span class="error_msg" id="c_error_leave_type"></span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2 items-start">
                <div>
                    <label for="leaveRequestX" class="inputLabel">
                        Start Date
                    </label>
                    <input type="date" id="c_start_date" onchange="calculateLeaveDays('c_')" name="start_date" value="{{date('Y-m-d')}}" class="inputFieldBorder">
                    <span class="error_msg" id="c_error_start_date"></span>
                </div>
                <div>
                    <label for="leaveRequestY" class="inputLabel">
                        End Date
                    </label>
                    <input type="date" id="c_end_date" onchange="calculateLeaveDays('c_')" name="end_date" value="{{date('Y-m-d')}}" class="inputFieldBorder">
                    <span class="error_msg" id="c_error_end_date"></span>
                </div>
                <div class="mt-3 self-center">
                    <label for="" class="inputLabel">Intend Of Leave Days: </label>
                    <span id="c_leave_days_count" class="text-[#831b94] italic font-medium"></span>
                    <p id="c_error_leave_days_count" class="text-[#831b94] italic"></p>
                </div>
                <div class="col-span-1 relative">
                    <label for="select_employeeX" class="inputLabel">
                        Select A Leave Reliever
                    </label>
                    <div id="employee-spinner" class="absolute right-10 bottom-4 z-10">
                        <div class="animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-gray-400 rounded-full dark:text-blue-500" role="status" aria-label="loading">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <select id="c_leave_select_reliever" name="reliever_emp_id" class="" placeholder="Select a leave reliever..."></select>
                    <span class="error_msg" id="c_error_leave_select_reliever"></span>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="leave_reasonX" class="inputLabel">
                        Leave Reason
                    </label>
                    <textarea id="c_leave_reason" name="reason" class="inputFieldBorder" rows="2" placeholder="Please tell us your leave reason."></textarea>

                    <span class="error_msg" id="c_error_leave_reason"></span>
                </div>
                @if(in_array(getUserRole(), ['super-admin', 'hr']))
                    <div>
                        <label for="c_leave_remarks" class="inputLabel">
                            Leave Remarks
                        </label>
                        <textarea id="c_leave_remarks" name="remarks" class="inputFieldBorder" rows="2" placeholder="Remarks."></textarea>
                        <span class="error_msg" id="c_error_leave_remarks"></span>
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
                            <input id="fileAttachment" accept=".pdf, .doc, .docx, image/*" type="file" multiple
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
                        <span id="error_file_upload" class="text-xs font-medium text-red-500 mt-2"></span>
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
                                        <div class="absolute bottom-0 left-0 right-0 flex flex-col p-2 text-xs bg-slate-100 bg-opacity-70 z-[101]">
                                            <span class="w-full font-bold text-gray-900 truncate"
                                                  x-text="files[index].name">Loading</span>
                                            <span class="text-xs text-gray-900" x-text="humanFileSize(files[index].size)">...</span>
                                        </div>

                                        <div class="absolute inset-0 transition-colors duration-300 preview" @dragenter="dragenter($event)"
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
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100">
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="createLeaveRequestModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    Cancel
                </button>
                @if(in_array(getUserRole(), ['super-admin', 'hr']))

                    <button id="c_submitBtn_approved" type="submit" name="approval_status" value="2" class="submit-button-green">
                        Approve
                    </button>
                    <button type="submit" name="approval_status" value="1" class="submit-button !bg-neutral-800">
                        Draft
                    </button>
                @else
                    <button type="submit" name="approval_status"  value="1" class="submit-button">
                        Request
                    </button>
                @endif

            </div>
        </div>
    </form>
</x-modals.large-modal>


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
            document.getElementById('fileAttachment').files = dataTransfer.files;

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
            loadFile(file,index) {
                const preview = document.querySelectorAll(".preview");
                const blobUrl = URL.createObjectURL(file);

                preview.forEach(elem => {
                    elem.onload = () => {
                        URL.revokeObjectURL(elem.src); // free memory
                    };
                });

                return blobUrl;
            },
            loadFile(file, index) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const previewElement = document.querySelectorAll(".preview")[index];
                    previewElement.style.backgroundImage = `url(${e.target.result})`;
                    previewElement.style.backgroundSize = "cover";
                    previewElement.style.backgroundPosition = "center";
                };
                reader.readAsDataURL(file);
            },
            addFiles(e) {
                const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp', 'image/webp']

                // Filter out invalid files
                const newFiles = Array.from(e.target.files).filter(file => validTypes.includes(file.type));

                // Show error message if some files were not accepted
                if (newFiles.length < e.target.files.length) {
                    toastr.error('Allowed file types are .pdf, .doc, .docx, and images.');
                    $('#error_file_upload').html('Allowed file types are .pdf, .doc, .docx, and images!');
                } else {
                    $('#error_file_upload').html('');
                }
                // Update the input element's files property only with valid files
                const dataTransfer = new DataTransfer();
                newFiles.forEach((file, index) => {
                    dataTransfer.items.add(file);
                    this.loadFile(file, index); // Load the preview for image files
                });
                document.getElementById('fileAttachment').files = dataTransfer.files;

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
