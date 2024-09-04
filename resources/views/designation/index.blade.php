@extends('layouts.app')
@section('title', 'Designation List')
@section('pageTitle', 'Designation List')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title' => 'Designation List', 'url' => '']]" class=""></x-breadcumbs.breadcumb>
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
                {{-- <div class="">
                <button class="sort button-outline !mb-3 text-2xl hover:text-[#831b94] group" data-sort="count">
                    <i class="ti ti-sort-ascending-letters group-[.desc]:hidden block"></i>
                </button>
            </div> --}}
            </div>
            @if (userCan('leave.create'))
                <div class="self-center h-full flex flex-col items-end justify-end">
                    <div class="flex items-center align-middle gap-x-2">
                        <div>
                            <h5 class="text-sm text-gray-800 dark:text-white">Action: </h5>
                        </div>
                        <div class="inline-block tooltip tooltip-left" data-tip="Create Designation">
                            <button type="button" onclick="createDesignationModal.showModal()"
                                class="actionBtn bg-[#831b94]"><i class="fa-solid fa-circle-plus text-xs"></i></button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-containers.container-box>
    <x-containers.container-box data="!p-0">
        <table id="dataTable"
            class="w-full display text-[13px] cell-border dark:text-white divide-neutral-200 dark:divide-neutral-700">
            <thead class="">
                <tr class="dark:hover:bg-neutral-800">
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start truncate">
                        SL
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        NAME
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        Status
                    </th>

                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody id="tableData">

            </tbody>
        </table>
    </x-containers.container-box>
@endsection
@include('designation.create')
@section('scripts')
    <script>
        let dataTable = new DataTable('#dataTable', {
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: `{{ route('designation.index') }}`
            },
            layout: tableDef.layout,
            columnDefs: [
                tableDef.columnDefs,
            ],
            order: [
                [1, 'asc']
            ],
            columns: [{
                    name: 'S/L',
                    data: 'sl',
                    sortable: false
                },
                {
                    name: 'name',
                    data: 'name'
                },
                {
                    name: 'status',
                    data: 'is_active',
                    render: function(data, type, row) {
                        let bgClass = row.is_active === 1 ? 'bg-[#0D9276]' : 'bg-[#C80036]';
                        return `<span class="${bgClass} text-white px-4 py-2 font-medium rounded-full text-xs">${row.is_active == 1 ? 'Active' : 'Inactive'}</span>`;
                    }
                },
                {
                    name: 'action',
                    data: 'id',
                    sortable: false,
                    render: function(id, type, row) {
                        let deletePermission = true; // Assuming you control this from backend
                        let lastChild = false; // This should be dynamically determined
                        let dropdownClasses = lastChild ? "dropdown dropdown-top" :
                        "dropdown dropdown-left";

                        return `
                    <div class="flex items-center justify-center font-medium">
                        <div class="${dropdownClasses}">
                            <button type="button" tabindex="0" role="button" class="flex items-center justify-center text-sm font-semibold text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg size-9 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                <svg class="flex-none text-gray-600 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1" />
                                    <circle cx="12" cy="5" r="1" />
                                    <circle cx="12" cy="19" r="1" />
                                </svg>
                            </button>
                            <ul tabindex="0" class="dropdown-content z-[1] menu px-0 shadow bg-base-100 rounded-md !w-40">
                                <div class="px-2 space-y-1">
                                    <button type="button"
                                        onclick="editModalAjax('edit-designation', 'smallModal', ${row.id})"
                                        class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                        <i class="ti ti-edit"></i>
                                        Edit
                                    </button>
                                    ${deletePermission ? `
                                        <button type="button"
                                            onclick="deletePopup('Delete Employee', '${row.name}', '${baseUrl}/designations/delete/${row.id}')"
                                            class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-sm text-[#831b94] hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                            <i class="fa-regular fa-trash-can"></i>
                                            Delete
                                        </button>` : ''}
                                </div>
                            </ul>
                        </div>
                    </div>
                `;
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
