@php
    use Illuminate\Support\Carbon;
    use App\Models\Holiday;
    $currentMonth = Carbon::now()->startOfMonth();
    $nextMonth = Carbon::now()->addMonth()->startOfMonth();
    $holiday = Holiday::where(function ($query) use ($currentMonth, $nextMonth) {
        $query->whereBetween('start_date', [$currentMonth, $nextMonth])
            ->orWhereBetween('end_date', [$currentMonth, $nextMonth])
            ->orWhere(function ($query) use ($currentMonth, $nextMonth) {
                $query->where('start_date', '<=', $currentMonth)
                    ->where('end_date', '>=', $nextMonth);
            });
    })
        ->orderBy('start_date', 'desc')
        ->limit(10)
        ->get();
@endphp

<div id="" class="bg-white border shadow-md rounded-lg h-50 dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
    <div class="flex items-center justify-between px-2 pr-1.5 py-1.5 border-b rounded-t-lg dark:border-neutral-700">
        <h3 class="text-base font-bold text-gray-800 dark:text-white">
            Holiday
        </h3>
        <a href="{{route('holiday.index')}}" class="submit-button-sm">See All</a>
    </div>
    <div id="">
        <div class="overflow-hidden overflow-y-auto max-h-[24rem]">
            @if($holiday->count()>0)
                <div class="grid grid-cols-1 gap-4 px-5 py-5">
                    @php $today = now(); @endphp
                    @foreach($holiday as $key=>$item)
                        @php
                            $startDate = \Carbon\Carbon::parse($item->start_date);
                            $bgColor = $startDate->lessThan($today) ? 'bg-[#831b94]' : 'bg-teal-600';
                            $borderColor = $startDate->lessThan($today) ? 'border-red-600' : 'border-teal-600';
                        @endphp
                        <div class="">
                            <div class="flex items-center gap-4">
                                <div class="">
                                    <div class="rounded-md border overflow-hidden w-24 text-center {{$borderColor}}">
                                        <p class="{{$bgColor}} text-white font-medium py-0.5">{{date('d M', strtotime($item->start_date))}}</p>
                                        <p class="py-1">{{date('Y', strtotime($item->start_date))}}</p>
                                    </div>
                                </div>
                                <div class="">
                                    <p class="font-semibold">{{$item->name}}</p>
                                    <p class="text-sm text-gray-600">{{date('l', strtotime($item->start_date))}}</p>
                                    @if($item->start_date != $item->end_date)
                                        <div class="flex items-center gap-2">
                                            <p class="text-xs">{{date('d M Y', strtotime($item->start_date))}} to {{date('d M Y', strtotime($item->end_date))}}</p>
                                            <span class="py-0.5 px-1 rounded-md text-xs bg-gray-500 text-white">{{dateToDateCount($item->start_date, $item->end_date)}} days</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center py-32">No data found!</p>
            @endif
        </div>
    </div>
</div>
