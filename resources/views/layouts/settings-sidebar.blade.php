@php
$ativeClass='active text-white font-semibold bg-[#831b94] dark:text-white active:text-white active:font-semibold active:bg-[#831b94] dark:active:text-white';
$ativeSubClass='text-[15px] text-[#831b94] font-bold dark:text-[#831b94] active:text-[#831b94] active:font-bold active:bg-[#831b94] dark:active:text-[#831b94] hs-accordion-active:text-[#831b94] hs-accordion-active:font-bold dark:hs-accordion-active:text-[#831b94]';
/* $ativeSubClass=' text-white font-semibold bg-[#831b94] dark:text-white active:text-white active:font-semibold active:bg-[#831b94] dark:active:text-white hs-accordion-active:text-white hs-accordion-active:font-semibold hs-accordion-active:bg-[#831b94] dark:hs-accordion-active:text-white'; */
@endphp
<div id="application-sidebar" class="hs-overlay hs-overlay-open:translate-x-50 -translate-x-50 transition-all duration-300 transform hidden px-3 fixed top-14 start-50 z-[60] py-4 w-60 bg-white border-e border-gray-200 overflow-y-auto lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-slate-700 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500 dark:bg-neutral-800 dark:border-neutral-700 dark:hs-overlay-backdrop-open:bg-neutral-900/90 print:hidden">
    <nav class="flex flex-col flex-wrap w-full  hs-accordion-group" data-hs-accordion-always-open>

        <ul class="space-y-2.5 font-semibold">
            <!-- List of Sidebar items -->
            {{-- @php $isActive = request()->is('/') ? 'true' : 'false'; @endphp
            <li>
                <a class="{{$isActive=='true' ? $ativeClass : ''}} flex items-center gap-x-3.5 py-2.5 px-2.5 text-sm text-black rounded-lg hover:bg-[#831b94] hover:text-white dark:hover:bg-[#831b94] dark:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="{{route('dashboard')}}">
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    Dashboard
                </a>
            </li> --}}
            {{-- End Notice Management --}}
            @if(in_array(getUserRole(), ['super-admin', 'hr']))
                @php $isActive = request()->is('settings/fingerprint', 'settings/fingerprint/*','settings/attendance-reporting', 'settings/attendance-reporting/*',) ? 'true' : 'false'; @endphp
                <li class="hs-accordion {{$isActive=='true' ? 'active' : ''}}" id="setting-accordion">
                    <button type="button" class="{{$isActive=='true' ? $ativeClass : ''}} hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2.5 px-2.5 hs-accordion-active:text-white hs-accordion-active:font-semibold hs-accordion-active:bg-[#831b94]  text-sm text-black rounded-lg hover:bg-[#831b94]  dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:hs-accordion-active:text-white dark:focus:outline-none ">
                        <span class="text-lg flex self-center items-center"><i class="ti ti-password-fingerprint"></i></span>
                        Machine Control
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
                                    <a class="{{$isSubActive=='true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-2 px-2.5 text-sm text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none truncate" href="{{ route('settings.fingerprintMachine.index') }}">
                                        <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Fingerprint Machine
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if(in_array(getUserRole(), ['super-admin', 'hr']))
                {{-- Setting implementation  --}}
                @php $isActive = request()->is('settings/duty-slot-rules','settings/duty-slot-rules/*') ? 'true' : 'false'; @endphp
                <li class="hs-accordion {{$isActive=='true' ? 'active' : ''}}" id="setting-accordion">
                    <button type="button" class="{{$isActive=='true' ? $ativeClass : ''}} hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2.5 px-2.5 hs-accordion-active:text-white hs-accordion-active:font-semibold hs-accordion-active:bg-[#831b94]  text-sm text-black rounded-lg hover:bg-[#831b94]  dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:hs-accordion-active:text-white dark:focus:outline-none ">
                        <span class="text-lg flex self-center items-center"><i class="ti ti-browser-check"></i></span>
                        Rules
                        <svg class="hidden hs-accordion-active:block ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="block hs-accordion-active:hidden ms-auto size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div id="setting-accordion-child" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden" {{$isActive =='true' ? 'style=display:block;' : ''}}>
                        <ul class="flex flex-col gap-1 pt-2 ml-3">
                            @if(userCan('duty-slot-rules.view'))
                                @php $isSubActive = request()->is('settings/duty-slot-rules','settings/duty-slot-rules/*') ? 'true' : 'false';@endphp
                                <li>
                                    <a href="{{route('dutySlotRules.index')}}" class="{{ $isSubActive == 'true' ? $ativeSubClass : ''}} flex items-center gap-x-1 py-2 px-2.5 text-sm text-black rounded-lg hover:bg-[#831b94] dark:hover:bg-[#831b94] hover:text-white dark:text-white dark:hover:text-white dark:focus:outline-none ">
                                        <span class="text-base {{$isSubActive == 'true' ? 'font-bold' : 'font-semibold'}}"><i class="ti ti-point"></i></span>Duty Slot Rules
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

        </ul>
    </nav>
</div>
