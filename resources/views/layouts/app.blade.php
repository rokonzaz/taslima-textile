@php $lastSync=\App\Models\Attendance::orderBy('sync_dtime', 'desc')->first()->sync_dtime ?? ''; @endphp


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') :: NEXHRM</title>
    @include('layouts.headerAssets')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F0F1F2] dark:bg-neutral-900 print:min-h-fit print:bg-transparent w-full overflow-x-hidden">
    @include('layouts.topnav', ['lastSync' => $lastSync])
    @include('layouts.sidebar')
    <!-- Content -->
    @if(request()->is('settings','settings/*'))
        <div class="w-full px-4 pt-2 sm:px-6 md:px-8 lg:ps-[16.5rem]  dark:bg-neutral-900  ">
            <!-- ========== MAIN CONTENT ========== -->
            <div id="content">
                <div class="grid grid-cols-5 gap-0">
                    <div class="">
                        @include('layouts.settings-sidebar')
                    </div>
                    <div class="col-span-4">
                        <div class="flex items-center justify-between mb-2 print:hidden">
                            {{--<div class="">
                                <h3 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">@yield('pageTitle')</h3>
                                --}}{{-- BREADCUMB --}}{{--
                                <div class="">@yield('breadcumb')</div>
                            </div>--}}

                            <div class="grow">
                                <div class="w-full flex items-center justify-end gap-x-2">@yield('additionalButton')</div>
                            </div>

                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
            <!-- ========== MAIN CONTENT END ========== -->
        </div>
    @else
        <div id="main-container" class="w-full  lg:pr-0 md:pl-64 {{--lg:pl-[70px]--}} dark:bg-neutral-900">
            <div class="px-4 min-h-[calc(100vh_-_7.2rem)]">
                <div class="flex items-center justify-between print:hidden">
                    {{--<div class="">
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">@yield('pageTitle')</h3>
                         BREADCUMB
                        <div class="">@yield('breadcumb')</div>
                    </div>--}}
                    <div class="grow">
                        <div class="w-full flex items-center justify-end gap-x-2">@yield('additionalButton')</div>
                    </div>

                </div>
                <!-- ========== MAIN CONTENT ========== -->
                <div id="content">
                    @yield('content')

                </div>
                <!-- ========== MAIN CONTENT END ========== -->
            </div>
            @include('layouts.footer')
            @if(userCan('attendance.sync'))
                @include('attendance.sync', ['lastSync' => $lastSync])
            @endif


        </div>
    @endif
    <!-- End Content -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        $(document).ready(function() {
            var isCollapsed = localStorage.getItem('sidebarState');
            if (isCollapsed === '1') {
                $('#sidebar').css({ width: '70px' });
                $('#sidebar').removeClass('toggle-full').addClass('toggle-collapsed');
                $('#main-container').removeClass('md:pl-64').addClass('toggle-pl-70');
                $('#topnav').removeClass('lg:ps-64').addClass('toggle-pl-70');
                $('#toggleButton').attr('data-value', '1');
                $('#toggleButton i').removeClass('rotate-0').addClass('rotate-180');
            } else {
                $('#sidebar').css({ width: '256px' });
                $('#sidebar').removeClass('toggle-collapsed').addClass('toggle-full');
                $('#main-container').removeClass('toggle-pl-70').addClass('md:pl-64');
                $('#topnav').removeClass('toggle-pl-70').addClass('lg:ps-64');
                $('#toggleButton').attr('data-value', '0');
                $('#toggleButton i').removeClass('rotate-180').addClass('rotate-0');
            }
        });
    </script>
    @include('layouts.spinners')
    @include('layouts.modals')
    @include('layouts.footerAssets')
    @yield('scripts')
</body>
@yield('modals')
</html>
