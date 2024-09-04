@extends('layouts.app')
@section('title', 'Request Approvals')
@section('pageTitle', 'Request Approvals')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Request Approvals', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection

@section('content')
    @php
        $leaveRequest=\App\Models\Leave::where('approval_status', 1)->whereIn('emp_id', $manageableEmployeesIDs);
        $leaveRequestCount=$leaveRequest->count();


        $query=\App\Models\EmployeeRequest::onlyPending()->whereIn('emp_id', $manageableEmployeesIDs);
        //$lateRequests=
        $lateRequestsPendingCount=$query->onlyLateArrivalRequest()->count();

        //$earlyExitRequests=\App\Models\EmployeeRequest::onlyEarlyExitRequest()->onlyPending()->whereIn('emp_id', $manageableEmployeesIDs);
        $earlyExitRequestsPendingCount=$query->onlyEarlyExitRequest()->count();

        //$homeOfficeRequests=\App\Models\EmployeeRequest::onlyHomeOfficeRequest()->onlyPending()->whereIn('emp_id', $manageableEmployeesIDs);
        $homeOfficeRequestsPendingCount=$query->onlyHomeOfficeRequest()->count();


    @endphp

    @php
        $active='leave-request';
        if(request()->has('active')) $active=request('active');
        if(!in_array($active, ["leave-request","late-arrival","early-exit","home-office"])) $active='leave-request';
    @endphp
    <x-containers.container-box>
        <div class="border-b border-gray-200 dark:border-neutral-700">
            <nav class="flex gap-x-6" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                <button type="button" onclick="setActiveTab('leave-request')" class="{{$active=='leave-request' ? 'active' : ''}} hs-tab-active:font-semibold hs-tab-active:border-red-600 hs-tab-active:text-[#831b94] py-2 pt-1 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-[#831b94] focus:outline-none focus:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-red-500" id="tabs-with-badges-item-1" aria-selected="true" data-hs-tab="#tabs-leave-request" aria-controls="tabs-leave-request" role="tab">
                    Leave Request
                    <span class="hs-tab-active:bg-red-100 hs-tab-active:text-[#831b94] dark:hs-tab-active:bg-red-800 dark:hs-tab-active:text-white ms-1 py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">{{$leaveRequestCount}} Pending</span>
                </button>
                <button type="button" onclick="setActiveTab('late-arrival')" class="{{$active=='late-arrival' ? 'active' : ''}} hs-tab-active:font-semibold hs-tab-active:border-red-600 hs-tab-active:text-[#831b94] py-2 pt-1 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-[#831b94] focus:outline-none focus:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-red-500" id="tabs-with-badges-item-2" aria-selected="false" data-hs-tab="#tabs-late-arrival" aria-controls="tabs-late-arrival" role="tab">
                    Late Arrival Request
                    <span class="hs-tab-active:bg-red-100 hs-tab-active:text-[#831b94] dark:hs-tab-active:bg-red-800 dark:hs-tab-active:text-white ms-1 py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">{{$lateRequestsPendingCount}} Pending</span>
                </button>
                <button type="button" onclick="setActiveTab('early-exit')" class="{{$active=='early-exit' ? 'active' : ''}} hs-tab-active:font-semibold hs-tab-active:border-red-600 hs-tab-active:text-[#831b94] py-2 pt-1 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-[#831b94] focus:outline-none focus:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-red-500" id="tabs-with-badges-item-2" aria-selected="false" data-hs-tab="#tabs-early-exit" aria-controls="tabs-early-exit" role="tab">
                    Early Exit Request
                    <span class="hs-tab-active:bg-red-100 hs-tab-active:text-[#831b94] dark:hs-tab-active:bg-red-800 dark:hs-tab-active:text-white ms-1 py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">{{$earlyExitRequestsPendingCount}} Pending</span>
                </button>
                <button type="button" onclick="setActiveTab('home-office')" class="{{$active=='home-office' ? 'active' : ''}} hs-tab-active:font-semibold hs-tab-active:border-red-600 hs-tab-active:text-[#831b94] py-2 pt-1 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-[#831b94] focus:outline-none focus:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-red-500" id="tabs-with-badges-item-2" aria-selected="false" data-hs-tab="#tabs-home-office" aria-controls="tabs-home-office" role="tab">
                    Home Office Request
                    <span class="hs-tab-active:bg-red-100 hs-tab-active:text-[#831b94] dark:hs-tab-active:bg-red-800 dark:hs-tab-active:text-white ms-1 py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">{{$homeOfficeRequestsPendingCount}} Pending</span>
                </button>
            </nav>
        </div>

        <div class="mt-3">
            <div id="tabs-leave-request" class="{{$active=='leave-request' ? 'active' : 'hidden'}}" role="tabpanel" aria-labelledby="tabs-with-badges-item-1">
                <div class="grow grid grid-cols-1 md:grid-cols-5 gap-4 items-end ">
                    <div>
                        <input type="text" class="inputField" id="leave-request-search-key" placeholder="Type to search...">
                    </div>
                    <div>
                        <select id="leave-approval-status" name="select-filter" class="inputFieldCompact select2" style="width: 100%">
                            <option value="">Leave Status (All)</option>
                            @foreach($approvalStatus as $item)
                                <option value="{{ $item->id }}"
                                    {{ request()->has('filter_approval_status')
                                        ? (request('filter_approval_status') == $item->id ? 'selected' : '')
                                        : ($item->id == 1 ? 'selected' : '') }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table id="leave-request-data-table" class="w-full text-[13px] display dark:text-white divide-neutral-200 dark:divide-neutral-700" style="width:100%">
                    <thead class="">
                    <tr class="dark:hover:bg-neutral-800">
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Employee
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Leave Type
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Issue Date
                        </th>
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
            <div id="tabs-late-arrival" class="{{$active=='late-arrival' ? 'active' : 'hidden'}}" role="tabpanel" aria-labelledby="tabs-with-badges-item-2">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end ">
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
                            Employee
                        </th>
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
                            Role As
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
                    <tbody>

                    </tbody>
                </table>

            </div>
            <div id="tabs-early-exit" class="{{$active=='early-exit' ? 'active' : 'hidden'}}" role="tabpanel" aria-labelledby="tabs-with-badges-item-3">
                <div class="grow grid grid-cols-1 md:grid-cols-5 gap-4 items-end ">
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
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Employee
                        </th>
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
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
            <div id="tabs-home-office" class="{{$active=='home-office' ? 'active' : 'hidden'}}" role="tabpanel" aria-labelledby="tabs-with-badges-item-3">
                <div class="grow grid grid-cols-1 md:grid-cols-5 gap-4 items-end ">
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
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Employee
                        </th>
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
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>

        {{--<div class="border-b px-3 py-1">
            <p class="font-medium py-1">Late Arrival Requests</p>
        </div>
        <div class="p-3">
            <div class="flex flex-col ">

                <x-tables.late-arrival-admin :data="$lateRequests"></x-tables.late-arrival-admin>
            </div>
        </div>--}}
    </x-containers.container-box>
@endsection

@section('scripts')
    <script>
        let status = $('#leave-approval-status').val();
        let leaveRequestDataTable = new DataTable('#leave-request-data-table', {
            serverSide: true,
            processing:true,
            layout: tableDef.layout,
            columnDefs: [
                tableDef.columnDefs,
            ],
            ajax:{
                url: `{{ route('leave.index', ['q'=>'request-approvals'])}}&filter_approval_status=${status}`,
                dataSrc: function (res) {
                    /*if(res.selectedDate!==''){
                        $('#currentLeaveDate').html('Selected Leave Date: '+res.selectedDate)
                    }else{
                        $('#currentLeaveDate').html('')
                    }*/
                    return res.data;
                }
            },
            order: [[3, 'desc']],
            columns: [
                { name: 'emp_id',  data: 'emp_id',
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'employee');
                    }
                },
                { name: 'leave_type',  data: 'leave_type',
                    render:function(data, type, row) {
                        return makeTableCellData(row, 'leave_type');
                    }
                },
                { name: 'issue_date',  data: 'issue_date'},
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
                        let viewBtn = `
                            <div class="inline-block tooltip"  data-tip="View Leave Request">
                                <button onclick="leaveViewModal('Leave Request Details', '${baseUrl}/leave/view/${data}')" class="actionBtn bg-[#831b94] hover:bg-red-700">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </div>
                        `;
                        let editBtn = `
                            <div class="inline-block tooltip" data-tip="Approve or Reject">
                                <button onclick="leaveEditModal('Edit Leave', '${baseUrl}/leave/edit/${data}', '{!!getUserRole() !== 'employee' ? 'management' : ''!!}')"
                                    class="actionBtn bg-neutral-700 hover:bg-neutral-900 disabled:bg-gray-500">
                                    {!!getUserRole() !== 'employee' ? '<i class="ti ti-shield-star"></i>' : '<i class="ti ti-edit"></i>'!!}
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

                        return `<div class="flex justify-center gap-1">${viewBtn}${editBtn}${printBtn}</div>`;
                    }
                }

            ]
        });
        $('#leave-request-search-key').on('input', debounce(function() {
            let searchText = $('#leave-request-search-key').val().trim();
            leaveRequestDataTable.search(searchText).draw();
        }, 500));
/*         $('#leave-approval-status, #organization, #designation').change(function (){
            let status = $('#leave-approval-status').val();
            console.log(status)
            leaveRequestDataTable.ajax.url(`${baseUrl}/request-approvals?requestType=leave-request&status=${status}`).load();
        }); */
        /* $('#leave-approval-status, #organization, #designation').change(function (){
            let status = $('#leave-approval-status').val();
            console.log(status)
            leaveRequestDataTable.ajax.url(`{{ route('leave.index', ['q'=>'request-approvals'])}}?status=${status}`).load();
        }); */
        $('#leave-approval-status, #organization, #designation').change(function (){
            let status = $('#leave-approval-status').val();
            console.log(status);
            leaveRequestDataTable.ajax.url(`{{ route('leave.index', ['q' => 'request-approvals']) }}&filter_approval_status=${status}`).load();
        });

        /* $('#leave_type, #approval_status, #date').change(function (){
            let leave_type = $('#leave_type').val();
            let approval_status = $('#approval_status').val();
            let isMyLeaveList = $('#isMyLeaveList').val();
            let date = $('#date').val();
            dataTable.ajax.url(`{{ route('leave.index') }}?a=${isMyLeaveList}&filter_leave_type=${leave_type}&filter_approval_status=${approval_status}&date=${date}`).load();
        }); */





        let lateArrivalDataTable = new DataTable('#late-arrival-request-data-table', {
            serverSide: true,
            processing:true,
            layout: tableDef.layout,
            columnDefs: [
                tableDef.columnDefs,
            ],
            ajax:{
                url: `${baseUrl}/request-approvals?requestType=late-arrival&status=1`,
                dataSrc: function (res) {
                    return res.data;
                }
            },
            columns: [
                { name: 'name',  data: 'name',
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'employee');
                    }
                },
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
                { name: 'roleAs',  data: 'roleAs'},
                { name: 'status',  data: 'status',
                    render:function(data, type, row) {
                        return makeTableCellData(row, 'request-approval-status');
                    }
                },
                { name: 'action', data: 'id',
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'request-approval-action', { title: 'Late Arrival' });
                    }
                }
            ]
        });
        $('#late-arrival-search-key').on('input', debounce(function() {
            let searchText = $('#late-arrival-search-key').val().trim();
            lateArrivalDataTable.search(searchText).draw();
        }, 500));
        $('#late-arrival-status, #organization, #designation').change(function (){
            let status = $('#late-arrival-status').val();
            console.log(status)
            lateArrivalDataTable.ajax.url(`${baseUrl}/request-approvals?requestType=late-arrival&status=${status}`).load();
        });




        let earlyExitDataTable = new DataTable('#early-exit-data-table', {
            serverSide: true,
            processing:true,
            layout: tableDef.layout,
            columnDefs: [
                tableDef.columnDefs,
            ],
            ajax:{
                url: `${baseUrl}/request-approvals?requestType=early-exit&status=1`,
                dataSrc: function (res) {
                    return res.data;
                }
            },
            columns: [
                { name: 'emp_id',  data: 'emp_id',
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'employee');
                    }
                },
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
                { name: 'action', data: 'id',
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'request-approval-action', { title: 'Early Exit' });
                    }
                }

            ]
        });
        $('#early-exit-search-key').on('input', debounce(function() {
            let searchText = $('#early-exit-search-key').val().trim();
            earlyExitDataTable.search(searchText).draw();
        }, 500));
        $('#early-exit-status, #organization, #designation').change(function (){
            let status = $('#early-exit-status').val();
            console.log(status)
            earlyExitDataTable.ajax.url(`${baseUrl}/request-approvals?requestType=early-exit&status=${status}`).load();
        });

        let homeOfficeDataTable = new DataTable('#home-office-data-table', {
            serverSide: true,
            processing:true,
            layout: tableDef.layout,
            columnDefs: [
                tableDef.columnDefs,
            ],
            ajax:{
                url: `${baseUrl}/request-approvals?requestType=home-office&status=1`,
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
                { name: 'emp_id',  data: 'emp_id',
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'employee');
                    }
                },
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
                { name: 'action', data: 'id',
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'request-approval-action', { title: 'Home Office' });
                    }
                }

            ]
        });
        $('#home-office-search-key').on('input', debounce(function() {
            let searchText = $('#home-office-search-key').val().trim();
            earlyExitDataTable.search(searchText).draw();
        }, 500));
        $('#home-office-status').change(function (){
            let status = $('#home-office-status').val();
            console.log(status)
            homeOfficeDataTable.ajax.url(`${baseUrl}/request-approvals?requestType=home-office&status=${status}`).load();
        });









    </script>
@endsection



