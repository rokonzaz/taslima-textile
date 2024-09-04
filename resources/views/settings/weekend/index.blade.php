@extends('layouts.app')
@section('title', 'Weekend')
@section('pageTitle', 'Weekend List')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title' => 'Weekend', 'url' => '']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection

@section('content')
    @if (count($weekend) == 0)
        <x-containers.container-box>
            <div class="flex justify-between items-end gap-4">
                {{-- <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                <div>
                    <input type="text" class="inputFieldCompact" id="searchKey" placeholder="Type to search...">
                </div>
            </div> --}}
                <div class="self-center h-full flex flex-col items-end justify-end">
                    <div class="flex items-center align-middle gap-x-2">
                        <div>
                            <h5 class="text-sm text-gray-800 dark:text-white">Action: </h5>
                        </div>
                        <div class="inline-block hs-tooltip tooltip-left">
                            <button type="button"
                                onclick="initializeSelectize('c_weekend','', '','','weekend');createWeekendModal.showModal();"
                                class="tooltip actionBtn bg-[#831b94]" data-tip="Weekend">
                                <i class="fa-solid fa-circle-plus"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </x-containers.container-box>
    @endif
    <x-containers.container-box data="!p-0">
        @if (count($weekend) > 0)
            <table id="dataTable"
                class="w-full display text-[13px] cell-border dark:text-white divide-neutral-200 dark:divide-neutral-700">
                <thead class="">
                    <tr class="dark:hover:bg-neutral-800">
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start truncate">
                            SL
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            WEEKEND NAME
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            ACTIONS
                        </th>
                    </tr>
                </thead>
                <tbody id="tableData">
                    <tr class="dark:hover:bg-neutral-800">
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start truncate">
                            1
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            {{ implode(', ', $weekend->toArray()) }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                            <div class="tooltip" data-tip="Edit Weekend">

                                @php
                                    $wArray = $weekend->map(function ($item) {
                                        return (string) $item;
                                    });
                                @endphp
                                <button type="button"
                                    onclick="initializeSelectize('e_weekend','', {{ $wArray }},'','weekend'); editWeekendModal.showModal();"
                                    class="actionBtn neutral">
                                    <i class="ti ti-edit-off"></i>
                                </button>
                            </div>
                        </th>
                    </tr>
                </tbody>
            </table>
        @else
            <div colspan="3" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-center">
                No Data Found!
            </div>
        @endif
    </x-containers.container-box>

@endsection

@section('scripts')
    <script>
        let dataTable = new DataTable('#dataTable', {});
    </script>
@endsection


@include('settings.weekend.create')
@include('settings.weekend.edit')
