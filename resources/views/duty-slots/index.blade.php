@extends('layouts.app')
@section('title', 'Duty Slot')
@section('pageTitle', 'Duty Slot List')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title' => 'Duty Slot', 'url' => '']]" class=""></x-breadcumbs.breadcumb>
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
                    <div class="inline-block tooltip tooltip-left" data-tip="Add Duty Slot">
                        <button type="button" onclick="createDutySlotModal.showModal()" class="actionBtn bg-[#831b94]"><i
                                class="fa-solid fa-circle-plus text-xs"></i></button>
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
                        SLOT NAME
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        Start Time
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        Threshold Time
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        End Time
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
@include('duty-slots.create')
@section('scripts')
    <script>
        let dataTable = new DataTable('#dataTable', {
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: `{{ route('dutySlots.index') }}`,
                dataSrc: function(res) {
                    $('#lastSyncDate').html(res.lastSyncDate)
                    $('#lastSyncTime').html(res.lastSyncTime)
                    return res.data;
                }

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
                    name: 'slot_name',
                    data: 'slot_name'
                },
                {
                    name: 'start_time',
                    data: 'start_time'
                },
                {
                    name: 'threshold_time',
                    data: 'threshold_time'
                },
                {
                    name: 'end_time',
                    data: 'end_time'
                },
                {
                    name: 'action',
                    data: 'id',
                    render: function(data, type, row) {
                        let editBtn = `
                            <button onclick="editModalAjax('duty-slot', 'largeModal', ${row.id})" class="tooltip tooltip-top actionBtn neutral" data-tip="Edit duty slot">
                                <i class="ti ti-edit-off"></i>
                            </button>
                        `
                        let deleteBtn = `
                            <button onclick="deletePopup('Delete Duty Slot', '${row.slot_name}', '${baseUrl}/duty-slots/delete/${row.id}')" class="actionBtn red tooltip tooltip-top" data-tip="Delete duty slot">
                                <i class="ti ti-trash"></i>
                            </button>
                        `
                        return `<div class="flex justify-center gap-2">${row.editPermission ? editBtn : ''}${row.deletePermission ? deleteBtn : ''}</div>`;
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
