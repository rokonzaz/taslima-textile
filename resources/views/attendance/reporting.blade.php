@extends('layouts.app')
@section('title', 'Attendance Reporting')
@section('pageTitle', 'Attendance Reporting')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Attendance Reporting', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection
@php use Carbon\Carbon; @endphp
@section('content')
    <x-containers.container-box>
        <div class="">Month: May - 2024</div>
        <div class="w-full overflow-x-auto">
            <table class="table">
                <tr>
                    <th>SL.</th>
                    <th>Name</th>
                    @for($i=1; $i<=31; $i++)
                        <th>{{$i}} May</th>
                    @endfor
                </tr>
                @php $sl=1 @endphp
                @foreach($employee as $item)
                    <tr>
                        <td>{{$sl}}</td>
                        <td class="truncate">
                            <p>{{$item->full_name}}</p>
                            <p class="text-xs">{{$item->empDesignation->name}}</p>
                            <p class="text-xs">{{$item->empDepartment->name}}</p>

                        </td>

                            @php
                                $date="2024-05-$i";
                                $start_date="2024-05-01";
                                $end_date="2024-05-31";
                                $attendance=$item->attendanceData($start_date, $end_date);
                                /*$attendanceCount = $attendance->count();
                                $sortedAttendances = $attendance->sortBy('DateTime');
                                $groupedAttendances = $attendance->groupBy('DateTime');*/



                                $attendance = $attendance->map(function ($record) {
                                    $record->DateTime = Carbon::parse($record->DateTime);
                                    return $record;
                                });
                                $groupedAttendances = $attendance->groupBy(function ($record) {
                                    return $record->DateTime->toDateString();
                                });
                                $sortedGroupedAttendances = $groupedAttendances->sortKeys();
                                $isHomeOffice=false;
                                if($item->empDepartment->name=='IT'){
                                    $isHomeOffice=true;
                                }
                            @endphp

                            @for ($i = 1; $i <= 31; $i++)
                                @php
                                    // Create the date string for the current day
                                    $date = Carbon::createFromDate(2024, 5, $i)->toDateString();
                                    // Get attendance records for the current date
                                    $records = $sortedGroupedAttendances->get($date, collect());
                                    $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
                                @endphp
                                <td class="truncate">
                                    @if ($carbonDate->dayOfWeek === Carbon::FRIDAY)
                                        Off Day
                                    @else
                                        @if ($records->isNotEmpty())
                                            @php
                                                $clockInAtt=$records->first();
                                                $clockIn = $clockInAtt->DateTime;

                                                $attendanceCount=$records->count();
                                                if($attendanceCount == 1){
                                                    $clockOut=null;
                                                }else{
                                                    $clockOutAtt=$records->last();
                                                    $clockOut=$clockOutAtt['DateTime'];
                                                }
                                                $dutySlotStartTime=strtotime("$date 10:00:00");
                                                $dutySlotThresholdTime=strtotime("$date 10:20:00");
                                                $clockInTime=strtotime($clockIn);
                                                $late='';
                                                if($clockInTime>$dutySlotThresholdTime) {
                                                    $lateCount = floor(($clockInTime - $dutySlotStartTime) / 60);
                                                    $hours = floor($lateCount / 60); // Calculate hours
                                                    $minutes = $lateCount % 60;
                                                    if($hours>0) $late = $hours . 'h ' .$minutes.'m';
                                                    else $late = $minutes.'m';
                                                }
                                            @endphp
                                        @if($late!='')
                                                <table>
                                                    {{--<tr><td class="p-0 border px-1 py-0.5">In: {{date('h:ia', strtotime($clockIn))}}</td></tr>
                                                    <tr><td class="p-0 border px-1 py-0.5">Out: {{date('h:ia', strtotime($clockOut))}}</td></tr>--}}
                                                    <tr><td class="p-0 border px-1 py-0.5 {{$late!=''?'text-[#831b94] bg-red-50 font-medium' : ''}}">Late: {{$late}}</td></tr>
                                                </table>
                                        @endif

                                        @else

                                            <p>No records</p>
                                        @endif
                                    @endif



                                    {{--<strong>{{ $date }}</strong>
                                    @if ($records->isNotEmpty())
                                        <ul>
                                            @foreach ($records as $record)
                                                <li>{{ $record->DateTime->toTimeString() }} - Verified: {{ $record->Verified }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>No records</p>
                                    @endif--}}
                                </td>
                            @endfor

                        <td>

                            {{--@php
                                $date="2024-05-$i";
                                $start_date="2024-05-$i";
                                $end_date="2024-05-$i";
                                $clockIn=null;
                                $clockOut=null;
                                $clockInTime=null;
                                $clockOutTime=null;
                                $attendance=$item->attendanceData($start_date, $end_date);
                                $attendanceCount = $attendance->count();
                                if($attendanceCount>0){
                                    $sortedAttendances = $attendance->sortBy('DateTime');
                                    // Clock In
                                    $clockInAtt=$sortedAttendances->first();
                                    $clockIn = $clockInAtt['DateTime'];

                                    // Clock out
                                    if($attendanceCount == 1){
                                        $clockOut=null;
                                    }else{
                                        $clockOutAtt=$sortedAttendances->last();
                                        $clockOut=$clockOutAtt['DateTime'];
                                        $isManualClockOut=$clockOutAtt['is_manual'];
                                    }

                                    // Late Count
                                    $clockInTime = strtotime($clockIn);
                                    /*$dutySlotStartTime = strtotime("$date $startTime");
                                    $dutySlotThresholdTime = strtotime("$date $thresholdTime");
                                    if($clockInTime>$dutySlotThresholdTime) {
                                        $lateCount = floor(($clockInTime - $dutySlotStartTime) / 60);
                                        $hours = floor($lateCount / 60); // Calculate hours
                                        $minutes = $lateCount % 60;
                                        if($hours>0) $late = $hours . 'h ' .$minutes.'m';
                                        else $late = $minutes.'m';
                                    }
                                    else{
                                        //$late = "Not Late";
                                    }*/
                                    // Over Time
                                    $clockOutTime = strtotime($clockOut);
                                    /*$dutySlotEndTime = strtotime("$date $endTime");
                                    if($clockOut){
                                        if($clockOutTime>$dutySlotEndTime) {
                                            $lateCount = floor(($clockOutTime - $dutySlotEndTime) / 60);
                                            $hours = floor($lateCount / 60); // Calculate hours
                                            $minutes = $lateCount % 60;
                                            if($hours>0) $overtime = $hours . 'h ' .$minutes.'m';
                                            else $overtime = $minutes.'m';
                                        }
                                    }

                                    // Early Leaving
                                    if(time()>$dutySlotEndTime && $clockOut){
                                        if($clockOutTime<$dutySlotEndTime) {
                                            $lateCount = floor(($dutySlotEndTime - $clockOutTime) / 60);
                                            $hours = floor($lateCount / 60); // Calculate hours
                                            $minutes = $lateCount % 60;
                                            if($hours>0) $earlyLeaving = $hours . 'h ' .$minutes.'m';
                                            else $earlyLeaving = $minutes.'m';
                                        }
                                    }*/
                                }

                            @endphp--}}

                        </td>
                    </tr>
                    @php $sl++ @endphp
                @endforeach
            </table>
        </div>

    </x-containers.container-box>
@endsection



