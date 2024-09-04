@extends('layouts.app')
@section('title', 'Leave List')
@section('pageTitle', 'Leave List')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Leave', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')
    @if(request('a')=='my-leave-list' && in_array(getUserRole(), ['employee', 'department-head', 'hr']))
        <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
            <button
                id="hs-dropdown-with-header"
                type="button"
                class="submit-button">
                Leave Balance
            </button>

            <div
                class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white  rounded-lg shadow-[0_10px_40px_10px_rgba(0,0,0,0.08)] dark:bg-neutral-900 dark:shadow-[0_10px_40px_10px_rgba(0,0,0,0.2)] z-10 overflow-hidden"
                aria-labelledby="hs-dropdown-with-header">
                <p class="p-3 font-medium bg-[#831b94] text-white">Leave Balance</p>
                <hr class="mb-2">
                <div class="px-5 py-3 -m-2 rounded-md">
                    <div class="flex items-center justify-between gap-3 border-b mb-2">
                        <p>Total</p>
                        <p>Available</p>
                    </div>
                    @php
                        $leaveBalances=auth()->user()->employee->leaveBalance(date('Y'));
                    @endphp
                    @foreach ($leaveBalances as $item)
                        @php
                            $allowance=$item['allowance'];
                            $remaining=$item['remaining'];
                        @endphp
                        <div class="flex items-center justify-between gap-3">
                            <p>{{$item['name']}} ({{ $allowance}})</p>
                            <p><span class="text-xl  {{$remaining==0 ? 'text-[#831b94]' : 'text-teal-600'}}">{{ $remaining }}</span></p>
                        </div>

                    @endforeach
                    <p class="text-sm font-bold text-white dark:text-neutral-100 flex items-center justify-start gap-x-2"><span class=""><i class="fa-regular fa-user"></i> </span><span class=""></span></p>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('content')
    <x-containers.container-box>
        <div class="flex justify-between items-center gap-4">
            <div class="grow grid grid-cols-1 md:grid-cols-5 gap-4 items-end ">
                <div>
                    {{--<label for="select-filter" class="inputLabel">Search</label>--}}
                    <input type="text" class="inputFieldCompact" id="searchKey" placeholder="Type to search...">
                </div>
                <div>
                    {{--<label for="select-filter" class="inputLabel">Leave Type</label>--}}

                    <select id="leave_type"  name="select-filter" class="inputFieldCompact select2">
                        <option value="">Leave Type (All)</option>
                        @foreach($leaveType as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select id="approval_status"  name="select-filter" class="inputFieldCompact select2">
                        <option value="">Leave Status (All)</option>
                        @foreach($approvalStatus as $item)
                            <option value="{{$item->id}}" {{request()->has('status') && request('status')==$item->id ? 'selected' : ''}}>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-1">
                    <label for="select-filter" class="inputLabel">Date:</label>
                    <input type="date" id="date"  name="date" value="{{date('Y-m-d')}}" class="inputFieldCompact">
                    <input type="hidden" id="isMyLeaveList"  value="{{request('a')}}" class="">
                </div>
            </div>
            <div class="h-full flex flex-col items-center justify-end mr-2">
                <div class="flex items-center align-middle gap-x-2">
                    @if(userCan('leave.create'))
                        <div class="inline-block hs-tooltip">
                            <div class="inline-block hs-tooltip">
                                <button type="button" onclick="initializeSelectize('c_leave_select_employee','get-employee-list', '','','leave');initializeSelectize('c_leave_select_reliever','get-employee-list', '','',''); createLeaveRequestModal.showModal(); changeRequisitionType(); calculateLeaveDays('c_');" class="tooltip actionBtn bg-[#831b94] " data-tip="Request a leave">
                                    {{-- <i class="ti ti-plus"></i> --}}
                                    <i class="fa-solid fa-circle-plus"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-containers.container-box>
    <div class="mt-2 text-center w-full text-[#831b94] text-lg font-medium" id="currentLeaveDate">Selected Leave Date: {{date('d M Y')}}</div>
    <x-containers.container-box>
        <div class="overflow-x-auto">
            <div class="w-full align-middle">
                <div class="overflow-x-auto">
                    <table id="dataTable" class="w-full text-[13px] display dark:text-white divide-neutral-200 dark:divide-neutral-700">
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
            </div>
        </div>
    </x-containers.container-box>

@endsection
@include('leave.create-late-request')
@include('leave.create-leave-request')
@section('scripts')
    <script>
        let dataTable = new DataTable('#dataTable', {
            serverSide: true,
            processing:true,
            layout: tableDef.layout,
            columnDefs: [
                tableDef.columnDefs,
            ],
            ajax:{
                url: '{{ route('leave.index', ['filter_approval_status'=>request('status'), 'a'=>request('a'), 'date'=>date('Y-m-d')])}}',
                dataSrc: function (res) {
                    if(res.selectedDate!==''){
                        $('#currentLeaveDate').html('Selected Leave Date: '+res.selectedDate)
                    }else{
                        $('#currentLeaveDate').html('')
                    }
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
                { name: 'leave_days',  data: 'leave_days'},
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

                        return `<div class="flex justify-center gap-1">${viewBtn}${editBtn}${printBtn}</div>`;
                    }
                }

            ]
        });
        $('#searchKey').on('input', debounce(function() {
            let searchText = $('#searchKey').val().trim();
            dataTable.search(searchText).draw();
        }, 500));
        $('#leave_type, #approval_status, #date').change(function (){
            let leave_type = $('#leave_type').val();
            let approval_status = $('#approval_status').val();
            let isMyLeaveList = $('#isMyLeaveList').val();
            let date = $('#date').val();
            dataTable.ajax.url(`{{ route('leave.index') }}?a=${isMyLeaveList}&filter_leave_type=${leave_type}&filter_approval_status=${approval_status}&date=${date}`).load();
        });
    </script>

    <script>
        $('#form_type').on('change', function() {
            handleChangeFormType(this);
        });
    </script>

    @if(request('req')=='true')
        <script>
            $(function () {
                initializeSelectize('c_leave_select_employee','get-employee-list', '','','leave');initializeSelectize('c_leave_select_reliever','get-employee-list', '','',''); createLeaveRequestModal.showModal(); calculateLeaveDays('c_');
            })
        </script>
    @endif
@endsection



