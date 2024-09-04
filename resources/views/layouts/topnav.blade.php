@php
    $lastSyncDate=date('d M Y, h:i a', strtotime($lastSync)) ?? '';
@endphp
<header
    class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b text-sm py-1.5 sm:py-2 lg:ps-64 dark:bg-neutral-900 dark:border-neutral-700 dark:hs-overlay-backdrop-open:bg-neutral-900/90 print:hidden" id="topnav">

    <nav class="flex items-center w-full px-4 mx-auto basis-full sm:px-6 md:px-8 relative" aria-label="Global">
        <button id="toggleButton" class="hidden md:absolute -left-[1px] top-[3px] hover:bg-[#4d2454] hover:text-white bg-[#ae5bbc] rounded-r-md !w-7 !h-7 px-[15px] py-4 z-100 md:flex navbar-toggler navbar-toggler md:justify-center md:items-center md:self-center" type="button" data-value='0'>
            <i class="ti ti-chevrons-left text-lg leading-none font-bold rotate-0"></i>
        </button>
        <div class="w-full flex items-center gap-x-2 pl-1">
            <h3 class="text-xl font-semibold text-[#80208d]">@yield('pageTitle')</h3>
        </div>

        <div class="flex items-center justify-end w-full ms-auto sm:justify-end sm:gap-x-3 sm:order-3">
            {{--<div class="hidden w-2/5 sm:block">
                <label for="icon" class="sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 z-20 flex items-center pointer-events-none start-0 ps-4">
                        <svg
                            class="flex-shrink-0 text-gray-400 size-4"
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </div>
                    <input
                        id="icon"
                        name="icon"
                        class="block w-full px-4 py-2 text-sm border border-gray-200 rounded-lg ps-11 bg:gray-100 focus:border-pink-500 focus:ring-pink-500 dark:border-transparent dark:text-neutral-400 dark:placeholder:text-neutral-400 dark:bg-neutral-800"
                        placeholder="Search projects, files or people" />
                </div>
            </div>--}}

            <div class="flex flex-row items-center justify-end gap-2">
                @if(userCan('attendance.sync'))
                    <div class="inline-block tooltip tooltip-bottom md:tooltip-left"  data-tip="Last Sync:{{$lastSyncDate}}">
                        <button
                            type="button"
                            onclick="attendanceSyncModal.showModal()"
                            class="flex items-center gap-2 px-2 py-1 rounded text-[#831b94] bg-red-50 font-semibold text-xs md:text-sm truncate hover:text-white hover:bg-[#831b94] duration-200">
                            <i class="ti ti-refresh text-xs md:text-sm"></i> Sync now
                        </button>
                    </div>
                @endif
                <button
                    type="button"
                    class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-[#831b94] hover:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    <svg
                        class="flex-shrink-0 size-4"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                    </svg>
                </button>
                {{--<button
                    type="button"
                    class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-[#831b94] hover:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                    data-hs-offcanvas="#hs-offcanvas-right">
                    <svg
                        class="flex-shrink-0 size-4"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                </button>--}}
                <div class="hs-dropdown">
                    <button
                        type="button"
                        class=" hs-dropdown-toggle hs-dark-mode group w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-[#831b94] hover:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <svg
                            class="block hs-dark-mode-active:hidden size-4"
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                        </svg>
                        <svg
                            class="hidden hs-dark-mode-active:block size-4"
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"></circle>
                            <path d="M12 2v2"></path>
                            <path d="M12 20v2"></path>
                            <path d="m4.93 4.93 1.41 1.41"></path>
                            <path d="m17.66 17.66 1.41 1.41"></path>
                            <path d="M2 12h2"></path>
                            <path d="M20 12h2"></path>
                            <path d="m6.34 17.66-1.41 1.41"></path>
                            <path d="m19.07 4.93-1.41 1.41"></path>
                        </svg>
                    </button>

                    <div
                        id="selectThemeDropdown"
                        class="hs-dropdown-menu hs-dropdown-open:opacity-100 mt-2 hidden z-10 transition-[margin,opacity] opacity-0 duration-300 mb-2 origin-bottom-left bg-white rounded-lg p-2 shadow-[0_10px_40px_10px_rgba(0,0,0,0.08)] dark:bg-neutral-900 dark:shadow-[0_10px_40px_10px_rgba(0,0,0,0.2)]">
                        <button
                            type="button"
                            class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-[#831b94] dark:text-neutral-300 dark:hover:bg-[#831b94] hover:text-white dark:hover:text-gray-300"
                            data-hs-theme-click-value="default">
                            Light
                        </button>
                        <button
                            type="button"
                            class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-[#831b94] dark:text-neutral-300 dark:hover:bg-[#831b94] hover:text-white dark:hover:text-gray-300"
                            data-hs-theme-click-value="dark">
                            Dark
                        </button>
                        <button
                            type="button"
                            class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-[#831b94] dark:text-neutral-300 dark:hover:bg-[#831b94] hover:text-white dark:hover:text-gray-300"
                            data-hs-theme-click-value="auto">
                            Auto (System)
                        </button>
                    </div>
                </div>
                <!-- Navigation Toggle -->
                <button
                id="navbarToggle"
                type="button"
                class="inline-flex items-center justify-center p-2 text-gray-800 bg-white border border-gray-200 rounded-full shadow-sm lg:hidden gap-x-2 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-transparent dark:border-white dark:text-white dark:hover:bg-white/10"
                data-hs-overlay="#sidebar"
                aria-controls="sidebar"
                aria-label="Toggle navigation">
                {{-- <span class="sr-only">Toggle Navigation</span> --}}
                    <svg
                        class="flex-shrink-0 size-4"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="3" x2="21" y1="6" y2="6" />
                        <line x1="3" x2="21" y1="12" y2="12" />
                        <line x1="3" x2="21" y1="18" y2="18" />
                    </svg>
                </button>
                <!-- End Navigation Toggle -->
                <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                    @php
                        $user=auth()->user();
                        if($user->employee){
                            $defaultProfileImage=employeeDefaultProfileImage($user->employee->gender);
                            $profile_img=employeeProfileImage($user->employee->emp_id, $user->employee->profile_photo);
                        }else{
                            $defaultProfileImage=employeeDefaultProfileImage("Male");
                            $profile_img=$user->user_image;
                        }
                    @endphp
                    <button
                        id="hs-dropdown-with-header"
                        type="button"
                        class="w-[2.375rem] h-[2.375rem] overflow-hidden rounded-full shadow border hover:border-transparent hover:shadow-lg hover:bg-red-500 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-[#831b94] hover:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 transition-all duration-200">
                        <img
                            class="w-[1.7rem] h-[1.7rem] mx-auto object-cover rounded-full ring-2 ring-white dark:ring-gray-800"
                            src="{{$profile_img}}"
                            onerror="this.onerror=null;this.src='{{$defaultProfileImage}}';"
                            alt="" />
                    </button>

                    <div
                        class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white  rounded-lg p-2  shadow-[0_10px_40px_10px_rgba(0,0,0,0.08)] dark:bg-neutral-900 dark:shadow-[0_10px_40px_10px_rgba(0,0,0,0.2)]"
                        aria-labelledby="hs-dropdown-with-header">
                        <div class="px-5 py-3 -m-2 rounded-t-lg bg-red-700 text-white dark:bg-[#831b94]">
                            <p class="text-sm text-white dark:text-neutral-100 font-medium">Signed in as</p>
                            <p class="text-sm font-bold text-white dark:text-neutral-100 flex items-center justify-start gap-x-2"><span class=""><i class="fa-regular fa-user"></i> </span><span class="">{{auth()->user()->name ?? ''}}</span></p>
                            <p class="text-sm font-bold text-white dark:text-neutral-100 flex items-center justify-start gap-x-2"><span class=""><i class="fa-regular fa-envelope"></i> </span><span class="">{{auth()->user()->email ?? ''}}</span></p>
                            <p class="text-sm font-bold text-white dark:text-neutral-100 flex items-center justify-start gap-x-2"><span class=""><i class="fa-solid fa-user-tie"></i></span><span class="">{{getUserRole() ? getUserRole('name') : ''}}</span></p>
                            {{-- <p class="text-sm font-medium text-white dark:text-neutral-100">{{getUserRole() ? ucfirst(getUserRole()) : ''}}</p> --}}
                        </div>

                        <div class="py-2 mt-2 first:pt-0 last:pb-0">
                            <a
                                class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-[#831b94] focus:ring-2 focus:ring-blue-500 dark:text-neutral-300 dark:hover:bg-[#831b94] hover:text-white dark:hover:text-gray-300"
                                href="#">
                                <svg
                                    class="flex-shrink-0 size-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                                </svg>
                                Notifications
                            </a>

                           @if(auth()->user()->employee)
                            <a
                                class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-[#831b94] focus:ring-2 focus:ring-blue-500 dark:text-neutral-300 dark:hover:bg-[#831b94] hover:text-white dark:hover:text-gray-300"
                                href="{{ route('my-profile.index') }}">
                                <svg
                                    class="flex-shrink-0 size-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                                My Profile
                            </a>
                            @endif

                            <form action="{{ route('logout') }}" method="post">
                @csrf
       <!-- <button type="submit">Logout</button> -->

        <button type="submit"
                                class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-[#831b94] focus:ring-2 focus:ring-blue-500 dark:text-neutral-300 dark:hover:bg-[#831b94] hover:text-white dark:hover:text-gray-300"
                                href="#">
                                <svg
                                    class="flex-shrink-0 size-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                                logout
                            </button>
                </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
