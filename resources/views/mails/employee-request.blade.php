

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Details</title>
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
        <h3>{{ __($mailData->request_type)  }} Request</h3>
    </div>
    <div class="content">
        <table class="details">
            <tr>
                <th>Employee</th>
                <td><b>{{$mailData->employee->full_name??''}} ({{$mailData->employee->emp_id??""}})</b></td>
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
            @if(in_array($mailData->request_type, ['late-arrival','early-exit']))
                <tr>
                    <th>Date/Time</th>
                    <td><b>{{ formatCarbonDate($mailData->date) }} | <span class="text-red">{{ formatCarbonDate($mailData->time, 'time') }}</span></b></td>
                </tr>
            @endif
            @if(in_array($mailData->request_type, ['home-office']))
                <tr>
                    <th>Date</th>
                    <td>
                        <b>
                            @if($mailData->start_date == $mailData->end_date)
                                {{ formatCarbonDate($mailData->start_date) }}
                            @else
                                {{ formatCarbonDate($mailData->start_date) }} to {{ formatCarbonDate($mailData->end_date) }}
                                ({{ $mailData->intended_days }} days)
                            @endif
                        </b>
                    </td>
                </tr>
            @endif

            <tr>
                <th>Reason</th>
                <td>
                    <div>{{$mailData->reason ?? 'N/A'}}</div>
                    @if($mailData->note!='')
                        <div style="font-size: 12px; margin-top:4px">Note: {{$mailData->note}}</div>
                    @endif

                </td>
            </tr>

        </table>
        @if($mailData->approvalPermission==1)
            <div class="" style="text-align: center; margin-top:20px">
                <a href="{{$mailData->previewUrl}}" class="btn text-white" style="color: #fff">View Request</a>
            </div>
        @endif
    </div>
    <div class="footer">
        <p>&copy; {{date('Y')}} Nexdecade Technology Ltd.. All rights reserved.</p>
    </div>
</div>
</body>
</html>
