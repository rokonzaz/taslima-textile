<div class="flex flex-col ">
    <div class="inline-block min-w-full overflow-y-auto align-middle h-[20rem]">
        <table class="min-w-full  divide-y divide-gray-200 dark:divide-neutral-700 text-xs">
            <thead class="sticky top-0 z-10">
            <tr class="bg-gray-200 dark:bg-neutral-900 text-xs">
                <th class="text-left px-2 py-2 font-semibold uppercase dark:text-teal-500">
                    S/L
                </th>
                <th class="text-left px-2 py-2   font-semibold uppercase dark:text-teal-500">
                    Date
                </th>
                <th class="text-left px-2 py-2   font-semibold uppercase dark:text-teal-500">
                    Reason
                </th>
            </tr>
            <tr class="bg-gray-200 dark:bg-neutral-900 text-xs">
                <td class="text-left px-1 pb-1 font-semibold uppercase dark:text-teal-500" colspan="3">
                    <input type="text" class="search inputFieldCompact !py-1 !text-xs" id="" placeholder="Type to search...">
                </td>
            </tr>
            </thead>
            <tbody class="list">
            @if($empLeave->count()>0)
                @php $sl=1; @endphp
                @foreach($empLeave as $key=>$item)
                    <tr
                        class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                        <td class="px-2 py-2 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                            <p class="">{{$sl++}}</p>
                        </td>
                        <td class="name px-2 py-2 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                            @if($item->start_date == $item->end_date)
                                {{formatCarbonDate($item->start_date)}}
                            @else
                                {{formatCarbonDate($item->start_date)}} - {{formatCarbonDate($item->end_date)}} ({{dateToDateCount($item->start_date, $item->end_date)}})
                            @endif
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-800 dark:text-neutral-200 font-semibold">
                            <div class="name">{{$item->leaveType->name ?? ''}}</div>
                            <div class="tooltip tooltip-wrap tooltip-bottom text-left after:w-100" data-tip="{{$item->leave_reason}}">
                                <div class="max-w-48">
                                    <p class="name truncate">{{$item->leave_reason}}</span></p>
                                </div>
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
