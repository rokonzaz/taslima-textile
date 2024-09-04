@php use Carbon\Carbon; @endphp
<table class="table" id="dataTable">
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

    @php
        $dataCounts=[
            'present'=>0,
            'absent'=>0,
            'leave'=>0,
            'late'=>0,
            'overtime'=>0
        ];
    @endphp
    <tbody>
    @foreach($employee as $key=>$item)
        @php
            $attendanceData=(new \App\Http\Controllers\AttendanceController())->employeeAttendanceData($item, $start_date);
            $clockIn=$attendanceData['clockIn'];
            $clockOut=$attendanceData['clockOut'];
            $isAbsent=$clockIn=='';
            $isLeave=$attendanceData['leave']!='';
            $presentStatus=$isLeave ? 'Leave' : ($isAbsent ? 'Absent' : 'Present');
            $late=$isLeave ? '' : $attendanceData['late'];
            $overtime=$isLeave ? '' : $attendanceData['overtime'];

            $isPresent=!$isLeave;
             if ($presentStatus == 'Present') {
                $dataCounts['present']++;
            } elseif ($presentStatus == 'Absent') {
                $dataCounts['absent']++;
            } elseif ($presentStatus == 'Leave') {
                $dataCounts['leave']++;
            }
            if($late!='') $dataCounts['late']++;
            if($overtime!='') $dataCounts['overtime']++;

            $bgClass='';
            $textClass='';
            if($presentStatus=='Absent'){
                $bgClass='bg-red-50';
                $textClass='text-[#831b94]';
            }elseif($presentStatus=='Leave'){
                $bgClass='bg-yellow-50';
                $textClass='text-yellow-700';
            }
        @endphp
        <tr class="{{$bgClass}} {{$textClass}}">
            <td>{{$key+1}}</td>
            <td>{{$item->full_name}}</td>
            <td>{{$item->emp_id}}</td>
            <td>{{$item->empDepartment->name ?? ''}}</td>
            <td>{{$item->empDesignation->name ?? ''}}</td>
            <td>
                <p class="font-medium">{{$clockIn!='' ? date('h:i A', strtotime($clockIn)) : ''}}</p>
                <p class="text-[11px] text-teal-600">{{$clockIn!='' ? date('d M Y', strtotime($clockIn)) : ''}}</p>
            </td>
            <td>
                <p class="font-medium">{{$clockOut!='' ? date('h:i A', strtotime($clockOut)) : ''}}</p>
                <p class="text-[11px] text-teal-600">{{$clockOut!='' ? date('d M Y', strtotime($clockOut)) : ''}}</p>
            </td>
            <td>{{$presentStatus}}</td>
            <td>
                @if($late!='')
                    {{$late}}
                @endif
            </td>
            <td>
                @if($overtime!='')
                    {{$overtime}}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr class="text-md font-bold">
        <td></td>
        <td>Present</td>
        <td>{{ $dataCounts['present'] }}</td>
        <td>Absent</td>
        <td>{{ $dataCounts['absent'] }}</td>
        <td>Leave</td>
        <td>{{ $dataCounts['leave'] }}</td>
        <td>Late</td>
        <td>{{ $dataCounts['late'] }}</td>
        <td></td>
    </tr>
    </tfoot>
</table>

