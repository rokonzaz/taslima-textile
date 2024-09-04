@extends('layouts.app')
@section('title', 'My Requests')
@section('pageTitle', 'My Requests')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'My Requests', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection

@section('content')
    <div class="mt-3 flex items-center gap-3">
        <div class="inline-flex items-center rounded-md shadow-sm">
            <button class="group-btn-l" onclick="createLeaveRequest()">
                <span><i class="ti ti-circle-plus"></i></span>
                <span>Leave Request</span>
            </button>
            <button class="group-btn-m" onclick="lateArrivalRequestModal.showModal()">
                <span><i class="ti ti-circle-plus"></i></span>
                <span>Late Arrival Request</span>
            </button>
            <button class="group-btn-m" onclick="earlyExitRequestModal.showModal()">
                <span><i class="ti ti-circle-plus"></i></span>
                <span>Early Exit Request</span>
            </button>
            {{-- <button class="group-btn-m" onclick="timeTrackerRequestModal.showModal()">
                <span><i class="ti ti-circle-plus"></i></span>
                <span>Add Time Tracker</span>
            </button> --}}
            <button class="group-btn-r" onclick="homeOfficeRequestModal.showModal(); calculateLeaveDays('h_','home-office');">
                <span><i class="ti ti-circle-plus"></i></span>
                <span>Home Office Request</span>
            </button>
        </div>

    </div>
    <div class="grid grid-cols-2 gap-x-4 gap-y-2">
        <x-containers.container-box data="!p-0">
            <div class="border-b px-3 py-1">
                <p class="font-medium py-1">Leave Requests</p>
            </div>
            <div class="">
                <div class="grow grid grid-cols-1 md:grid-cols-3 gap-4 items-end p-3">
                    <div>
                        <input type="text" class="inputField" id="leave-request-search-key" placeholder="Type to search...">
                    </div>
                    <div>
                        <select id="leave-approval-status"  name="select-filter" class="inputFieldCompact select2" style="width: 100%">
                            <option value="">Leave Status (All)</option>
                            @foreach($approvalStatus as $item)
                                <option value="{{$item->id}}" {{request()->has('status') && request('status')==$item->id ? 'selected' : ''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table id="leave-request-data-table" class="w-full text-[13px] display dark:text-white divide-neutral-200 dark:divide-neutral-700" style="width:100%">
                    <thead class="">
                    <tr class="dark:hover:bg-neutral-800">
                       {{-- <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Employee
                        </th>--}}
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Leave Type
                        </th>
                        {{--<th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Issue Date
                        </th>--}}
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Leave Date
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Total Days
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody id="tableData">

                    </tbody>
                </table>
            </div>
        </x-containers.container-box>
        <x-containers.container-box data="!p-0">
            <div class="border-b px-3 py-1">
                <p class="font-medium py-1">Late Arrival Requests</p>
            </div>
            <div class="">
                {{--<div class="flex flex-col ">
                    @php
                        $lateRequests=\App\Models\EmployeeRequest::where('emp_id', $empId)->where('request_type', 'late-arrival')->orderBy('id', 'desc')->limit(10)->get();
                    @endphp
                    <x-tables.late-arrival :data="$lateRequests"></x-tables.late-arrival>
                </div>--}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end p-3">
                    <div>
                        <input type="text" class="inputField" id="late-arrival-search-key" placeholder="Type to search...">
                    </div>
                    <div>
                        <select id="late-arrival-status"  name="select-filter" class="inputFieldCompact select2" style="width: 100%">
                            <option value="">Status (All)</option>
                            @foreach($approvalStatus as $item)
                                <option value="{{$item->id}}"
                                        @if($item->id==1) selected @endif
                                >{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table id="late-arrival-request-data-table" class="w-full text-[13px] display dark:text-white divide-neutral-200 dark:divide-neutral-700" style="width:100%">
                    <thead class="">
                    <tr class="dark:hover:bg-neutral-800">
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Date/Time
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Reason
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Status
                        </th>
                        {{--<th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Action
                        </th>--}}
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </x-containers.container-box>
        <x-containers.container-box data="!p-0">
            <div class="border-b px-3 py-1">
                <p class="font-medium py-1">Early Exit Requests</p>
            </div>
            <div class="">
                <div class="grow grid grid-cols-1 md:grid-cols-3 gap-4 items-end p-3">
                    <div>
                        <input type="text" class="inputField" id="early-exit-search-key" placeholder="Type to search...">
                    </div>
                    <div>
                        <select id="early-exit-status"  name="select-filter" class="inputFieldCompact select2" style="width: 100%">
                            <option value="">Status (All)</option>
                            @foreach($approvalStatus as $item)
                                <option value="{{$item->id}}"
                                        @if($item->id==1) selected @endif
                                >{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table id="early-exit-data-table" class="w-full text-[13px] display dark:text-white divide-neutral-200 dark:divide-neutral-700" style="width:100%">
                    <thead class="">
                    <tr class="dark:hover:bg-neutral-800">
                        {{--<th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Employee
                        </th>--}}
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Date/Time
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Reason
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Status
                        </th>
                        {{--<th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Action
                        </th>--}}
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                {{--<div class="flex flex-col ">
                    @php
                        $earlyExitRequests=\App\Models\EmployeeRequest::where('emp_id', $empId)->where('request_type', 'early-exit')->orderBy('id', 'desc')->limit(10)->get();
                    @endphp
                    <x-tables.late-arrival :data="$earlyExitRequests"></x-tables.late-arrival>
                </div>--}}
            </div>
        </x-containers.container-box>
        <x-containers.container-box data="!p-0">
            <div class="border-b px-3 py-1">
                <p class="font-medium py-1">Home Office Requests</p>
            </div>
            <div class="">
                <div class="grow grid grid-cols-1 md:grid-cols-3 gap-4 items-end p-3">
                    <div>
                        <input type="text" class="inputField" id="home-office-search-key" placeholder="Type to search...">
                    </div>
                    <div>
                        <select id="home-office-status"  name="select-filter" class="inputFieldCompact select2" style="width: 100%">
                            <option value="">Status (All)</option>
                            @foreach($approvalStatus as $item)
                                <option value="{{$item->id}}"
                                        @if($item->id==1) selected @endif
                                >{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table id="home-office-data-table" class="w-full text-[13px] display dark:text-white divide-neutral-200 dark:divide-neutral-700" style="width:100%">
                    <thead class="">
                    <tr class="dark:hover:bg-neutral-800">
                        {{--<th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Employee
                        </th>--}}
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Date/Time
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Reason
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Status
                        </th>
                        {{--<th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Action
                        </th>--}}
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
               {{-- <div class="flex flex-col ">
                    @php
                        $homeOfficeRequests=\App\Models\EmployeeRequest::where('emp_id', $empId)->where('request_type', 'home-office')->orderBy('id', 'desc')->limit(10)->get();
                    @endphp
                    <x-tables.home-office :data="$homeOfficeRequests"></x-tables.home-office>
                </div>--}}
            </div>
        </x-containers.container-box>
    </div>

@endsection
@include('leave.create-leave-request')
@include('my-request.late-arrival.create')
@include('my-request.early-exit.create')
@include('my-request.time-tracker.create')
@include('my-request.home-office.create')
@section('scripts')
    <script>

        let leaveRequestDataTable = new DataTable('#leave-request-data-table', {
            serverSide: true,
            processing:true,
            layout: {
                topStart: {},
                topEnd: {},
                bottomStart: {
                    pageLength: true,
                }
            },
            columnDefs: [
                tableDef.columnDefs,
            ],
            ajax:{
                url: '{{ route('leave.index') }}?a=my-leave-list&filter_approval_status=1',
                dataSrc: function (res) {
                    /*if(res.selectedDate!==''){
                        $('#currentLeaveDate').html('Selected Leave Date: '+res.selectedDate)
                    }else{
                        $('#currentLeaveDate').html('')
                    }*/
                    return res.data;
                }
            },
            order: [[1, 'desc']],
            columns: [
                /*{ name: 'emp_id',  data: 'emp_id',
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'employee');
                    }
                },*/
                { name: 'leave_type',  data: 'leave_type',
                    render:function(data, type, row) {
                        return makeTableCellData(row, 'leave_type');
                    }
                },
                /*{ name: 'issue_date',  data: 'issue_date'},*/
                { name: 'start_date',  data: 'start_date',
                    render:function(data, type, row) {
                        return `<div class="">${row.leave_date}</div>`;
                    }
                },
                { name: 'leave_days',  data: 'leave_days',
                    render:function(data, type, row) {
                        return `<div class="text-center">${row.leave_days}</div>`;
                    }
                },
                { name: 'status',  data: 'status',
                    render:function(data, type, row) {
                        return makeTableCellData(row, 'request-approval-status');
                    }
                },
                { name: 'action', data: 'id',
                    render: function(data, type, row) {
                        console.log("🚀 ~ row:", row)
                        /* let viewBtn = `
                            <div class="inline-block tooltip"  data-tip="View Leave Request">
                                <button onclick="leaveViewModal('Leave Request Details', '${baseUrl}/leave/view/${data}')" class="actionBtn bg-[#831b94] hover:bg-red-700">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </div>
                        `;
                        let editBtn = `
                            <div class="inline-block tooltip"  data-tip="Approve or Reject">
                                <button onclick="leaveEditModal('Edit Leave', '${baseUrl}/leave/edit/${data}')" class="actionBtn bg-neutral-700 hover:bg-neutral-900 disabled:bg-gray-500" >
                                    {!! getUserRole() !== 'employee' ? '<i class="ti ti-shield-star"></i>' : '<i class="ti ti-edit"></i>'!!}
                                </button>
                            </div>

                        `;

                        let printBtn = `
                            <div class="inline-block tooltip"  data-tip="Print Leave">
                                <button onclick="PrintView('${baseUrl}/leave/view/${data}?printView=1')" class="actionBtn bg-blue-800 hover:bg-blue-900">
                                    <i class="ti ti-printer"></i>
                                </button>
                            </div>
                        `;

                        let editPermission = row.approvalPermission;
                        if (!editPermission) {
                            editBtn = '';
                        }

                        return `<div class="flex justify-center gap-1">${viewBtn}${editBtn}${printBtn}</div>`; */
                        let lastChild = false;
                        let dropdownClasses = "dropdown";
                        if (lastChild) {
                            dropdownClasses += " dropdown-top";
                        } else {
                            dropdownClasses += " dropdown-left";
                        }
                        let html=`<div class="flex items-center justify-center font-medium">
                                <div class="${dropdownClasses}">
                                    <button type="button" tabindex="0" role="button" class="flex items-center justify-center text-sm font-semibold text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg size-7 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                        <svg class="flex-none text-gray-600 size-3" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="1" />
                                            <circle cx="12" cy="5" r="1" />
                                            <circle cx="12" cy="19" r="1" />
                                        </svg>
                                    </button>
                                    <ul tabindex="0" class="dropdown-content z-[1] menu px-0 shadow bg-base-100 rounded-md !w-44">
                                        <div class=" px-2">
                                            <button type="button"
                                                class="editUserBtn w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer"
                                                onclick="leaveViewModal('Leave Request Details', '${baseUrl}/leave/view/${data}')">
                                                <i class="fa-regular fa-address-card"></i>
                                                View Details
                                            </button>
                                            ${row.approvalPermission ? `<button type="button"
                                                class="editUserBtn w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer"
                                                onclick="leaveEditModal('Edit Leave', '${baseUrl}/leave/edit/${data}')">
                                                <i class="ti ti-shield-star"></i> Approve</button>`:''}

                                            ${row.deletePermission ? `<button type="button"
                                                onclick="deletePopup('Delete Leave Request', '${row.leave_date}', '${baseUrl}/my-requests/delete/${row.id}')"
                                                class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs text-[#831b94] hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                                <i class="fa-regular fa-trash-can "></i>
                                                Delete
                                            </button>`:'Delete permissions denied'}
                                        </div>
                                    </ul>
                                </div>
                            </div>`;
                        return html;
                    }
                }

            ]
        });
        $('#leave-request-search-key').on('input', debounce(function() {
            let searchText = $('#leave-request-search-key').val().trim();
            leaveRequestDataTable.search(searchText).draw();
        }, 500));
        $('#leave-approval-status, #approval_status, #date').change(function (){
            let leave_type = $('#leave_type').val();
            let approval_status = $('#leave-approval-status').val();
            let isMyLeaveList = $('#isMyLeaveList').val();
            let date = $('#date').val();
            leaveRequestDataTable.ajax.url(`{{ route('leave.index') }}?a=my-leave-list&filter_approval_status=${approval_status}`).load();
        });


        let lateArrivalDataTable = new DataTable('#late-arrival-request-data-table', {
            serverSide: true,
            processing:true,
            layout: {
                topStart: {},
                topEnd: {},
                bottomStart: {
                    pageLength: true,
                }
            },
            order: [[0, 'desc']],
            columnDefs: [
                tableDef.columnDefs,
            ],
            ajax:{
                url: `${baseUrl}/request-approvals?a=my-request&requestType=late-arrival&status=1`,
                dataSrc: function (res) {
                    return res.data;
                }
            },
            columns: [

                { name: 'dateTime',  data: 'date',
                    render:function(data, type, row) {
                        return makeTableCellData(row, 'dateTime');
                    }
                },
                { name: 'reason',  data: 'reason',
                    render:function(data, type, row) {
                        return makeTableCellData(row, 'request-reason');
                    }
                },
                { name: 'status',  data: 'status',
                    render:function(data, type, row) {
                        return makeTableCellData(row, 'request-approval-status');
                    }
                },
                /*{ name: 'action', data: 'id',
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'request-approval-action', { title: 'Late Arrival' });
                    }
                }*/
            ]
        });
        $('#late-arrival-search-key').on('input', debounce(function() {
            let searchText = $('#late-arrival-search-key').val().trim();
            lateArrivalDataTable.search(searchText).draw();
        }, 500));
        $('#late-arrival-status, #organization, #designation').change(function (){
            let status = $('#late-arrival-status').val();
            console.log(status)
            lateArrivalDataTable.ajax.url(`${baseUrl}/request-approvals?a=my-request&requestType=late-arrival&status=${status}`).load();
        });


        let earlyExitDataTable = new DataTable('#early-exit-data-table', {
            serverSide: true,
            processing:true,
            layout: {
                topStart: {},
                topEnd: {},
                bottomStart: {
                    pageLength: true,
                }
            },
            order: [[0, 'desc']],
            columnDefs: [
                tableDef.columnDefs,
            ],
            ajax:{
                url: `${baseUrl}/request-approvals?a=my-request&requestType=early-exit&status=1`,
                dataSrc: function (res) {
                    return res.data;
                }
            },
            columns: [
                /*{ name: 'emp_id',  data: 'emp_id',
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'employee');
                    }
                },*/
                { name: 'dateTime',  data: 'date',
                    render:function(data, type, row) {
                        return makeTableCellData(row, 'dateTime');
                    }
                },
                { name: 'reason',  data: 'reason',
                    render:function(data, type, row) {
                        return makeTableCellData(row, 'request-reason');
                    }
                },
                { name: 'status',  data: 'status',
                    render:function(data, type, row) {
                        return makeTableCellData(row, 'request-approval-status');
                    }
                },
                /*{ name: 'action', data: 'id',
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'request-approval-action', { title: 'Early Exit' });
                    }
                }*/

            ]
        });
        $('#early-exit-search-key').on('input', debounce(function() {
            let searchText = $('#early-exit-search-key').val().trim();
            earlyExitDataTable.search(searchText).draw();
        }, 500));
        $('#early-exit-status, #organization, #designation').change(function (){
            let status = $('#early-exit-status').val();
            console.log(status)
            earlyExitDataTable.ajax.url(`${baseUrl}/request-approvals?a=my-request&requestType=early-exit&status=${status}`).load();
        });

        let homeOfficeDataTable = new DataTable('#home-office-data-table', {
            serverSide: true,
            processing:true,
            layout: {
                topStart: {},
                topEnd: {},
                bottomStart: {
                    pageLength: true,
                }
            },
            order: [[0, 'desc']],
            columnDefs: [
                tableDef.columnDefs,
            ],
            ajax:{
                url: `${baseUrl}/request-approvals?a=my-request&requestType=home-office&status=1`,
                dataSrc: function (res) {
                    return res.data;
                }
            },
            /* ajax:{
                url: '{{ route('request-approvals.index', ['requestType'=>'home-office'])}}',
                dataSrc: function (res) {
                    return res.data;
                }
            }, */
            columns: [
                /*{ name: 'emp_id',  data: 'emp_id',
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'employee');
                    }
                },*/
                { name: 'dateTime',  data: 'start_end_date',
                    render:function(data, type, row) {
                        let html=``;
                        if(row.total_day>1){
                            html=`${row.start_end_date} <span class="text-teal-600">(${row.total_day})</span>`
                        }else{
                            html=row.start_end_date
                        }
                        return `<div>${html} </div>`;
                    }
                },
                { name: 'reason',  data: 'reason',
                    render:function(data, type, row) {
                        return makeTableCellData(row, 'request-reason');
                    }
                },
                { name: 'status',  data: 'status',
                    render:function(data, type, row) {
                        return makeTableCellData(row, 'request-approval-status');
                    }
                },
                /*{ name: 'action', data: 'id',
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'request-approval-action', { title: 'Home Office' });
                    }
                }*/

            ]
        });
        $('#home-office-search-key').on('input', debounce(function() {
            let searchText = $('#home-office-search-key').val().trim();
            earlyExitDataTable.search(searchText).draw();
        }, 500));
        $('#home-office-status').change(function (){
            let status = $('#home-office-status').val();
            console.log(status)
            homeOfficeDataTable.ajax.url(`${baseUrl}/request-approvals?a=my-request&requestType=home-office&status=${status}`).load();
        });






        function createLeaveRequest() {
            // initializeSelectize('c_leave_select_employee', 'get-employee-list', '', '', 'leave');
            initializeSelectize('c_leave_select_reliever', 'get-employee-list', '', '', '');
            createLeaveRequestModal.showModal();
            changeRequisitionType();
            calculateLeaveDays('c_');
        }

        $('input[name="late_reason"]').change(function () {
            console.log(1)
            $selectedLateReason = $('input[name="late_reason"]:checked').val();
            selectedLateReason === 'Others' ? $('#late_note_start_mark').text('*') : $('#late_note_start_mark').text('');
        });
        $('input[name="early_exit_reason"]').change(function () {
            $selectedLateReason = $('input[name="early_exit_reason"]:checked').val();
            selectedLateReason === 'Others' ? $('#early_exit_note_start_mark').text('*') : $('#early_exit_note_start_mark').text('');
        });
    </script>
    {{-- <script>
        let counterInterval;
        let hours = 0, minutes = 0, seconds = 0;
        let startTime, endTime;
        let totalSeconds = 0;
        let isRunning = false;

        function updateCounter() {
          seconds++;
          if (seconds >= 60) {
            seconds = 0;
            minutes++;
            if (minutes >= 60) {
              minutes = 0;
              hours++;
            }
          }

          $('#hours').text(hours.toString().padStart(2, '0'));
          $('#minutes').text(minutes.toString().padStart(2, '0'));
          $('#seconds').text(seconds.toString().padStart(2, '0'));
        }

        function updateTotalTime() {
          let totalH = Math.floor(totalSeconds / 3600);
          let totalM = Math.floor((totalSeconds % 3600) / 60);
          let totalS = Math.floor(totalSeconds % 60);
          $('#totalTime').text(`Total Time: ${totalH}h ${totalM}m ${totalS}s`);
        }

        $('#toggleTrackingBtn').click(function() {
          if (isRunning) {
            clearInterval(counterInterval);
            endTime = new Date();
            let duration = ((endTime - startTime) / 1000); // in seconds
            totalSeconds += duration;
            let h = Math.floor(duration / 3600);
            let m = Math.floor((duration % 3600) / 60);
            let s = Math.floor(duration % 60);

            let entryId = `entry-${Date.now()}`;
            let entryHtml = `
              <div class="p-2 text-white flex justify-between items-center bg-neutral rounded-md entry" id="${entryId}">
                <span>${startTime.toLocaleString()} - ${endTime.toLocaleString()}</span>
                <span>${h}h ${m}m ${s}s</span>
                <button class="dlt-icon" onclick="deleteEntry('${entryId}', ${duration})"><i class="fa-regular fa-trash-can"></i></button>
              </div>
            `;
            $('#timeEntries').append(entryHtml);
            $('#noEntriesMessage').hide();
            updateTotalTime();
            $(this).html('<i class="fa-solid fa-play"></i>').removeClass('stop-btn').addClass('play-btn').attr('data-tip','start tracker');
            isRunning = false;

          } else {
            if (counterInterval) clearInterval(counterInterval);
            hours = 0;
            minutes = 0;
            seconds = 0;
            startTime = new Date();
            counterInterval = setInterval(updateCounter, 1000);
            $(this).html('<i class="fa-solid fa-stop"></i>').removeClass('play-btn').addClass('stop-btn').attr('data-tip','stop tracker');
            isRunning = true;


            $.ajax({
                url: `${baseUrl}/my-requests/request?a=time-track&type=start`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Adjust selector based on your CSRF token location
                },
                success: function(res) {
                    if (res.status === 1) {

                        toastr.success(res.msg)

                    }else{
                        toastr.error(res.msg);
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });

          }
        });

        function deleteEntry(entryId, duration) {
          $(`#${entryId}`).remove();
          totalSeconds -= duration;
          updateTotalTime();
          if ($('#timeEntries').children().length === 0) {
            $('#noEntriesMessage').show();
          }
        }
    </script> --}}
    <script>
        let counterInterval;
        let hours = 0, minutes = 0, seconds = 0;
        let startTime, endTime;
        let totalSeconds = 0;
        let isRunning = false;

        function updateCounter() {
            seconds++;
            if (seconds >= 60) {
                seconds = 0;
                minutes++;
                if (minutes >= 60) {
                    minutes = 0;
                    hours++;
                }
            }

            $('#hours').text(hours.toString().padStart(2, '0'));
            $('#minutes').text(minutes.toString().padStart(2, '0'));
            $('#seconds').text(seconds.toString().padStart(2, '0'));
        }

        function updateTotalTime() {
            let totalH = Math.floor(totalSeconds / 3600);
            let totalM = Math.floor((totalSeconds % 3600) / 60);
            let totalS = Math.floor(totalSeconds % 60);
            $('#totalTime').text(`Total Time: ${totalH}h ${totalM}m ${totalS}s`);
        }

        function checkTrackerStatus() {
            $.ajax({
                url: `${baseUrl}/my-requests/request?a=time-track&type=status`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Adjust selector based on your CSRF token location
                },
                success: function(res) {
                    if (res.status === 1 && res.isRunning) {
                        startTime = new Date(res.startTime);
                        let now = new Date();
                        let elapsedSeconds = Math.floor((now - startTime) / 1000);
                        totalSeconds += elapsedSeconds;
                        hours = Math.floor(elapsedSeconds / 3600);
                        minutes = Math.floor((elapsedSeconds % 3600) / 60);
                        seconds = Math.floor(elapsedSeconds % 60);
                        counterInterval = setInterval(updateCounter, 1000);
                        $('#toggleTrackingBtn').html('<i class="fa-solid fa-stop"></i>').removeClass('play-btn').addClass('stop-btn').attr('data-tip', 'stop tracker');
                        isRunning = true;
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching tracker status:', error);
                }
            });
        }

        function formatDateToMySQL(datetime) {
            return datetime.toISOString().slice(0, 19).replace('T', ' ');
        }

        $('#toggleTrackingBtn').click(function() {
            if (isRunning) {
                clearInterval(counterInterval);
                endTime = new Date();
                let duration = ((endTime - startTime) / 1000); // in seconds
                totalSeconds += duration;
                let h = Math.floor(duration / 3600);
                let m = Math.floor((duration % 3600) / 60);
                let s = Math.floor(duration % 60);

                let entryId = `entry-${Date.now()}`;
                let entryHtml = `
                <div class="p-2 text-white flex justify-between items-center bg-neutral rounded-md entry" id="${entryId}">
                    <span>${startTime.toLocaleString()} - ${endTime.toLocaleString()}</span>
                    <span>${h}h ${m}m ${s}s</span>
                    <button class="dlt-icon" onclick="deleteEntry('${entryId}', ${duration})"><i class="fa-regular fa-trash-can"></i></button>
                </div>
                `;
                $('#timeEntries').append(entryHtml);
                $('#noEntriesMessage').hide();
                updateTotalTime();
                $(this).html('<i class="fa-solid fa-play"></i>').removeClass('stop-btn').addClass('play-btn').attr('data-tip','start tracker');
                isRunning = false;

                $.ajax({
                    url: `${baseUrl}/my-requests/request?a=time-track&type=stop`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        // endTime: formatDateToMySQL(endTime),
                        duration: duration
                    },
                    success: function(res) {
                        if (res.status === 1) {
                            toastr.success(res.msg)
                        } else {
                            toastr.error(res.msg);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });

            } else {
                if (counterInterval) clearInterval(counterInterval);
                hours = 0;
                minutes = 0;
                seconds = 0;
                startTime = new Date();
                counterInterval = setInterval(updateCounter, 1000);
                $(this).html('<i class="fa-solid fa-stop"></i>').removeClass('play-btn').addClass('stop-btn').attr('data-tip','stop tracker');
                isRunning = true;

                $.ajax({
                    url: `${baseUrl}/my-requests/request?a=time-track&type=start`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    /* data: {
                        startTime: formatDateToMySQL(startTime)
                    }, */
                    success: function(res) {
                        if (res.status === 1) {
                            toastr.success(res.msg)
                        } else {
                            toastr.error(res.msg);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }
        });


        function deleteEntry(entryId, duration) {
            $(`#${entryId}`).remove();
            totalSeconds -= duration;
            updateTotalTime();
            if ($('#timeEntries').children().length === 0) {
                $('#noEntriesMessage').show();
            }
        }

        $(document).ready(function() {
            checkTrackerStatus();
        });
    </script>
@endsection



