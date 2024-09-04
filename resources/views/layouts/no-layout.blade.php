<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') :: NEXHRM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('layouts.headerAssets')
</head>

<body class="min-h-screen dark:bg-neutral-900 print:min-h-fit bg-slate-50">
    <div class="">
        @yield('content')
    </div>
    <!-- End Content -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
    @include('layouts.spinners')
    @include('layouts.modals')
    @include('layouts.footerAssets')
    @yield('scripts')
</body>
@yield('modals')
</html>
