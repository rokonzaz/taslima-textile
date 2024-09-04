@extends('layouts.app')
@section('title', 'Reports')
@section('pageTitle', 'Reports')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Reports', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection
@php
    use Carbon\Carbon;
@endphp
@section('content')
    <x-containers.container-box>

        <table class="w-full whitespace-nowrap  overflow-x-auto">
            <tr class="border">
                <th class="p-3 border">S/L</th>
                <th class="p-3 border">Emp Id</th>
                <th class="p-3 border">Name</th>
                @php $currentDate = $startDateTime->copy() @endphp
                @while($currentDate <= $endDateTime)
                    <td class="p-3 border">{{$currentDate->format('d M')}}</td>
                    @php $currentDate->addDay() @endphp
                @endwhile
            </tr>
            @foreach($employee as $key=>$item)
                @php
                    $dutySlotId=$item->duty_slot;
                    $dutySlot=$dutySlots->find($item->duty_slot);
                    $leaveCount=0;
                    $lateCount=0;
                    $earlyLeaveCount=0;
                    $overtimeCount=0;
                @endphp
                <tr class="border">
                    <td class="p-3 border">{{$key+1}}</td>
                    <td class="p-3 border">{{$item->emp_id}}</td>
                    <td class="p-3 border">{{$item->full_name}}</td>
                    @php $currentDate = $startDateTime->copy() @endphp
                    @while($currentDate <= $endDateTime)
                        @php $formattedCurrentDate=$currentDate->format('Y-m-d') @endphp
                        <td class="p-3 border">

                            @if(in_array($currentDate->format('l'), $weekends))
                                W
                            @elseif(isset($allLeave[$item->emp_id][$formattedCurrentDate]))
                                @php $leaveCount++ @endphp
                                Leave
                            @else
                                @if($item->biometric_id!='')
                                    @if(isset($attendanceData[$item->biometric_id][$formattedCurrentDate]))
                                        @php
                                            $attendance=$attendanceData[$item->biometric_id][$formattedCurrentDate] ;
                                            $clockIn='';
                                            $clockOut='';
                                            $late='';
                                            $earlyLeave='';
                                            $overtime='';
                                        @endphp
                                        @if(isset($attendance['clockIn']))
                                            @php
                                                $clockIn = Carbon::parse($attendance['clockIn']['DateTime'])->format('h:i:s A');
                                                if($dutySlot){
                                                    $dutyStartTime=isset($dutySlotRules[$dutySlotId][$formattedCurrentDate]) ? Carbon::parse($dutySlotRules[$dutySlotId][$formattedCurrentDate]['start_time']) : Carbon::parse($dutySlot->start_time);
                                                    $dutyThresholdTime=isset($dutySlotRules[$dutySlotId][$formattedCurrentDate]) ? Carbon::parse($dutySlotRules[$dutySlotId][$formattedCurrentDate]['threshold_time']) : Carbon::parse($dutySlot->threshold_time);
                                                    if (Carbon::parse($clockIn) > $dutyThresholdTime) {
                                                        $dif = Carbon::parse($clockIn)->diffInMinutes($dutyStartTime);
                                                        $late=minutesToHour($dif);
                                                        $lateCount++;
                                                    }
                                                }
                                            @endphp
                                        @else
                                        @endif

                                        @if(isset($attendance['clockOut']))
                                            @php
                                                $clockOut = Carbon::parse($attendance['clockOut']['DateTime'])->format('h:i:s A');
                                                if($dutySlot){
                                                    $dutyEndTime=isset($dutySlotRules[$dutySlotId][$formattedCurrentDate]) ? Carbon::parse($dutySlotRules[$dutySlotId][$formattedCurrentDate]['end_time']) : Carbon::parse($dutySlot->end_time);
                                                    if (Carbon::parse($clockOut) < $dutyEndTime) {
                                                        $dif = Carbon::parse($clockOut)->diffInMinutes($dutyEndTime);
                                                        $earlyLeave=minutesToHour($dif);
                                                        $earlyLeaveCount++;
                                                    }else{
                                                        $dif = Carbon::parse($clockOut)->diffInMinutes($dutyEndTime);
                                                        $overtimeCount+=$dif;
                                                        $overtime=minutesToHour($dif);
                                                    }
                                                }
                                            @endphp
                                        @else

                                        @endif

                                        <table class="whitespace-nowrap">
                                            <tr>
                                                <td class="border p-1">In: {{$clockIn}}</td>
                                            </tr>
                                            <tr>
                                                <td class="border p-1">Out: {{$clockOut}}</td>
                                            </tr>
                                            <tr>
                                                <td class="border p-1">LE: {{$late}}</td>
                                            </tr>
                                            <tr>
                                                <td class="border p-1">EL: {{$earlyLeave}}</td>
                                            </tr>
                                            <tr>
                                                <td class="border p-1">OT: {{$overtime}}</td>
                                            </tr>
                                        </table>

                                    @else
                                         {{--Attendance Not Found--}}
                                    @endif
                                @else
                                    -- {{--No Biometric ID--}}
                                @endif
                            @endif

                            {{--@if(isset($attendanceData['842544708']['2024-07-01']))

                            @else
                                N/A
                            @endif--}}

                        </td>

                        @php $currentDate->addDay() @endphp
                    @endwhile
                    <td>
                        <table class="whitespace-nowrap">
                            <tr>
                                <td class="border p-1">Leave: {{$leaveCount}}</td>
                            </tr>
                            <tr>
                                <td class="border p-1">Late: {{$lateCount}}</td>
                            </tr>
                            <tr>
                                <td class="border p-1">Early Leave: {{$earlyLeaveCount}}</td>
                            </tr>
                            <tr>
                                <td class="border p-1">Overtime: {{minutesToHour($overtimeCount)}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endforeach
        </table>
    </x-containers.container-box>
@endsection
@include('department.create')
@section('scripts')

@endsection
