@php
$ativeClass='active text-white font-semibold bg-[#831b94] dark:text-white active:text-white active:font-medium active:bg-[#831b94] dark:active:text-white';
$ativeSubClass='text-[#831b94] font-bold dark:text-[#831b94] active:text-[#831b94] active:font-bold active:bg-[#831b94] dark:active:text-[#831b94] hs-accordion-active:text-[#831b94] hs-accordion-active:font-bold dark:hs-accordion-active:text-[#831b94]';
/* $ativeSubClass=' text-white font-semibold bg-[#831b94] dark:text-white active:text-white active:font-semibold active:bg-[#831b94] dark:active:text-white hs-accordion-active:text-white hs-accordion-active:font-semibold hs-accordion-active:bg-[#831b94] dark:hs-accordion-active:text-white'; */
@endphp
<div id="sidebar" class="toggle-full {{--toggle-collapsed--}} text-[13px] hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden fixed top-0 start-0 bottom-0 z-[60]  bg-white border-e border-gray-200 pt-1 pb-10 {{--overflow-y-auto overflow-x-visible--}} lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-slate-700 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500 dark:bg-neutral-800 dark:border-neutral-700 dark:hs-overlay-backdrop-open:bg-neutral-900/90 print:hidden" style="width: 256px">
    {{-- <button id="toggleButton" class="hidden md:absolute right-[-1.1rem] top-2 hover:bg-[#831b94] hover:text-white border-2 bg-rose-100 rounded-full  !w-6 !h-6 p-[14px] z-10 md:flex navbar-toggler navbar-toggler md:justify-center md:items-center md:self-center" type="button" data-value='0'>
        <i class="fa-solid fa-angles-left rotate-180"></i>
    </button> --}}
    <div class="logo-padding">
        <a class="flex-none text-xl font-semibold dark:text-white" href="/" aria-label="Brand">
            <img src="/assets/img/logo-taslima.png" class="sidebar-logo-full" alt="Taslima Textile">
            <img src="{{url('assets/img/default/collapsed-logo.png')}}" class="sidebar-logo-minimized mx-auto" alt="Nexdecade Logo">
        </a>
    </div>

    <nav class="flex flex-col flex-wrap w-full p-4 py-4 hs-accordion-group relative" data-hs-accordion-always-open>
        <ul class="space-y-2.5 font-semibold">
            <!-- List of Sidebar items -->
            @php $isActive = request()->is('/') ? 'true' : 'false'; @endphp
            <li class="hs-accordion">
                <a id="staff-accordion" class="{{$isActive=='true' ? $ativeClass : ''}} flex items-center gap-x-3.5 py-2.5 px-2.5 text-black rounded-lg hover:bg-[#831b94] hover:text-white dark:hover:bg-[#831b94] dark:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="{{route('dashboard')}}">
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    <span class="sidebar-link-label">Dashboard</span>
                </a>
            </li>
            @if(getUserEmpId())
                @php $isActive = request()->is('my-requests', 'my-requests/*') ? 'true' : 'false'; @endphp
                <li class="hs-accordion">
                    <a  href="{{route('my-requests.index')}}" id="staff-accordion" class="{{$isActive=='true' ? $ativeClass : ''}} flex items-center gap-x-3.5 py-2.5 px-2.5 text-black rounded-lg hover:bg-[#831b94] hover:text-white dark:hover:bg-[#831b94] dark:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-plus" viewBox="0 0 16 16">
                            <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2zm3.708 6.208L1 11.105V5.383zM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2z"/>
                            <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5"/>
                        </svg>
                        <span class="sidebar-link-label">My Requests</span>
                    </a>
                </li>
            @endif
            @if(isManagableEployeesAvailable() || in_array(getUserRole(), ['super-admin', 'hr']))
                @php $isActive = request()->is('request-approvals', 'request-approvals/*') ? 'true' : 'false'; @endphp
                <li class="hs-accordion">
                    <a  href="{{route('request-approvals.index')}}" id="staff-accordion" class="{{$isActive=='true' ? $ativeClass : ''}} flex items-center gap-x-3.5 py-2.5 px-2.5 text-black rounded-lg hover:bg-[#831b94] hover:text-white dark:hover:bg-[#831b94] dark:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-plus" viewBox="0 0 16 16">
                            <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2zm3.708 6.208L1 11.105V5.383zM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2z"/>
                            <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5"/>
                        </svg>
                        <span class="sidebar-link-label">Request Approvals</span>
                    </a>
                </li>
            @endif
            @if(userCan('employee.view'))
                @php $isActive = request()->is('employees', 'employees/*','designations', 'designations/*') ? 'true' : 'false'; @endphp
                <li class="hs-accordion {{$isActive =='true' ? 'active' : ''}}" id="staff-accordion">
                    <button type="button" class="{{$isActive=='true' ? $ativeClass : ''}} hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2.5 px-2.5 hs-accordion-active:text-white hs-accordion-active:font-semibold hs-accordion-active:bg-[#831b94]  text-black rounded-lg hover:bg-[#831b94]  dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:hs-accordion-active:text-white dark:focus:outline-none ">
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="10" r="3"></circle>
                            <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"></path>
                        </svg>
                        <span class="sidebar-link-label">Employee</span>

                        <svg class="sidebar-link-label-indicator hidden hs-accordion-active:block ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="sidebar-link-label-indicator block hs-accordion-active:hidden ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div id="staff-accordion-child" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden" {{$isActive =='true' ? 'style=display:block;' : ''}}>
                        <ul class="flex flex-col gap-1 pt-2 ml-3 ps-2">
                            @if(userCan('employee.view'))
                                @php $isSubActive = request()->is('employees', 'employees/view/*') ? 'true' : 'false'; @endphp
                                <li>
                                    <a href="{{route('employees.index')}}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-3.5 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="font-semibold text-base"><i class="ti ti-point"></i></span>Employee List
                                    </a>
                                </li>
                            @endif
                            @if(userCan('employee.view'))
                                @php $isSubActive = request()->is('designations', 'designations/*') ? 'true' : 'false'; @endphp
                                <li>
                                    <a href="{{route('designation.index')}}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-3.5 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="font-semibold text-base"><i class="ti ti-point"></i></span>Designations
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif


            @if(in_array(getUserRole(), ['super-admin', 'hr']))
                @php $isActive = request()->is('departments', 'departments/*') ? 'true' : 'false'; @endphp
                <li class="hs-accordion {{$isActive =='true' ? 'active' : ''}}" id="staff-accordion">
                    <button type="button" class="{{$isActive=='true' ? $ativeClass : ''}} hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2.5 px-2.5 hs-accordion-active:text-white hs-accordion-active:font-semibold hs-accordion-active:bg-[#831b94]  text-black rounded-lg hover:bg-[#831b94]  dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:hs-accordion-active:text-white dark:focus:outline-none ">
                        <i class="fa-solid fa-building-user"></i>
                        <span class="sidebar-link-label">Departments</span>
                        <svg class="sidebar-link-label-indicator hidden hs-accordion-active:block ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="sidebar-link-label-indicator block hs-accordion-active:hidden ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div id="staff-accordion-child" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden" {{$isActive =='true' ? 'style=display:block;' : ''}}>
                        <ul class="flex flex-col gap-1 pt-2 ml-3 ps-2">
                            @if(userCan('employee.view'))
                                @php $isSubActive = request()->is('departments', 'departments/*') ? 'true' : 'false'; @endphp
                                <li>
                                    <a href="{{ route('department.index') }}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-3.5 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="font-semibold text-base"><i class="ti ti-point"></i></span>Department List
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(isRoleIn(['super-admin', 'hr']))
                @if(userCan('leave.view'))
                    @php $isActive = request()->is('leave', 'leave/*') ? 'true' : 'false'; @endphp
                    <li class="hs-accordion {{$isActive=='true' ? 'active' : ''}}" id="staff-accordion">
                        <button type="button" class="{{$isActive=='true' ? $ativeClass : ''}} hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2.5 px-2.5 hs-accordion-active:text-white hs-accordion-active:font-semibold hs-accordion-active:bg-[#831b94]  text-black rounded-lg hover:bg-[#831b94]  dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:hs-accordion-active:text-white dark:focus:outline-none truncate">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                                <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1z" />
                                <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2m0 1a3 3 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3 3 0 0 1 8 3" />
                            </svg>
                            <span class="sidebar-link-label">Leave Management</span>
                            <svg class="sidebar-link-label-indicator hidden hs-accordion-active:block ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>

                            <svg class="sidebar-link-label-indicator block hs-accordion-active:hidden ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div id="staff-accordion-child" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden" {{$isActive =='true' ? 'style=display:block;' : ''}}>
                            <ul class="flex flex-col gap-1 pt-2 ml-3">
                                @if(userCan('leave.view'))
                                    @if(in_array(getUserRole(), ['super-admin', 'hr', 'department-head', 'team-lead']))
                                        @php $isSubActive = request()->is('leave') && !request()->has('a') ? 'true' : 'false'; @endphp
                                        <li>
                                            <a href="{{route('leave.index')}}" class="{{ $isSubActive == 'true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-1.5 px-2 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                                <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span> Leave List
                                            </a>
                                        </li>
                                    @endif
                                @endif
                                @if(in_array(getUserRole(), ['super-admin', 'hr']))
                                    @php $isSubActive = request()->is('leave/holiday', 'leave/holiday/*') ? 'true' : 'false'; @endphp
                                    <li>
                                        <a  href="{{ route('holiday.index') }}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-1.5 px-2 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none">
                                            <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Holidays
                                        </a>
                                    </li>
                                @endif
                                @if(in_array(getUserRole(), ['super-admin', 'hr']))
                                    @php $isSubActive = request()->is('leave/weekend', 'leave/weekend/*') ? 'true' : 'false'; @endphp
                                    <li>
                                        <a  href="{{ route('weekend.index') }}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-1.5 px-2 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none">
                                            <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Weekends
                                        </a>
                                    </li>
                                @endif
                                @if(in_array(getUserRole(), ['super-admin', 'hr']))
                                    @php $isSubActive = request()->is('leave/leave-type', 'leave/leave-type/*') ? 'true' : 'false'; @endphp
                                    <li>
                                        <a  href="{{ route('leave-type.index') }}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-1.5 px-2 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none">
                                            <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Leave Types
                                        </a>
                                    </li>
                                @endif
                                @if(userCan('leave.view'))
                                    @php $isSubActive = request()->is('leave') && request()->has('a') && request('a')=='my-leave-list' && request('req')!='true' ? 'true' : 'false'; @endphp
                                    {{--<li>
                                        <a href="{{route('leave.index', ['a'=>'my-leave-list'])}}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-1.5 px-2 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none">
                                            <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>  My Leave List
                                        </a>
                                    </li>
                                        @php $isSubActive = request()->is('leave') && request()->has('a') && request('a')=='my-leave-list' && request('req')=='true' ? 'true' : 'false'; @endphp
                                    <li>
                                        <a href="{{route('leave.index', ['a'=>'my-leave-list', 'req'=>'true'])}}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-1.5 px-2 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none">
                                            <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>  Request a leave
                                        </a>
                                    </li>--}}
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif
            @endif

            @if(userCan('attendance.view') || userCan('duty-slot.view') || userCan('duty-slot-rules.view'))
                @php $isActive = request()->is('attendance', 'attendance/*','duty-slots','duty-slots/*','duty-slot-rules','duty-slot-rules/*') ? 'true':'false'; @endphp
                <li class="hs-accordion {{$isActive=='true' ? 'active' : ''}}" id="staff-accordion">
                    <button type="button" class="{{$isActive=='true' ? $ativeClass : ''}} hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2.5 px-2.5 hs-accordion-active:text-white hs-accordion-active:font-semibold hs-accordion-active:bg-[#831b94]  text-black rounded-lg hover:bg-[#831b94]  dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:hs-accordion-active:text-white dark:focus:outline-none ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-alarm" viewBox="0 0 16 16">
                            <path d="M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9z" />
                            <path d="M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5 0 0 0 0-1zm1.038 3.018a6 6 0 0 1 .924 0 6 6 0 1 1-.924 0M0 3.5c0 .753.333 1.429.86 1.887A8.04 8.04 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5M13.5 1c-.753 0-1.429.333-1.887.86a8.04 8.04 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1" />
                        </svg>
                        <span class="sidebar-link-label">Attendance</span>
                        <svg class="sidebar-link-label-indicator hidden hs-accordion-active:block ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="sidebar-link-label-indicator block hs-accordion-active:hidden ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div id="staff-accordion-child" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden" {{$isActive =='true' ? 'style=display:block;' : ''}}>
                        <ul class="flex flex-col gap-1 pt-2 ml-3">
                            @if(userCan('attendance.view'))
                                @php $isSubActive = request()->is('attendance', 'attendance/*') ? 'true' : 'false'; @endphp
                                <li>
                                    <a href="{{route('attendance.index')}}" class="{{ $isSubActive == 'true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-1.5 px-2 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none">
                                        <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Attendance List
                                    </a>
                                </li>
                            @endif
                            @if(userCan('duty-slot.view'))
                                @php $isSubActive = request()->is('duty-slots','duty-slots/*') ? 'true' : 'false'; @endphp
                                <li>
                                    <a href="{{route('dutySlots.index')}}" class="{{ $isSubActive == 'true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Duty Slots
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if(userCan('reports.view'))
                @php $isActive = request()->is('reports', 'reports/*') ? 'true' : 'false'; @endphp
                <li class="hs-accordion {{$isActive =='true' ? 'active' : ''}}" id="staff-accordion">
                    <button type="button" class="{{$isActive=='true' ? $ativeClass : ''}} hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2.5 px-2.5 hs-accordion-active:text-white hs-accordion-active:font-semibold hs-accordion-active:bg-[#831b94]  text-black rounded-lg hover:bg-[#831b94]  dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:hs-accordion-active:text-white dark:focus:outline-none ">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-report"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" /><path d="M18 14v4h4" /><path d="M18 11v-4a2 2 0 0 0 -2 -2h-2" /><path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M8 11h4" /><path d="M8 15h3" /></svg>
                        <span class="sidebar-link-label">Reports</span>

                        <svg class="sidebar-link-label-indicator hidden hs-accordion-active:block ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="sidebar-link-label-indicator block hs-accordion-active:hidden ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div id="staff-accordion-child" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden" {{$isActive =='true' ? 'style=display:block;' : ''}}>
                        <ul class="flex flex-col gap-1 pt-2 ml-3">
                            @if(userCan('reports.view'))
                                @php $isSubActive = request()->is('reports', 'reports/*') && request('a')=='attendance-report' ? 'true' : 'false'; @endphp
                                <li>
                                    <a href="{{route('reports.index', ['a'=>'attendance-report'])}}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Attendance Reports
                                    </a>
                                </li>
                            @endif
                            @if(userCan('reports.view'))
                                @php $isSubActive = request()->is('reports') && request('a')=='leave-report' ? 'true' : 'false'; @endphp
                                <li>
                                    <a href="{{route('reports.index', ['a'=>'leave-report'])}}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Leave Reports
                                    </a>
                                </li>
                            @endif
                            @if(userCan('reports.view'))
                                @php $isSubActive = request()->is('reports') && request('a')=='employee-report' ? 'true' : 'false'; @endphp
                                <li>
                                    <a href="{{route('reports.index', ['a'=>'employee-report'])}}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Employee Reports
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>
            @endif
            {{--User--}}
            @if(userCan('role.view'))
                @php $isActive = request()->is('users', 'users/*', 'roles', 'roles/*' ) ? 'true' : 'false'; @endphp
                <li class="hs-accordion {{$isActive =='true' ? 'active' : ''}}" id="staff-accordion">
                    <button type="button" class="{{$isActive=='true' ? $ativeClass : ''}} hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2.5 px-2.5 hs-accordion-active:text-white hs-accordion-active:font-semibold hs-accordion-active:bg-[#831b94]  text-black rounded-lg hover:bg-[#831b94]  dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:hs-accordion-active:text-white dark:focus:outline-none ">
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        <span class="sidebar-link-label">User Management</span>

                        <svg class="sidebar-link-label-indicator hidden hs-accordion-active:block ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="sidebar-link-label-indicator block hs-accordion-active:hidden ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div id="staff-accordion-child" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden" {{$isActive =='true' ? 'style=display:block;' : ''}}>
                        <ul class="flex flex-col gap-1 pt-2 ml-3">
                            @if(userCan('user.view'))
                                @php $isSubActive = request()->is('users', 'users/*') ? 'true' : 'false'; @endphp
                                <li>
                                    <a href="{{route('users.index')}}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Users
                                    </a>
                                </li>
                            @endif
                            @if(userCan('role.view'))
                                @php $isSubActive = request()->is('roles') ? 'true' : 'false'; @endphp
                                <li>
                                    <a href="{{route('roles.index')}}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Role
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>
            @endif
            {{--@if(userCan('team.view'))
                @php $isActive = request()->is('team', 'team/*') ? 'true' : 'false'; @endphp
                <li class="hs-accordion {{$isActive =='true' ? 'active' : ''}}" id="staff-accordion">
                    <button type="button" class="{{$isActive=='true' ? $ativeClass : ''}} hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2.5 px-2.5 hs-accordion-active:text-white hs-accordion-active:font-semibold hs-accordion-active:bg-[#831b94]  text-black rounded-lg hover:bg-[#831b94]  dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:hs-accordion-active:text-white dark:focus:outline-none ">
                        <i class="fa-solid fa-people-group"></i>
                        <span class="sidebar-link-label">Team Management</span>

                        <svg class="sidebar-link-label-indicator hidden hs-accordion-active:block ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="sidebar-link-label-indicator block hs-accordion-active:hidden ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div id="staff-accordion-child" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden" {{$isActive =='true' ? 'style=display:block;' : ''}}>
                        <ul class="flex flex-col gap-1 pt-2 ml-3 ps-2">
                            @if(userCan('team.view'))
                                @php $isSubActive = request()->is('team', 'team/*') ? 'true' : 'false'; @endphp
                                <li>
                                    <a href="{{route('team.index')}}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-3.5 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="font-semibold text-base"><i class="ti ti-point"></i></span>Team List
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif--}}
            {{-- notice management --}}
            @if(userCan('notice.view'))
                @php $isActive = request()->is('notice', 'notice/*') ? 'true' : 'false'; @endphp
                <li class="hs-accordion {{$isActive =='true' ? 'active' : ''}}" id="staff-accordion">
                    <button type="button" class="{{$isActive=='true' ? $ativeClass : ''}} hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2.5 px-2.5 hs-accordion-active:text-white hs-accordion-active:font-semibold hs-accordion-active:bg-[#831b94]  text-black rounded-lg hover:bg-[#831b94]  dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:hs-accordion-active:text-white dark:focus:outline-none ">
                        <i class="fa-solid fa-chalkboard"></i>
                        <span class="sidebar-link-label">Notice Board</span>
                        <svg class="sidebar-link-label-indicator hidden hs-accordion-active:block ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="sidebar-link-label-indicator block hs-accordion-active:hidden ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div id="staff-accordion-child" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden" {{$isActive =='true' ? 'style=display:block;' : ''}}>
                        <ul class="flex flex-col gap-1 pt-2 ml-3">
                            @if(userCan('notice.view'))
                                @php $isSubActive = request()->is('notice', 'notice/*') ? 'true' : 'false'; @endphp
                                <li>
                                    <a href="{{route('notice.index')}}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-3.5 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="font-semibold text-base"><i class="ti ti-point"></i></span>Notice
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            {{-- End Notice Management --}}
            @if(in_array(getUserRole(), ['super-admin', 'hr']))
            @php $isActive = request()->is('settings', 'settings/*') ? 'true' : 'false'; @endphp
            <li>
                <a class="{{$isActive=='true' ? $ativeClass : ''}} flex items-center gap-x-3.5 py-2.5 px-2.5 text-black rounded-lg hover:bg-[#831b94] hover:text-white dark:hover:bg-[#831b94] dark:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" {{-- href="{{route('settings.index')}}" --}} href="{{route('settings.fingerprintMachine.index')}}">
                    <i class="fa-solid fa-gear"></i>
                    <span class="sidebar-link-label">Settings</span>
                </a>
            </li>
            @endif
            {{-- @if(in_array(getUserRole(), ['super-admin', 'hr']))
                @php $isActive = request()->is('settings/fingerprint', 'settings/fingerprint/*' , 'settings/departments', 'settings/departments/*', 'settings/attendance-reporting', 'settings/attendance-reporting/*', 'settings/duty-slot-rules','settings/duty-slot-rules/*') ? 'true' : 'false'; @endphp
                <li class="hs-accordion {{$isActive=='true' ? 'active' : ''}}" id="setting-accordion">
                    <button type="button" class="{{$isActive=='true' ? $ativeClass : ''}} hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2.5 px-2.5 hs-accordion-active:text-white hs-accordion-active:font-semibold hs-accordion-active:bg-[#831b94]  text-black rounded-lg hover:bg-[#831b94]  dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:hs-accordion-active:text-white dark:focus:outline-none ">
                        <i class="fa-solid fa-gear"></i>
                        Settings
                        <svg class="hidden hs-accordion-active:block ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="block hs-accordion-active:hidden ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div id="setting-accordion-child" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden" {{$isActive =='true' ? 'style=display:block;' : ''}}>
                        <ul class="flex flex-col gap-1 pt-2 ml-3">
                            @if(in_array(getUserRole(), ['super-admin', 'hr']))
                                @php $isSubActive = request()->is('settings/fingerprint', 'settings/fingerprint/*') ? 'true' : 'false'; @endphp
                                <li>
                                    <a class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none truncate" href="{{ route('settings.fingerprintMachine.index') }}">
                                        <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Fingerprint Machine
                                    </a>
                                </li>
                            @endif
                            @if(in_array(getUserRole(), ['super-admin', 'hr']))
                                @php $isSubActive = request()->is('settings/departments', 'settings/departments/*') ? 'true' : 'false'; @endphp
                                <li>
                                    <a  href="{{ route('settings.department.index') }}" class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Departments
                                    </a>
                                </li>
                            @endif
                            @if(userCan('duty-slot-rules.view'))
                                @php $isSubActive = request()->is('settings/duty-slot-rules','settings/duty-slot-rules/*') ? 'true' : 'false';@endphp
                                <li>
                                    <a href="{{route('dutySlotRules.index')}}" class="{{ $isSubActive == 'true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-2 px-2.5 text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Duty Slot Rules
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif --}}

        </ul>
    </nav>
</div>
