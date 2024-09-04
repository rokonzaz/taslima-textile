<!DOCTYPE html>
<html>
<head>
    <style>
        body{
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
        .links{
            margin-top: 10px;
        }
        .links a{
            color: #DC2626;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        {{$mailData->leaveType->name??''}} Leave Request for {{$mailData->employee->full_name??''}} ({{$mailData->employee->emp_id ??''}})
    </div>
    <div class="content">
        Dear Bhaia,
        <br>
        <p>
            I am writing to formally request a <b>{{ $mailData->requisitionType->name ?? '' }}</b>
                @if($mailData->start_date == $mailData->end_date)
                on <b>{{ \Carbon\Carbon::parse($mailData->start_date)->isoFormat('D MMMM YYYY') }}</b>
                @else
                    from <b>{{ \Carbon\Carbon::parse($mailData->start_date)->isoFormat('D MMMM YYYY') }} to {{ \Carbon\Carbon::parse($mailData->end_date)->isoFormat('D MMMM YYYY') }}
                    ({{ \Carbon\Carbon::parse($mailData->start_date)->diffInDays($mailData->end_date) + 1 }} days)</b>
                @endif
            .
            <br>
            <br>
            <b><u>Reason:</u></b>
            <br>
            @if($mailData->leave_reason)
                {{ $mailData->leave_reason }}
            @else
                N/A
            @endif
            <br>
            <br>
            Please let me know if you need any further information or if there are any formalities I need to complete. I appreciate your understanding and approval of this leave request.
        </p>

        Thank you for your consideration.
    </div>
    <div class="footer">
        Best regards,
        <br>
        {{$mailData->employee->full_name??''}}<br>
        {{$mailData->employee->empDepartment->name ??''}} ({{$mailData->employee->empDesignation->name??''}})<br>
        {{$mailData->employee->email??''}}<br>
        {{$mailData->employee->phone??''}}
    </div>
    <div class="links">
        Leave Request Link: <a href="{{route('leaveRequest.email-preview', ['id'=>tripleBase64Encode($mailData->id)])}}">{{route('leaveRequest.email-preview', ['id'=>tripleBase64Encode($mailData->id)])}}</a>
    </div>
</div>
</body>
</html>
