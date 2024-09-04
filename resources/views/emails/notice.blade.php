<!DOCTYPE html>
<html>
<head>
    <title>Notice Email</title>
</head>
<body>
    <span class="font-bold" style="font-weight: bold;">Important notice for {{ $mailData->notice_type }}</span> <br><br>
    <span class="font-bold" style="font-weight: bold;">Notice By:</span> {{ $mailData->notice_by }} <br><br>
    {{-- {{ $mailData->notice_description }} <br><br> --}}
    {!! nl2br(e($mailData->notice_description)) !!} <br><br>
    @if($mailData->notice_file)
        <a href="{{ url($mailData->notice_file) }}" target="_blank">View Attached Notice</a>
    @endif
</body>
</html>
