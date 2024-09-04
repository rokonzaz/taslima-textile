@extends('layouts.app')
@section('title', 'Employee Profile')
@section('pageTitle', 'Employee List')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Employee List', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection

@section('content')
    <x-containers.container-box>
        <div class="flex justify-between items-end gap-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
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
            <div class="self-center h-full flex flex-col items-end justify-end">
                <div class="flex items-center align-middle gap-x-2">
                    <div>
                        <h5 class="text-sm text-gray-800 dark:text-white">Action: </h5>
                    </div>
                    <div class="inline-block tooltip"  data-tip="Import">
                        <a href="{{route('employees.import')}}" type="button" class="actionBtn bg-violet-800"><i class="fa-solid fa-cloud-arrow-down text-xs"></i></a>
                    </div>
                    <div class="inline-block tooltip tooltip-wrap"  data-tip="Create Employee">
                        <button type="button" onclick="createEmployeeModal.showModal()" class="actionBtn bg-[#831b94]"><i class="fa-solid fa-circle-plus text-xs"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </x-containers.container-box>
    <x-containers.container-box data="!p-0">
        <table id="dataTable" class="w-full display text-[13px] cell-border dark:text-white divide-neutral-200 dark:divide-neutral-700">
            <thead class="">
            <tr class="dark:hover:bg-neutral-800">
                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start truncate">
                    EMP ID
                </th>
                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                    NAME
                </th>
                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                    Department Head/Line Manager
                </th>
                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                    DEPARTMENT
                </th>
                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                    DESIGNATION
                </th>
                <th scope="col"
                    class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                    Gender
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
    </x-containers.container-box>

@endsection
@include('employee.create')
@section('scripts')
    <script>
        let dataTable = new DataTable('#dataTable', {
            responsive: true,
            serverSide: true,
            processing:true,
            ajax:{
                url: `{{ route('employees.index') }}?organization={{request()->has('organization') ? request('organization') : ''}}&department={{request('department') ?? ''}}`
            },
            layout: tableDef.layout,
            columnDefs: [
                tableDef.columnDefs,
            ],
            order: [[1, 'asc']],
            columns: [
                { name: 'employeeID',  data: 'emp_id', render: function(data, type, row) {
                        let html=`<a href="${baseUrl}/employees/view/${row.emp_id}" class="emp-id-list-btn">${data}</a>`;
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
                /*{ data: 'email' },*/
                { name: 'departmentHead',  data: 'departmentHead', sortable:false,
                    render: function(data, type, row) {
                        return makeTableCellData(row, 'department-head');
                    }
                },
                { name: 'department',  data: 'department' },
                { name: 'designation',  data: 'designation' },
                { name: 'gender',  data: 'gender', className: 'dt-center', },
                { name: 'status',  data: 'is_active', className: 'dt-center',
                    render: function(data, type, row) {
                        let bgCLass='bg-[#831b94]';
                        if(row.is_active===0) bgCLass='bg-[#C80036]';
                        if(row.is_active===1) bgCLass='bg-[#0D9276]';
                        return html=`<span class="${bgCLass} text-white px-2.5 pb-1.5 pt-1 font-medium rounded-full text-xs">${row.is_active==1? 'Active' : 'Inactive'}</span>`
                    }
                },
                { name: 'action',  data: 'id', sortable:false,
                    render: function(id, type, row) {
                        /* let viewProfileBtn=`
                                        <div class="inline-block tooltip"  data-tip="View">
                                            <a href="${baseUrl}/employees/view/${row.emp_id}" class="actionBtn neutral">
                                                <i class="fa-regular fa-address-card"></i>
                                            </a>
                                        </div>
                                    `

                        let assignRoleButton=`
                                        <div class="inline-block tooltip"  data-tip="Assign">
                                            <button type="button" ${row.is_user===1 ? 'disabled' : ''} onclick="assignAsUserPopup(${id})" class="actionBtn teal">
                                                <i class="fa-solid ${row.is_user===1 ? 'fa-user-check' : 'fa-user-gear'} "></i>
                                            </button>
                                        </div>
                                    `;
                                    let deletePermission=true;
                                    let deleteButton=`
                                        <div class="inline-block tooltip"  data-tip="Delete">
                                            <button class="actionBtn red" onclick="deletePopup('Delete Emplyee', '${row.full_name}', '${baseUrl}/employees/delete/${row.id}')">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>

                                    `
                        if(!deletePermission) deleteButton='';
                        //if(row.is_user===1) assignRoleButton=''
                        let html=`<div class="flex items-center justify-center gap-1">${viewProfileBtn}${viewProfileBtn2}${assignRoleButton}${deleteButton}</div>`;*/
                        // let deletePermission=true;
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
                                            ${row.viewPermission ? `<button type="button"
                                                class="editUserBtn w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer"
                                                onclick="window.location.href = '${baseUrl}/employees/view/${row.emp_id}'">
                                                <i class="fa-regular fa-address-card"></i>
                                                View Details
                                            </button>`:''}
                                            ${(row.is_superAdmin || row.is_hr) ? `<button type="button"
                                                onclick="changeActiveStatusPopup(${id})"
                                                class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                                <i class="fa-solid fa-user-lock"></i>
                                                Active/Inactive
                                            </button>`:''}
                                            ${(row.assignRolePermission & row.is_user===0) ? `<button type="button"
                                                ${row.is_user===1 ? 'disabled' : ''} onclick="assignAsUserPopup(${id})"
                                                class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                                <i class="fa-solid ${row.is_user===1 ? 'fa-user-check' : 'fa-user-gear'} "></i>
                                                Assign Role
                                            </button>` : ''}
                                            ${row.deletePermission ? `<button type="button"
                                                onclick="deletePopup('Delete Emplyee', '${row.full_name}', '${baseUrl}/employees/delete/${row.id}')"
                                                class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs text-[#831b94] hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                                <i class="fa-regular fa-trash-can "></i>
                                                Delete
                                            </button>`:''}
                                        </div>
                                    </ul>
                                </div>
                            </div>`;
                        return html;
                    }
                },
            ]
        });
        $('#searchKey').on('input', debounce(function() {
            let searchText = $('#searchKey').val().trim();
            dataTable.search(searchText).draw();
        }, 500));
        $('#department, #organization, #designation').change(function (){
            let department = $('#department').val();
            let organization = $('#organization').val();
            let designation = $('#designation').val();
            dataTable.ajax.url(`{{ route('employees.index') }}?department=${department}&organization=${organization}&designation=${designation}`).load();
        });
        $('#reset').click(function (){
            $('#department').val('')
            $('#organization').val('');
            $('#designation').val('');
            $('.select2').select2();
            let department = '';
            let organization = '';
            let designation = '';
            $('#searchKey').val(''); dataTable.search('').draw();
            dataTable.ajax.url(`{{ route('employees.index') }}?department=${department}&organization=${organization}&designation=${designation}`).load();
            var currentUrl = new URL(window.location.href);
            window.history.pushState({ path: currentUrl.href }, '', currentUrl.origin+currentUrl.pathname);
        });
    </script>
@endsection



