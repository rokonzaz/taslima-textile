@props(['data'])
@php
    $homeOfficeRequests=$data;
    $homeOfficeRequestsCount=$homeOfficeRequests->count();
@endphp
@if($homeOfficeRequestsCount>0)
    <div class="inline-block min-w-full  align-middle h-[20rem] overflow-y-scroll overflow-x-visible">
        <table class="w-full  divide-y divide-gray-200 dark:divide-neutral-700 text-xs">
            <thead class="sticky top-0 z-[9]">
            <tr class="bg-gray-200 dark:bg-neutral-900">
                <th class="text-left px-3 py-2 font-semibold uppercase dark:text-teal-500">
                    Date
                </th>
                <th class="text-left px-3 py-2 font-semibold uppercase dark:text-teal-500">
                    Total Days
                </th>
                <th class="text-left px-3 py-2 font-semibold uppercase dark:text-teal-500">
                    Reason
                </th>
                <th class="px-3 py-2 font-semibold uppercase dark:text-teal-500">
                    Status
                </th>
                <th class="px-3 py-2 font-semibold uppercase dark:text-teal-500">
                    Action
                </th>
            </tr>
            </thead>
            <tbody>

            @foreach($homeOfficeRequests as $key=>$item)
                <tr
                    class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                    <td class="px-3 py-2 text-gray-800 dark:text-neutral-200">
                        <p class="font-medium">{{formatCarbonDate($item->start_date)}} to {{formatCarbonDate($item->end_date)}}</p>
                        <p class="font-medium text-teal-600">{{formatCarbonDate($item->time, 'time')}}</p>
                    </td>
                    <td class="px-3 py-2 text-gray-800 dark:text-neutral-200">
                        <p class="font-medium w-6 h-6 text-center p-1 rounded-full bg-teal-200">{{\Carbon\Carbon::parse($item->start_date)->diffInDays(\Carbon\Carbon::parse($item->end_date)) + 1}}</p>
                    </td>
                    <td class="px-3 py-2 text-gray-800 dark:text-neutral-200">
                        @if($item->note!='')
                            <div class="hs-tooltip [--trigger:hover] [--placement:right] inline-block">
                                <div class="hs-tooltip-toggle block text-center">
                                    <button type="button" class=" text-left">
                                        <p class="font-semibold">{{$item->reason}}</p>
                                        @if($item->note!='')
                                            <p class="w-48 truncate">Note: {{$item->note}}</p>
                                        @endif
                                    </button>
                                    <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible hidden opacity-0 transition-opacity absolute !left-[-6rem] invisible z-10 max-w-xs bg-white border border-gray-300 text-start rounded-lg shadow-md dark:bg-neutral-800 dark:border-neutral-700" role="tooltip">
                                        <span class="pt-2 px-3 block font-bold text-md text-gray-800 dark:text-white">Reason: {{$item->reason ?? 'N/A'}}</span>
                                        <div class="py-2 px-3 text-xs text-gray-600 dark:text-neutral-400">
                                            <p>Note: {{$item->note}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="font-semibold">{{$item->reason}}</p>
                            @if($item->note!='')
                                <p class="w-48 truncate">Note: {{$item->note}}</p>
                            @endif
                        @endif


                    </td>
                    <td class="px-3 py-2 text-gray-800 dark:text-neutral-200 text-center">
                        @if($item->approval_status=='approved')
                            <button class="btn-approved">Approved</button>
                        @elseif($item->approval_status=='rejected')
                            <button class="btn-rejected">Rejected</button>
                        @else
                            <button class="btn-pending">Pending</button>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-gray-800 dark:text-neutral-200 text-center">
                        @if($item->approval_status=='' && $item->approval_by=='' && $item->rejected_by=='')
                            <div class="dropdown dropdown-left ">
                                <button type="button" tabindex="0" role="button" class="flex items-center justify-center text-sm font-semibold text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg size-7 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                    <svg class="flex-none text-gray-600 size-3" xmlns="http://www.w3.org/2000/svg" width="24"
                                         height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="12" cy="5" r="1" />
                                        <circle cx="12" cy="19" r="1" />
                                    </svg>
                                </button>
                                <div tabindex="0" class="dropdown-content z-[1] menu px-0 shadow bg-base-100 rounded-md !w-32">
                                    <div class=" px-2">
                                        <button type="button" class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                            <i class="ti ti-edit"></i>
                                            Edit
                                        </button>
                                        <button type="button" onclick="deletePopup('Delete Request', '{{$item->reason}}', '{{route('my-requests.delete', ['id'=>$item->id])}}')" class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                            <i class="ti ti-trash"></i>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="py-10 text-center text-lg text-slate-400">No Records Found!</div>
@endif
