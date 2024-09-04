@php
    use App\Models\Notice;
    $notices=Notice::orderBy('notice_date', 'desc')->limit(10)->get();
@endphp

<div id="" class="bg-white border shadow-md rounded-lg h-50 dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
    <div class="flex items-center justify-between px-2 pr-1.5 py-1.5 border-b rounded-t-lg dark:border-neutral-700">
        <h3 class="text-base font-bold text-gray-800 dark:text-white">
            Notices
        </h3>
        <a href="{{route('holiday.index')}}" class="submit-button-sm">See All</a>
    </div>
    <div id="">
        <div class="overflow-hidden overflow-y-auto max-h-[24rem]">
            @if($notices->count()>0)
                <table class="items-center bg-transparent w-full border-collapse ">
                    <tbody>
                    @foreach($notices as $key=>$item)
                        <tr class="border-b truncate">
                            <td class="border-t-0 px-2 align-center border-l-0 border-r-0 whitespace-nowrap">
                                @php
                                    $day=date('d', strtotime($item->notice_date));
                                    $month=date('M', strtotime($item->notice_date));
                                    $year=date('Y', strtotime($item->notice_date));
                                @endphp
                                <div class="text-center w-24">
                                    <p class="text-4xl text-[#831b94] font-semibold">{{$day}}</p>
                                    <span class="text-sm">{{$month}} {{$year}}</span>
                                </div>
                            </td>
                            <td class="px-2 align-middle w-full">
                                <p class="font-medium text-base">{{$item->notice_type}}</p>
                                <div class="w-full overflow-hidden text-sm text-gray-600">
                                    <p class="w-96 truncate">{{$item->notice_description}}</p>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            @else
                <p class="text-center py-32">No data found!</p>
            @endif
        </div>
    </div>
</div>
