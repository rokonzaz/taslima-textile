@extends('layouts.app')
@section('title', 'Holiday')
@section('pageTitle', 'Holiday List')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title' => 'Holiday List', 'url' => '']]" class=""></x-breadcumbs.breadcumb>
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
            </div>
            <div class="self-center h-full flex flex-col items-end justify-end">
                <div class="flex items-center align-middle gap-x-2">
                    <div>
                        <h5 class="text-sm text-gray-800 dark:text-white">Action: </h5>
                    </div>
                    <div class="inline-block hs-tooltip tooltip-left">
                        <button type="button" onclick="createHolidayModal.showModal(); calculateLeaveDays('c_','holiday')"
                            class="tooltip actionBtn bg-[#831b94]" data-tip="Holiday">
                            <i class="fa-solid fa-circle-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-containers.container-box>
    <x-containers.container-box data="!p-0">
        <table id="dataTable"
            class="w-full display text-[13px] cell-border dark:text-white divide-neutral-200 dark:divide-neutral-700">
            <thead class="">
                <tr class="dark:hover:bg-neutral-800">
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start truncate">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        HOLIDAY NAME
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        START DATE
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        END DATE
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        TOTAL DAYS
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        ACTIONS
                    </th>
                </tr>
            </thead>
            <tbody id="tableData">

            </tbody>
        </table>
    </x-containers.container-box>

@endsection
@include('settings.holiday.create')
{{-- @include('settings.holiday.edit') --}}
@section('scripts')
    <script>
        let dataTable = new DataTable('#dataTable', {
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: `{{ route('holiday.index') }}`
            },
            dataSrc: function(res) {
                $('#lastSyncDate').html(res.lastSyncDate)
                $('#lastSyncTime').html(res.lastSyncTime)
                return res.data;
            },
            layout: tableDef.layout,
            columnDefs: [
                tableDef.columnDefs,
            ],
            order: [
                [1, 'asc']
            ],
            columns: [{
                    name: 'id',
                    data: 'id'
                },
                {
                    name: 'name',
                    data: 'name'
                },
                {
                    name: 'start_date',
                    data: 'start_date'
                },
                {
                    name: 'end_date',
                    data: 'end_date',
                },
                {
                    name: 'total_days',
                    data: 'total_days'
                },
                {
                    name: 'action',
                    data: 'id',
                    render: function(data, type, row) {
                        let viewBtn= `<button type="button"
                                                class="editUserBtn w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer"
                                                onclick="window.location.href = '${baseUrl}/employees/view/${row.emp_id}'">
                                                <i class="fa-regular fa-address-card"></i>
                                                View Details
                                            </button>`
                        let lastChild = false;
                        let deletePermission = true;
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
                                                onclick="editHolidayModal('Edit Holiday', '${baseUrl}/leave/holiday/edit/${row.id}')"
                                                class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                                <i class="ti ti-edit-off"></i>
                                                Edit holiday
                                            </button>
                                            ${deletePermission ? `<button type="button"
                                                onclick="deletePopup('Delete Holiday', '${row.name}', '${baseUrl}/leave/holiday/delete/${row.id}')"
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
    </script>
@endsection
