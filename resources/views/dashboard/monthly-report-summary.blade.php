<div class="flex flex-col ">
    <div class="inline-block min-w-full overflow-hidden overflow-y-auto align-middle h-[20rem]">
        <table class="min-w-full  divide-y divide-gray-200 dark:divide-neutral-700 text-xs">
            <thead class="sticky top-0 z-10">
            <tr class="bg-gray-200 dark:bg-neutral-900 text-xs">
                <th class="text-left px-2 py-2   font-semibold uppercase dark:text-teal-500">
                    S/L
                </th>
                <th class="text-left px-2 py-2   font-semibold uppercase dark:text-teal-500">
                    Title
                </th>
                <th class="text-center px-2 py-2   font-semibold uppercase dark:text-teal-500">
                    Count
                </th>
            </tr>
            <tr class="bg-gray-200 dark:bg-neutral-900 text-xs">
                <td class="text-left px-1 pb-1 font-semibold uppercase dark:text-teal-500" colspan="3">
                    <input type="text" class="search inputFieldCompact !py-1 !text-xs" id="" placeholder="Type to search...">
                </td>
            </tr>
            </thead>
            <tbody class="list">
            <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                <td class="name px-2 py-2 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200" colspan="3">
                    <div class="flex justify-between items-center">
                        <p>Month: <span class="text-[#831b94]">{{$displayMonth}}</span></p>
                        @if(count($attendanceData)>0)
                            <div class="dropdown dropdown-end">
                                <div tabindex="0" role="button" class="text-base"><i class="ti ti-list-details"></i></div>
                                <ul id="monthly_summary_comments" tabindex="0" class="dropdown-content menu bg-base-100 rounded z-[1] w-64 max-h-[12rem] overflow-hidden overflow-y-scroll p-2 shadow border">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex justify-between items-center">
                                            <p class="text-xs">Month: <span class="text-[#831b94]">{{$displayMonth}}</span></p>
                                            <button class="flex items-center gap-1 text-xs hover:bg-neutral-200 rounded px-1 py-0.5" onclick="fnExportReport('monthly_summary_comments','pdf' ,'monthly_summary_comments {{request('date')}}')"><i class="ti ti-file-type-pdf"></i> <span class="text-xs">Export</span></button>
                                        </div>
                                        <table class="">

                                            <tr class="bg-neutral-50">
                                                <th class="text-left border px-2">S/L</th>
                                                <th class="text-left border px-2">Date</th>
                                                <th class="text-left border px-2">Comment</th>
                                            </tr>

                                            @php $sl=1; @endphp
                                            @foreach($attendanceData as $date=>$item)
                                                <tr>
                                                    <th class="text-left border px-2">{{$sl++}}</th>
                                                    <th class="text-left border px-2">{{formatCarbonDate($date)}}</th>
                                                    <td class="text-left border px-2">
                                                        @php $comment=$item['comment']??'' @endphp
                                                        @if($comment=='Present')
                                                            <span class="text-teal-600">Present</span>
                                                        @elseif($comment=='Weekends')
                                                            <span class="text-purple-600">Weekends</span>
                                                        @elseif($comment=='Leave')
                                                            <span class="text-blue-600">Leave</span>
                                                        @elseif($comment=='Absent')
                                                            <span class="text-[#831b94]">Absent</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </ul>
                            </div>
                        @endif
                    </div>

                </td>
            </tr>
            @if(count($monthlySummary)>0)
                @php $sl=1; @endphp
                @foreach($monthlySummary as $key=>$value)
                    <tr
                        class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                        <td class="px-2 py-2 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                            <p class="">{{$sl++}}</p>
                        </td>
                        <td class="name px-2 py-2 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                            {{$key}}
                        </td>
                        <td class="text-center px-2 py-2 text-xs text-gray-800 whitespace-nowrap dark:text-neutral-200 font-semibold">
                            {{$value}}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">
                        <div class="py-10 text-center text-lg text-slate-400">No Records Found!</div>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>


</div>
