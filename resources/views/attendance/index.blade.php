@extends('layouts.app')
@section('title', 'Employee Attendance')
@section('pageTitle', 'Employee Attendance')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Employee Attendance', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')
    {{--<div class="">

        <p class="text-right">Last Sync:</p>
        <div class="flex items-center gap-1 truncate">
            <span class="text-[#831b94]" id="lastSyncDate"></span>
            <span class="text-[#831b94]" id="lastSyncTime"></span>
        </div>
    </div>--}}
@endsection

@section('content')
    <x-containers.container-box>
        <div class="flex justify-between items-end gap-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end shrink">
                <div>
                    <input type="text" class="inputFieldCompact" id="searchKey" placeholder="Type to search...">
                </div>
                <div>
                    <select id="organization"  name="select-filter" class="inputFieldCompact select2">
                        <option value="">Organization (All)</option>
                        @foreach($organizations as $item)
                            <option value="{{$item->id}}" {{request()->has('organization') ? request('organization') : ''}}>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select id="department"  class="inputFieldCompact select2">
                        <option value="">Department (All)</option>
                        @foreach($departments as $item)
                            <option value="{{$item->id}}" @if(request()->has('department') && request('department')==$item->id) selected @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select id="designation"  name="designation" class="inputFieldCompact select2">
                        <option value="">Designation (All)</option>
                        @foreach($designations as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="inline-flex items-end gap-3">
                    <div>
                        <input type="date" id="date"  name="date" value="{{date('Y-m-d')}}" class="inputFieldCompact">
                    </div>
                    <div class="">
                        <div class="inline-block tooltip"  data-tip="Reset All Filters">
                            <button type="button" class="btn-red w-9 aspect-square flex items-center justify-center" id="reset">
                                <i class="fas fa-undo text-center text-sm"></i>
                                <span
                                    class="absolute z-10 invisible inline-block px-2 py-1 text-white transition-opacity bg-[#831b94] rounded-lg shadow-md opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible"
                                    role="tooltip">
                            Reset
                        </span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="w-32 h-full flex flex-col items-end justify-end gap-2 relative">
                <div class="flex items-center align-middle gap-x-2 mb-2.5">
                    <div>
                        <h5 class="text-sm text-gray-800 dark:text-white">Action: </h5>
                    </div>
                    <div class="inline-block tooltip tooltip-left" data-tip="Employee ID Bulk Tag">
                        <a href="{{route('attendance.bulk-tag-machine-id')}}" id="sync-biometric-data" class="actionBtn bg-[#831b94]" >
                            <i class="ti ti-tags"></i>
                        </a>
                    </div>
                    {{-- <div class="inline-block tooltip tooltip-left" data-tip="Employee ID Bulk Tag">
                        <a href="{{route('attendance.bulk-tag')}}" id="sync-biometric-data" class="actionBtn bg-[#831b94]" >
                            <i class="ti ti-tags"></i>
                        </a>
                    </div>
                    <div class="inline-block tooltip tooltip-left" data-tip="Sync now">
                        <button type="button" id="sync-biometric-data" onclick="attendanceSyncModal.showModal()" class=" actionBtn bg-[#831b94]">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>
    </x-containers.container-box>
    <div class="flex items-center justify-center">
        <div class="mt-2 text-[#831b94] text-lg font-medium" id="currentDate">Attendance Date: {{date('d M Y')}}</div>
    </div>
    <x-containers.container-box data="!p-0">
        <table id="dataTable" class="w-full display dark:text-white divide-neutral-200 dark:divide-neutral-700">
            <thead class="">
            <tr class="dark:hover:bg-neutral-800">
                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                    EMP ID
                </th>
                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                    NAME
                </th>
                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                    Slot
                </th>
                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                    Entry Time
                </th>
                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                    Exit Time
                </th>
                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                    Timing
                </th>
                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                    Remarks
                </th>

                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                    Details
                </th>
            </tr>
            </thead>
            <tbody id="tableData">

            </tbody>
        </table>
    </x-containers.container-box>

@endsection

@section('scripts')
    <script>
        $(function () {
            $('#syncBtn').click(function () {
                $('#syncBtn').html($('#spinner-small-white').html() + ' Syncing');
            });
        });
    </script>
    <script>
        let dataTable = new DataTable('#dataTable', {
            serverSide: true,
            processing:true,
            /*language: {
                processing: $("#spinner-large").html(),
            },*/
            layout: tableDef.layout,
            columnDefs: [
                tableDef.columnDefs,
            ],
            ajax:{
                url: `{{ route('attendance.index') }}?organization=${$('#organization').val()}`,
                dataSrc: function (res) {
                    $('#lastSyncDate').html(res.lastSyncDate)
                    $('#lastSyncTime').html(res.lastSyncTime)
                    $('#currentDate').html('Attendance Date: '+res.selectedDate)
                    return res.data;
                }
            },
            order: [[1, 'asc']],
            columns: [
                    { name: 'employeeID',  data: 'emp_id', render: function(data, type, row) {
                        let html=`<div class="flex flex-col items-center">
                                <a href="${baseUrl}/employees/view/${row.emp_id}" class="button-red truncate">${data}</a>
                                <span class=" ${row.biometric_id==='' ? `text-[#831b94]` : ``}"><i class="fa-solid fa-fingerprint"></i> ${row.biometric_id}</span>
                            </div>`;
                        return html;
                    },
                    className: 'dt-center',
                    width:'40px',
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('padding', '8px 12px')
                    },
                },
                { name: 'name',  data: 'name', searchable:false,
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'employee');
                    }
                },
                { name: 'slot',  data: 'dutySlotName',
                    render: function(data, type, row) {
                        let html=`
                                <div class="flex flex-col text-sm">
                                    <span class="font-medium">${row.dutySlotName}</span>
                                    <span class="font-medium">${row.dutySlotTitle??''}</span>
                                    ${row.leave
                                        ? `<span class="text-[#831b94]">Leave: ${row.leave}</span>`
                                        :
                                            `
                                                ${''/* <span>ST: ${row.dutyStartTime}</span>
                                                <span>TT: ${row.dutyThresholdTime}</span>
                                                <span>ET: ${row.dutyEndTime}</span> */ }
                                            `
                                    }

                                </div>
                               `
                        return html;
                    }
                },
                { name: 'clockIn',  data: 'clockIn',
                    render:function(data, type, row) {
                        if(row.leave) {
                            return `<span class="text-[#831b94]">Leave</span>`;
                        }
                        let html=``;
                        if(data){
                            html=`
                                    <div class="flex flex-col">
                                        <span class="font-medium">${row.clockInDetails.is_manual===0 ? `<i class="fa-solid fa-fingerprint text-green-700"></i>` : `<i class="fa-solid fa-file-import text-[#831b94]"></i>`}
                                            ${formatDateTime(data, 'time-with-a-no-sec')}
                                        </span>
                                        <span class="ml-5 text-[11px] text-teal-600">${row.formattedDate}</span>

                                        <span class="max-w-48 line-clamp-2">
                                            ${row.clockInDetails.is_manual === 1
                                                ?
                                                    row.clockInDetails.remarks && `
                                                        <span class="font-medium">Reason:</span> ${row.clockInDetails.remarks}
                                                    `
                                                : ''
                                            }
                                        </span>

                                    </div>
                                `
                        }
                        return html;
                    }
                },
                { name: 'clockOut',  data: 'clockOut',
                    render:function(data, type, row) {
                        if(row.leave) {
                            return `<span class="text-[#831b94]">Leave</span>`;
                        }
                        let html=``;
                        if(data){
                            html=`
                                    <div class="flex flex-col">
                                        <span class="font-medium">${row.clockOutDetails.is_manual===0 ? `<i class="fa-solid fa-fingerprint text-green-700"></i>` : `<i class="fa-solid fa-file-import text-[#831b94]"></i>`} ${formatDateTime(data, 'time-with-a-no-sec')}</span>
                                        <span class="ml-5 text-[11px] text-teal-600">${row.formattedDate}</span>
                                        <span class="max-w-48 line-clamp-2">
                                            ${row.clockOutDetails.is_manual===1
                                                ? row.clockOutDetails.remarks ? `<span class="font-medium">Reason:</span> ${row.clockOutDetails.remarks}` : ``
                                                : ``
                                            }
                                        </span>
                                    </div>
                                `
                        }
                        return html;
                    }
                },
                { name: 'timing',  data: 'id',
                    render:function(data, type, row) {
                        if(row.leave) {
                            return `<span class="text-[#831b94]">Leave</span>`;
                        }
                        return data ? `
                            <div class="flex flex-col">
                                <p><span class="font-medium">LA:</span> ${row.late ? `<span class="text-[#831b94]">${row.late}</span>` : ''}</p>
                                <p><span class="font-medium">EE:</span> ${row.earlyLeaving ? `<span class="text-[#831b94]">${row.earlyLeaving}</span>` : ''}</p>
                                <p><span class="font-medium">OT:</span> ${row.overtime ? `<span class="text-green-600">${row.overtime}</span>` : ''}</p>
                            </div>
                        ` : '';
                    }
                },
                { name: 'additionalDetails',  data: 'additionalDetails',
                    render:function(data, type, row) {

                        /*let html=``;
                        let sortedData=data;
                        let x=sortedData[0];
                        //sortedData.map(x=>{
                        if(x) {
                            html = `
                                    <div class="">
                                        ${x.start_time
                                            ? `<p><i class="fa-solid fa-stopwatch"></i> ${formatDateTime(`${x.date} ${x.start_time}`, 'time-with-a-no-sec')} - ${formatDateTime(`${x.date} ${x.end_time}`, 'time-with-a-no-sec')}</p> <p>${x.reason}</p> ${x.additional_note ? `<p class="text-xs font-medium">**Note: <span class="text-[#831b94] italic">${x.additional_note}</span></p>` : ''}`
                                            : `
                                                <p><i class="fa-solid fa-stopwatch"></i> ${x.reason}</p>
                                                <p>${x.additional_note}</p>

                                              `
                                        }

                                    </div>
                                  `;
                        }*/
                        //})
                        let html=`<div class="flex flex-col items-center max-w-24">
                                    ${row.lateEntryNote ? `
                                        <div class="tooltip" data-tip="Late Arrival Note: ${row.lateEntryNote}">
                                          <div class="w-32 truncate text-left"><span class="text-xs">${row.lateEntryNote ? `LA Note: ${row.lateEntryNote}` : ``}</span></div>
                                        </div>
                                    ` : ''}
                                    ${row.earlyExitNote ? `
                                        <div class="tooltip" data-tip="Early Exit Note: ${row.earlyExitNote}">
                                          <div class="w-32 truncate text-left"><span class="text-xs">${row.earlyExitNote ? `EE Note: ${row.earlyExitNote}` : ``}</span></div>
                                        </div>
                                    ` : ''}
                                    ${row.homeOfficeNote ? `
                                        <div class="tooltip" data-tip="Home Office Note: ${row.homeOfficeNote}">
                                          <div class="w-32 truncate text-left"><span class="text-xs">${row.homeOfficeNote ? `HO Note: ${row.homeOfficeNote}` : ``}</span></div>
                                        </div>
                                    ` : ''}
                                </div>`
                        return html;
                    }
                },
                { name: 'attendance',  data: 'attendance',
                    render:function(data, type, row) {
                        let att=``;
                        {data.length>0?
                            data.map((x,index)=>{
                                let dateTimeString = x.DateTime;
                                let dateTime = new Date(dateTimeString);
                                let formattedTime = dateTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                                att+= `<div class="flex justify-between items-start gap-3 px-4 overflow-y-scroll max-h-[55vh]">
                                            <div class="w-full flex flex-col gap-2 py-2">
                                                <span class="font-medium flex justify-start items-center gap-2">${x.is_manual===0 ? `<i class="fa-solid fa-fingerprint text-green-700"></i>` : `<i class="fa-solid fa-file-import text-[#831b94]"></i>`} ${formattedTime} </span>

                                                ${x.is_manual === 1 && x.remarks
                                                    ? `<span class="py-1 px-2 ml-5 border bg-neutral-50 rounded-md">${x.remarks}</span>`
                                                    : ``
                                                }
                                            </div>
                                            <div class="py-2">
                                                <span class="font-medium flex justify-start items-center gap-2"><i class="fa-regular fa-clock"></i> ${index+1} </span>

                                            </div>
                                    </div>

                                    `;
                            })
                        : att+=`
                            <div class="flex justify-center items-center p-4">
                                <span class="font-medium font-italic">No attendance logs found !</span>
                            </div>
                        `}
                        let html=`
                            <div class="flex items-center justify-center">
                                <div class="dropdown dropdown-left">
                                    <button type="button" tabindex="0" role="button" class="flex items-center justify-center text-sm font-semibold text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg size-9 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                        <svg class="flex-none text-gray-600 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="1" />
                                            <circle cx="12" cy="5" r="1" />
                                            <circle cx="12" cy="19" r="1" />
                                        </svg>
                                    </button>
                                    <ul tabindex="0" class="dropdown-content z-[1] menu px-0 shadow bg-base-100 rounded-md min-w-60">
                                        <div class=" px-2">
                                            <button type="button"
                                                class="editUserBtn w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer"
                                                onclick="addManualAttendancePopup('${row.emp_id}', '${row.date}', 'Add Manual Attendance')">
                                                <i class="fa-solid fa-user-clock"></i>
                                                Add Manual Attendance
                                            </button>

                                            <button type="button"
                                                onclick="openAttendanceLogsPopUp('${row.id}','Attendance Logs')"
                                                class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                                <i class="fa-solid fa-list-check"></i>
                                                Attendance Logs
                                            </button>
                                        </div>
                                    </ul>
                                </div>
                                <div class="hidden" id="dot-${row.id}">${att}</div>
                            </div>
                        `
                        return html;
                    }
                },


            ]
        });
        $('#searchKey').on('input', debounce(function() {
            let searchKey = $('#searchKey').val();
            let department = $('#department').val();
            let organization = $('#organization').val();
            let designation = $('#designation').val();
            let date = $('#date').val();
            dataTable.ajax.url(`{{ route('attendance.index') }}?searchKey=${searchKey}&department=${department}&organization=${organization}&designation=${designation}&date=${date}`).load();
        }, 500));

        $('#department, #organization, #designation, #date').change(function (){
            let searchKey = $('#searchKey').val();
            let department = $('#department').val();
            let organization = $('#organization').val();
            let designation = $('#designation').val();
            let date = $('#date').val();
            dataTable.ajax.url(`{{ route('attendance.index') }}?searchKey=${searchKey}&department=${department}&organization=${organization}&designation=${designation}&date=${date}`).load();
        });
        $('#reset').click(function (){
            $('#department').val('')
            $('#organization').val('');
            $('#designation').val('');
            var currentDate = new Date();
            var formattedDate = currentDate.toISOString().slice(0, 10);
            $('#date').val(formattedDate);
            let department = '';
            let organization = '';
            let designation = '';
            let date = '';
            $('#searchKey').val('');
            dataTable.ajax.url(`{{ route('attendance.index') }}?department=${department}&organization=${organization}&designation=${designation}&date=${date}&searchKey=`).load();
        });
        $('#sync-biometric-data').click(function (){

        });
    </script>
@endsection



