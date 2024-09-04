@extends('layouts.app')
@section('title', 'Leave Types')
@section('pageTitle', 'Leave Types')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title' => 'Leave Types', 'url' => '']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection

@section('content')
    <x-containers.container-box data="!p-0">
        @if ($leaveTypes->count() > 0)
            <table id="dataTable"
                class="w-full display text-[13px] cell-border dark:text-white divide-neutral-200 dark:divide-neutral-700">
                <thead class="">
                    <tr class="dark:hover:bg-neutral-800">
                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start truncate">
                            Sl.
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Leave Type
                        </th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Days</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            Remarks
                        </th>

                        <th scope="col" id="actionHeader"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-center">
                            Action {{-- Adjusted the alignment here --}}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach ($leaveTypes as $key => $item)
                        <tr class="">
                            <td class="">
                                {{ $key + 1 }}
                            </td>
                            <td class="text-lg font-bold"><span class="">{{ $item->name }} Leave</span></td>
                            <td class="">{{$item->days}}</td>
                            <td class="">{{ $item->remarks }}</td>
                            <td class="text-center">
                                <div class="inline-block tooltip tooltip-left" data-tip="Edit Leave Type">
                                    <button type="button"
                                        onclick="editModalAjax('leave-type', 'smallModal', {{ $item->id }})"
                                        class="actionBtn red">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-lg text-center py-12 text-gray-600">No records found!</p>
        @endif
    </x-containers.container-box>
@endsection

@section('scripts')
    <script>
        let dataTable = new DataTable('#dataTable', { // Make sure the ID matches with the table ID in HTML
            responsive: true,
            processing:true,
            layout: {
                topStart: {},
                bottomStart: {
                    pageLength: true,
                    info: true,
                }
            },
            columnDefs: [
                tableDef.columnDefs,
            ],
            order: [[1, 'asc']],
            "initComplete": function(settings, json) {
                $('#actionHeader').css('text-align', 'center'); // This is now redundant as the alignment was fixed in HTML
            },
        });
    </script>
@endsection
