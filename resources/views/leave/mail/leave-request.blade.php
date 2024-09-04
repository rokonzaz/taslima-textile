

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 10px auto;
            background-color: #ffffff;
            border:1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #DC2626;;
            color: #ffffff;
            text-align: center;
            padding: 2px;
        }
        .text-white{
            color: #ffffff;
        }
        .text-red{
            color: #DC2626;
        }
        .content {
            padding: 20px;
        }
        .content h2 {
            color: #333333;
        }
        .content p {
            color: #666666;
        }
        .details {
            width: 100%;
            border-collapse: collapse;
        }
        .details th, .details td {
            padding: 6px 12px;
            border: 1px solid #dddddd;
            text-align: left;
        }
        .details th {
            background-color: #fafafa;
        }
        .footer {
            background-color: #f4f4f4;
            text-align: center;
            padding: 6px;
            color: #888888;
            font-size: 12px;
        }
        .manage-link{
            color:#333333;
            text-decoration: none;
        }
        .manage-link:hover{
            color:#DC2626;
            text-decoration: underline;
        }
        .btn{
            padding:8px 16px;
            background: #DC2626;
            color: white;
            font-size: 18px;
            text-align: center;
            text-decoration: none;
            border-radius: 40px;
        }
        .btn:hover{
            background: #620202;
        }
    </style>
</head>
<body>




<div class="container">
    <div class="header">
        <h3>{{ $mailData->requisitionType->name ?? 'Leave' }} Request</h3>
    </div>
    <div class="content">
        <table class="details">
            <tr>
                <th>Leave Type</th>
                <td style="font-weight: 600">{{$mailData->leaveType->name ?? ''}}</td>
            </tr>
            <tr>
                <th>Employee</th>
                <td>{{$mailData->employee->full_name??''}} ({{$mailData->employee->emp_id??""}})</td>
            </tr>
            <tr>
                <th>Organization</th>
                <td>{{$mailData->employee->empOrganization->name??''}}</td>
            </tr>
            <tr>
                <th>Department</th>
                <td>{{$mailData->employee->empDepartment->name??""}}</td>
            </tr>
            <tr>
                <th>Designation</th>
                <td>{{$mailData->employee->empDesignation->name??''}}</td>
            </tr>

            <tr>
                <th>Date</th>
                <td>
                    @if($mailData->start_date == $mailData->end_date)
                        {{ date('d-m-Y', strtotime($mailData->start_date)) }}
                    @else
                        {{ date('d-m-Y', strtotime($mailData->start_date)) }} to {{ date('d-m-Y', strtotime($mailData->end_date)) }}
                        ({{ $mailData->intended_leave_days }} days)
                    @endif
                </td>
            </tr>
            <tr>
                <th>Reason</th>
                <td>{{$mailData->leave_reason ?? 'N/A'}}</td>
            </tr>
            @if($mailData->remarks!='')
                <tr>
                    <th>Remarks</th>
                    <td>{{$mailData->remarks ?? 'N/A'}}</td>
                </tr>
            @endif
            <tr>
                <th>Reliever</th>
                <td>
                    @if($mailData->reliever_emp_id!='')
                        @if(isset($mailData->leaveReliever))
                            @php $reliever=$mailData->leaveReliever @endphp
                            {{$reliever->full_name ?? ''}} ({{$reliever->empDepartment->name ?? ''}})
                        @else
                            N/A
                        @endif
                    @else
                        N/A
                    @endif
                </td>
            </tr>

        </table>
        @if(isset($othersData['mail_as']))
            @if($othersData['mail_as']=='reliever')
                <div class="" style="margin-top: 10px">
                    <p class="text-red" style="font-size: 14px">
                        Please be informed that {{$mailData->employee->full_name??''}} has submitted a leave request. As you are the designated leave reliever, kindly take note of his absence.
                        <br>
                        Thank you for your cooperation.
                    </p>
                </div>
            @endif
        @endif
        @if($mailData->approvalPermission==1)
            <div class="" style="text-align: center; margin-top: 10px">
                <a href="{{$mailData->previewUrl}}" class="btn text-white" style="color: #fff">View Request</a>
            </div>
        @endif

    </div>
    <div class="footer">
        <p>&copy; 2024 Nexdecade Technology Ltd.. All rights reserved.</p>
    </div>
</div>
</body>
</html>
