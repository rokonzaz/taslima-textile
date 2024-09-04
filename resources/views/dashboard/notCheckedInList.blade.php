<div class="flex flex-col ">
    @if($notCheckedInList->count()>0)
        <div class="inline-block min-w-full overflow-hidden overflow-y-auto align-middle h-[20rem]">
            <table class="min-w-full  divide-y divide-gray-200 dark:divide-neutral-700 text-xs">
                <thead class="sticky top-0">
                <tr class="bg-gray-200 dark:bg-neutral-900 text-xs">
                    <th class="text-left px-2 py-2   font-semibold uppercase dark:text-teal-500">
                        Name
                    </th>
                    <th class="text-left px-2 py-2   font-semibold uppercase dark:text-teal-500">
                        Status</th>
                </tr>
                </thead>
                <tbody>

                @foreach($notCheckedInList as $item)
                    @php
                        $isLeave=$item->leaveDateWise($date, $date)
                    @endphp
                    <tr
                        class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                        <td class="px-2 py-2 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                            <p class="">{{$item->full_name}}  <span class="text-xs text-gray-700 dark:text-gray-400">({{$item->emp_id}})</span></p>
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-800 whitespace-nowrap dark:text-neutral-200 font-semibold">
                            @if($isLeave)
                                <span class="text-teal-600">Leave</span>
                            @else
                                <span class="text-[#831b94]">Absent</span>
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

</div>
