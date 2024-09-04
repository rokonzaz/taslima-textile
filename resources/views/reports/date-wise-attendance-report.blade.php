<table class="cell-border text-base text-center" id="dataTable">
    <thead>
        <tr>
            <th>S/L</th>
            <th>Employee name</th>
            <th>Employee Id</th>
            <th>Department</th>
            <th>Designation</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Present/Absent</th>
            <th>Late</th>
            <th>Overtime</th>
        </tr>
    </thead>

    <tbody>
    @php $employeeReport=$attendanceReport['data'] @endphp
    @foreach($employeeReport as $key=>$item)
        <tr class="">
            <td>{{$key+1}}</td>
            <td>{{$item['emp_name']}}</td>
            <td>{{$item['emp_id']}}</td>
            <td>{{$item['emp_department']}}</td>
            <td>{{$item['emp_designation']}}</td>
            @if(isset($item['dateWiseAttendanceData'][$date]))
                @php $attendanceData=$item['dateWiseAttendanceData'][$date]; @endphp
                @if($attendanceData['comment']=='Present')
                    @if(isset($attendanceData['clockIn']))
                        <td>
                            @if($attendanceData['clockIn']!='')
                                <span class="truncate">{{$date}}</span> <br> {{$attendanceData['clockIn']}}
                            @else
                                -
                            @endif

                        </td>
                        <td>
                            @if($attendanceData['clockOut']!='')
                                <span class="truncate">{{$date}}</span>  <br> {{$attendanceData['clockOut']}}
                           @else
                                -
                           @endif
                        </td>
                        <td>{{$attendanceData['comment']}}</td>
                        <td>
                            @if($attendanceData['late']!='')
                                <span>Late-({{$attendanceData['late']}})</span>
                            @endif
                        </td>
                        <td>{{$attendanceData['overtime']}}</td>
                    @else
                        <td></td>
                        <td></td>
                        <td>{{$attendanceData['comment']}}</td>
                        <td></td>
                        <td>{{$attendanceData['overtime']}}</td>
                    @endif
                @else
                    <td></td>
                    <td></td>
                    <td>{{$attendanceData['comment']}}</td>
                    <td></td>
                    <td>{{$attendanceData['overtime']}}</td>
                @endif
            @else
                <td></td>
                <td></td>
                <td>{{$attendanceData['comment']}}</td>
                <td></td>
                <td>{{$attendanceData['overtime']}}</td>
            @endif

        </tr>
    @endforeach
    </tbody>
    @php $summary=$attendanceReport['globalSummary'] @endphp
    <tfoot class="">
        <tr class=" font-bold">
            <td class="text-2xl"></td>
            <td class="text-2xl">Present</td>
            <td class="text-2xl">{{ $summary['present'] }}</td>
            <td class="text-2xl">Absent</td>
            <td class="text-2xl">{{ $summary['absent'] }}</td>
            <td class="text-2xl">Leave</td>
            <td class="text-2xl">{{ $summary['leave'] }}</td>
            <td class="text-2xl">Late</td>
            <td class="text-2xl">{{ $summary['late'] }}</td>
            <td class="text-2xl"></td>
        </tr>
    </tfoot>
</table>

