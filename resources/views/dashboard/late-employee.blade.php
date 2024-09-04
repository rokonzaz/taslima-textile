<div class="flex flex-col ">
    <div class="inline-block min-w-full overflow-hidden overflow-y-auto align-middle h-[20rem]">
        <table class="min-w-full  divide-y divide-gray-200 dark:divide-neutral-700 text-xs">
            <thead class="sticky top-0 z-10">
            <tr class="bg-gray-200 dark:bg-neutral-900 text-xs">
                <th class="text-left px-2 py-2   font-semibold uppercase dark:text-teal-500">
                    S/L
                </th>
                <th class="text-left px-2 py-2   font-semibold uppercase dark:text-teal-500">
                    Name
                </th>
                <th class="text-left px-2 py-2   font-semibold uppercase dark:text-teal-500">
                    Thresh
                </th>
                <th class="text-left px-2 py-2   font-semibold uppercase dark:text-teal-500">
                    Late
                </th>
            </tr>
            <tr class="bg-gray-200 dark:bg-neutral-900 text-xs">
                <td class="text-left px-1 pb-1 font-semibold uppercase dark:text-teal-500" colspan="4">
                    <input type="text" class="search inputFieldCompact !py-1 !text-xs" id="" placeholder="Type to search...">
                </td>
            </tr>
            </thead>
            <tbody class="list">
            @if(count($lateEmployees)>0)
                @foreach($lateEmployees as $key=>$item)
                    <tr
                        class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                        <td class="px-2 py-2 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                            <p class="">{{$key+1}}</p>
                        </td>
                        <td class="px-2 py-2 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                            <div class="tooltip text-left" data-tip="{{$item['emp_name']}} ({{$item['emp_id']}})">
                                <div class="w-36">
                                    <p class="name truncate">{{$item['emp_name']}}  <span class="text-xs text-gray-700 dark:text-gray-400">({{$item['emp_id']}})</span></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-800 whitespace-nowrap dark:text-neutral-200 font-semibold">
                            <div class="tooltip tooltip-left tooltip-wrap text-left" data-tip="Start Time: {{$item['dutyStartTime']}}">
                                {{$item['dutyThresholdTime']}}
                            </div>
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-800 whitespace-nowrap dark:text-neutral-200 font-semibold">
                            <div class="tooltip tooltip-left tooltip-wrap text-left" data-tip="Clock In: {{$item['clockIn']}}">
                                {{$item['late']}}
                            </div>

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
