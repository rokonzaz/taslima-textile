@extends('layouts.app')
@section('title', 'Notice Board')
@section('pageTitle', 'Notice List')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title' => 'Notice', 'url' => '']]" class=""></x-breadcumbs.breadcumb>
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
                    <select id="notice_type" name="notice_type" class="inputFieldCompact select2">
                        <option value="">Notice Title (All)</option>
                        @foreach ($notice as $item)
                            <option value="{{ $item->notice_type }}">{{ $item->notice_type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select id="notice_status" name="select-filter" class="inputFieldCompact select2">
                        <option value="">Notice By (All)</option>
                        @foreach ($notice as $item)
                            <option value="{{ $item->notice_by }}">{{ $item->notice_by }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="date" id="date" name="date" value="{{ date('Y-m-d') }}"
                        class="inputFieldCompact">
                </div>
                <div class="">
                    <div class="inline-block tooltip" data-tip="Reset All Filters">
                        <button type="button" class="btn-red w-9 aspect-square flex items-center justify-center"
                            id="reset">
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
                    @if (userCan('notice.create'))
                        <div class="inline-block hs-tooltip tooltip-left">
                            <button type="button" onclick="createNoticeModal.showModal()"
                                class="tooltip actionBtn bg-[#831b94]" data-tip="Add Notice">
                                <i class="fa-solid fa-circle-plus"></i>
                            </button>
                        </div>
                    @endif
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
                        SL
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        Notice Title
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        Notice Date
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        Notice Description
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                        Notice By
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

@include('notice.create')
{{-- @include('notice.edit') --}}

@section('scripts')
    <script>
        let dataTable = new DataTable('#dataTable', {
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: `{{ route('notice.ajaxNotice') }}`,
                data: function(d) {
                    d.notice_type = $('#notice_type').val();
                    d.notice_status = $('#notice_status').val();
                    d.date = $('#date').val();
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
                    name: 'notice_type',
                    data: 'notice_type'
                },
                {
                    name: 'notice_date',
                    data: 'notice_date'
                },
                {
                    name: 'notice_description',
                    data: 'notice_description',
                    render: function(data, type, row) {
                        let words = data.split(' ');
                        if (words.length > 50) {
                            return words.slice(0, 10).join(' ') + '...';
                        } else {
                            return data;
                        }
                    }
                },
                {
                    name: 'notice_by',
                    data: 'notice_by'
                },
                {
                    name: 'action',
                    data: 'id',
                    sortable: false,
                    render: function(id, type, row) {
                        let editButton = `
                            <div class="tooltip"  data-tip="Edit Notice">
                                <button type="button" onclick="editNoticeModal('Edit Notice', '${baseUrl}/notice/edit/${row.id}')" class="actionBtn neutral">
                                    <i class="ti ti-edit-off"></i>
                                </button>
                            </div>
                        `;
                        let deleteButton = `
                                        <div class="inline-block tooltip"  data-tip="Notice Delete">
                                            <button class="actionBtn red" onclick="deletePopup('Delete Notice', '${row.notice_type}', '${baseUrl}/notice/delete/${row.id}')">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>

                                    `;
                        let sendUserNoticeMail = `
                            <div class="inline-block tooltip"  data-tip="Send Notice">
                                <button type="button" onclick="sendUserNoticeMail('Send Notice', '${baseUrl}/notice/send/${row.id}')" class="actionBtn teal">
                                    <i class="fa-solid fa-envelope"></i>
                                </button>
                            </div>
                        `;
                        return `<div class="flex items-center justify-center gap-1">${editButton}${deleteButton}${sendUserNoticeMail}</div>`;
                    }
                }
            ]
        });
        $('#searchKey').on('input', debounce(function() {
            let searchText = $('#searchKey').val().trim();
            dataTable.search(searchText).draw();
        }, 500));
        $('#notice_type, #notice_status, #date').change(function() {
            if ($(this).attr('id') !== 'date') {
                $('#date').val('');
            }
            dataTable.ajax.reload();
        });
        $('#reset').click(function() {
            $('#notice_type').val('');
            $('#notice_status').val('');
            $('.select2').select2();
            let today = new Date().toISOString().split('T')[0];
            $('#date').val(today);

            let notice_type = '';
            let notice_status = '';
            let date = today;

            $('#searchKey').val('');
            dataTable.search('').draw();
            dataTable.ajax.url(
                `{{ route('notice.ajaxNotice') }}?notice_type=${notice_type}&notice_status=${notice_status}&date=${date}`
                ).load();

            var currentUrl = new URL(window.location.href);
            window.history.pushState({
                path: currentUrl.href
            }, '', currentUrl.origin + currentUrl.pathname);
        });



        function deleteNotice(id) {
            if (confirm('Are you sure you want to delete this notice?')) {
                // CSRF token is required for the DELETE request
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // AJAX request to delete the notice
                $.ajax({
                    url: '/notice/delete/' + id, // Updated URL
                    type: 'DELETE',
                    success: function(result) {
                        dataTable.ajax.reload();
                        alert('Notice deleted successfully');
                    },
                    error: function(xhr) {
                        alert('Failed to delete notice');
                    }
                });
            }
        }

        function editNoticeModal(title, url) {
            largeModal.showModal();
            $('#largeModalTitle').html(title);
            $(`#largeModalBody`).html($('#spinner-large').html())
            $.ajax(url).then(function(res) {
                if (res.status === 1) {
                    $(`#largeModalBody`).html(res.html)
                } else {
                    toastr.error(res.msg)
                    $('#largeModalTitle').close()
                }
            })
        }
    </script>
@endsection
