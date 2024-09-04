@extends('layouts.app')
@section('title', 'Dashboard')
@section('pageTitle', 'Dashboard')
@section('content')
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #FFF;
        }
    </style>
    @php
        use App\Models\Employee;
        use App\Models\Attendance;
        use Illuminate\Support\Facades\DB;
        use App\Http\Controllers\AttendanceController;
        $date = date('Y-m-d');

    @endphp
    @section('additionalButton')

    @endsection

    @php
        $manageableEmployeeIds=getManageableEmployeesIDs();
        $manageableEmployeeIdsCount=count($manageableEmployeeIds)
    @endphp

    @if(isRoleIn(['super-admin','hr']))
        <x-containers.container-box>
            <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-5 sm:gap-6">
                @if(isRoleIn(['super-admin','hr']))
                    <div>
                        <select id="selectOrganization"  name="select-filter" class="inputFieldCompact select2">
                            <option value="">Organization (All)</option>
                            @foreach($organizations as $item)
                                <option value="{{$item->id}}" {{request()->has('organization') ? request('organization') : ''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    @php $types=['Daily', 'Monthly'];  @endphp
                    <select id="selectType"  name="select-filter" class="inputFieldCompact select2">
                        @foreach($types as $item)
                            <option value="{{$item}}" {{$item=='Daily'? 'selected' : ''}}>{{$item}}</option>
                        @endforeach
                    </select>
                </div>
                <div id="monthField" class="max-w-sm space-y-3">
                    <input type="month" name="month" value="{{date('Y-m')}}" class="inputFieldCompact !bg-white" placeholder="Select a Month...">
                </div>
                <div id="dateField" class="max-w-sm space-y-3">
                    <input type="date" name="date" value="{{date('Y-m-d')}}" class="inputFieldCompact !bg-white" placeholder="Select a Date...">
                </div>
                <div class="">
                    <div class="inline-block tooltip"  data-tip="Reset All Filters">
                        <button type="button" class="btn-red w-9 aspect-square flex items-center justify-center" id="resetvalues">
                            <i class="fas fa-undo text-center text-sm"></i>
                            <span
                                class="absolute z-10 invisible inline-block px-2 py-1 text-white transition-opacity bg-[#831b94] rounded-lg shadow-md opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible"
                                role="tooltip">
                            Reset Values
                        </span>
                        </button>
                    </div>
                </div>
            </div>
        </x-containers.container-box>
    @else
        @if(count($manageableEmployeeIds)>0)
            <h3 class="pb-1 font-semibold text-gray-800 text-md dark:text-white">Summary</h3>
            <div class="grid sm:grid-cols-2 md:grid-cols-5 gap-2">
                @php
                    $data=['title'=>'Employees', 'counting'=>"", 'icon'=>'<i class="ti ti-users-group"></i>', 'url'=>'#', 'id'=>'manageableEmployeeCount', 'loader'=>true]
                @endphp
                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>
                @php
                    $data=['title'=>'Pending requests', 'counting'=>"", 'icon'=>'<i class="ti ti-clock-minus"></i>',  'url'=>route('request-approvals.index'), 'id'=>'pendingRequestCount', 'loader'=>true];
                @endphp
                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>
                @php
                    $data=['title'=>'Today presents', 'counting'=>'', 'icon'=>'<i class="ti ti-calendar-user"></i>', 'url'=>'#', 'id'=>'todayPresentCount', 'loader'=>true];
                @endphp
                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>
                @php
                    $data=['title'=>'Today absents', 'counting'=>0, 'icon'=>'<i class="ti ti-user-exclamation"></i>', 'url'=>'#', 'id'=>'todayAbsentCount', 'loader'=>true];
                @endphp
                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>
                @php
                    $data=['title'=>'Today leave', 'counting'=>0, 'icon'=>'<i class="ti ti-user-share"></i>', 'url'=>'#', 'id'=>'todayLeaveCount', 'loader'=>true];
                @endphp
                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>

            </div>
            {{-- Reports --}}
            <div class="py-4 mx-auto mt-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    {{--Attendance Chart--}}
                    <div class="flex flex-col bg-white border shadow-sm rounded-xl max-h-[400px] dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                        <div
                            class="flex items-center justify-between px-2 py-2 border-b rounded-t-xl md:px-5 dark:border-neutral-700">
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">
                                Attendance Today
                            </h3>
                        </div>
                        <div id="attendanceToday">
                            <div class="py-20 flex items-center justify-center">
                                <span class="loading loading-dots loading-lg"></span>
                            </div>
                        </div>
                    </div>
                    {{--Not Checked IN Employee--}}
                    <div class="flex flex-col bg-white border shadow-sm rounded-xl max-h-[400px] dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                        <div
                            class="flex items-center justify-between px-2 py-2 border-b rounded-t-xl md:px-5 dark:border-neutral-700">
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">
                                Not Checked IN <span id="notCheckedInCount" class="text-blue-600"></span>
                                {{--({{$notCheckedInList->count()}})--}}
                            </h3>
                            <div class="flex items-center justify-center font-medium">
                                <div class="dropdown dropdown-left">
                                    <button type="button" tabindex="0" role="button" class="flex items-center justify-center text-sm font-semibold text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg size-7 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                        <svg class="flex-none text-gray-600 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                             height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="1" />
                                            <circle cx="12" cy="5" r="1" />
                                            <circle cx="12" cy="19" r="1" />
                                        </svg>
                                    </button>
                                    <ul tabindex="0" class="dropdown-content z-[1] menu px-0 shadow bg-base-100 rounded-md !w-44">
                                        <div class=" px-2">
                                            <button type="button"
                                                    onclick="fnExportReport('notCheckedInList','pdf', 'NotCheckedInList');"
                                                    class="editUserBtn w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer"
                                            >
                                                <i class="ti ti-file-type-pdf"></i>
                                                Download PDF
                                            </button>

                                            <button type="button"
                                                    onclick="fnExportReport('notCheckedInList','csv', 'NotCheckedInList');"
                                                    class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                                <i class="ti ti-file-type-csv"></i>
                                                Download CSV
                                            </button>
                                            <button type="button"
                                                    onclick="fnExportReport('notCheckedInList','xlsx', 'NotCheckedInList');"
                                                    class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                                <i class="ti ti-file-type-xls"></i>
                                                Download EXCEL
                                            </button>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="notCheckedInListManagable" class="text-xs">
                            <div class="py-20 flex items-center justify-center">
                                <span class="loading loading-dots loading-lg"></span>
                            </div>
                        </div>
                    </div>
                    {{--Late Employees--}}
                    <div class="flex flex-col bg-white border shadow-sm rounded-xl max-h-[400px] dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                        <div
                            class="flex items-center justify-between px-2 py-2 border-b rounded-t-xl md:px-5 dark:border-neutral-700">
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">
                                Late Employees <span id="lateEmployeeCount" class="text-[#831b94]"></span>
                            </h3>
                            <div class="flex items-center justify-center font-medium">
                                <div class="dropdown dropdown-left">
                                    <button type="button" tabindex="0" role="button" class="flex items-center justify-center text-sm font-semibold text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg size-7 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                        <svg class="flex-none text-gray-600 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                             height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="1" />
                                            <circle cx="12" cy="5" r="1" />
                                            <circle cx="12" cy="19" r="1" />
                                        </svg>
                                    </button>
                                    <ul tabindex="0" class="dropdown-content z-[1] menu px-0 shadow bg-base-100 rounded-md !w-44">
                                        <div class=" px-2">
                                            <button type="button"
                                                    onclick="fnExportReport('lateEmployeesListContainer','pdf' ,'LateEmployeesList');"
                                                    class="editUserBtn w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer"
                                            >
                                                <i class="ti ti-file-type-pdf"></i>
                                                Download PDF
                                            </button>

                                            <button type="button"
                                                    onclick="fnExportReport('lateEmployeesListContainer','csv' ,'LateEmployeesList');"
                                                    class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                                <i class="ti ti-file-type-csv"></i>
                                                Download CSV
                                            </button>
                                            <button type="button"
                                                    onclick="fnExportReport('lateEmployeesListContainer','xlsx' ,'LateEmployeesList');"
                                                    class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                                <i class="ti ti-file-type-xls"></i>
                                                Download EXCEL
                                            </button>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="lateEmployeeSpinner">
                            <div class="py-20 flex items-center justify-center">
                                <span class="loading loading-dots loading-lg"></span>
                            </div>
                        </div>
                        <div id="lateEmployeesListContainer">
                            <div class="flex flex-col" >
                                <div class="inline-block min-w-full overflow-hidden overflow-y-auto align-middle h-[20rem]">
                                    <table id="lateEmployeesList" class="min-w-full  divide-y divide-gray-200 dark:divide-neutral-700 !text-xs">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

    @endif



    @if (in_array(getUserRole(), ['super-admin','hr']))
        <div class="pt-4 mx-auto">
            <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-5 sm:gap-6">
                @php
                    $employeeCount=\App\Models\Employee::count();
                    $data=['title'=>'Total Employee', 'counting'=>"$employeeCount", 'icon'=>'<i class="ti ti-users-group"></i>', 'url'=>route('employees.index')]
                @endphp
                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>

                @php
                    $date=date('Y-m-d 00:00:00');
                    $data=['title'=>'On leave today', 'counting'=>count($todayLeave), 'icon'=>'<i class="ti ti-calendar-user"></i>', 'url'=>route('leave.index', ['status'=>2]), 'id'=>'todayLeaveCount',];
                @endphp
                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>
                @php
                    $leaveRequestCount=\App\Models\Leave::where('approval_status', 1)
                        ->where('start_date', '<=', $date)
                        ->where('end_date', '>=', $date)
                        ->count();
                    $data=['title'=>'Leave requests', 'counting'=>"$leaveRequestCount", 'icon'=>'<i class="ti ti-user-exclamation"></i>', 'url'=>route('leave.index', ['status'=>1])];
                @endphp
                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>
                @php
                    $data=['title'=>'Late Today', 'counting'=>0, 'icon'=>'<i class="ti ti-clock-minus"></i>', 'url'=>'#', 'id'=>'lateCountCard', 'loader'=>true];
                @endphp
                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>
                @php
                    $data=['title'=>'Early Leave Today', 'counting'=>0, 'icon'=>'<i class="ti ti-user-share"></i>', 'url'=>'#'];
                @endphp
                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>

            </div>
        </div>
        <div class="py-4 mx-auto mt-2">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                {{--Attendance Chart--}}
                <div class="flex flex-col bg-white border shadow-sm rounded-xl max-h-[400px] dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                    <div
                        class="flex items-center justify-between px-2 py-2 border-b rounded-t-xl md:px-5 dark:border-neutral-700">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">
                            Attendance Today
                        </h3>
                    </div>
                    <div id="attendanceToday">
                        <div class="py-20 flex items-center justify-center">
                            <span class="loading loading-dots loading-lg"></span>
                        </div>
                    </div>
                </div>
                {{--Not Checked IN Employee--}}
                <div class="flex flex-col bg-white border shadow-sm rounded-xl max-h-[400px] dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                    <div
                        class="flex items-center justify-between px-2 py-2 border-b rounded-t-xl md:px-5 dark:border-neutral-700">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">
                            Not Checked IN <span id="notCheckedInCount" class="text-blue-600"></span>
                            {{--({{$notCheckedInList->count()}})--}}
                        </h3>
                        <div class="flex items-center justify-center font-medium">
                            <div class="dropdown dropdown-left">
                                <button type="button" tabindex="0" role="button" class="flex items-center justify-center text-sm font-semibold text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg size-7 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                    <svg class="flex-none text-gray-600 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                         height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="12" cy="5" r="1" />
                                        <circle cx="12" cy="19" r="1" />
                                    </svg>
                                </button>
                                <ul tabindex="0" class="dropdown-content z-[1] menu px-0 shadow bg-base-100 rounded-md !w-44">
                                    <div class=" px-2">
                                        <button type="button"
                                                onclick="fnExportReport('notCheckedInList','pdf', 'NotCheckedInList');"
                                                class="editUserBtn w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer"
                                        >
                                            <i class="ti ti-file-type-pdf"></i>
                                            Download PDF
                                        </button>

                                        <button type="button"
                                                onclick="fnExportReport('notCheckedInList','csv', 'NotCheckedInList');"
                                                class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                            <i class="ti ti-file-type-csv"></i>
                                            Download CSV
                                        </button>
                                        <button type="button"
                                                onclick="fnExportReport('notCheckedInList','xlsx', 'NotCheckedInList');"
                                                class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                            <i class="ti ti-file-type-xls"></i>
                                            Download EXCEL
                                        </button>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="notCheckedInList" class="text-xs">
                        <div class="py-20 flex items-center justify-center">
                            <span class="loading loading-dots loading-lg"></span>
                        </div>
                    </div>
                </div>
                {{--Late Employees--}}
                <div class="flex flex-col bg-white border shadow-sm rounded-xl max-h-[400px] dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                    <div
                        class="flex items-center justify-between px-2 py-2 border-b rounded-t-xl md:px-5 dark:border-neutral-700">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">
                            Late Employees <span id="lateEmployeeCount" class="text-[#831b94]"></span>
                        </h3>
                        <div class="flex items-center justify-center font-medium">
                            <div class="dropdown dropdown-left">
                                <button type="button" tabindex="0" role="button" class="flex items-center justify-center text-sm font-semibold text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg size-7 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                    <svg class="flex-none text-gray-600 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                         height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="12" cy="5" r="1" />
                                        <circle cx="12" cy="19" r="1" />
                                    </svg>
                                </button>
                                <ul tabindex="0" class="dropdown-content z-[1] menu px-0 shadow bg-base-100 rounded-md !w-44">
                                    <div class=" px-2">
                                        <button type="button"
                                                onclick="fnExportReport('lateEmployeesListContainer','pdf' ,'LateEmployeesList');"
                                                class="editUserBtn w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer"
                                        >
                                            <i class="ti ti-file-type-pdf"></i>
                                            Download PDF
                                        </button>

                                        <button type="button"
                                                onclick="fnExportReport('lateEmployeesListContainer','csv' ,'LateEmployeesList');"
                                                class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                            <i class="ti ti-file-type-csv"></i>
                                            Download CSV
                                        </button>
                                        <button type="button"
                                                onclick="fnExportReport('lateEmployeesListContainer','xlsx' ,'LateEmployeesList');"
                                                class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                            <i class="ti ti-file-type-xls"></i>
                                            Download EXCEL
                                        </button>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="lateEmployeeSpinner">
                        <div class="py-20 flex items-center justify-center">
                            <span class="loading loading-dots loading-lg"></span>
                        </div>
                    </div>
                    <div id="lateEmployeesListContainer">
                        <div class="flex flex-col" >
                            <div class="inline-block min-w-full overflow-hidden overflow-y-auto align-middle max-h-[20rem]">
                                <table id="lateEmployeesList" class="min-w-full  divide-y divide-gray-200 dark:divide-neutral-700 !text-xs"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endif
    @if (getUserRole() == 'employee')
        <div class="mb-6 mx-auto">
            <h3 class="pb-2 font-semibold text-gray-800 text-md dark:text-white">Leave Summary</h3>
            <div class="grid gap-3 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 sm:gap-6">
                @php
                    $employee=auth()->user()->employee;
                    $leaveBalances=$employee->leaveBalance(date('Y'));
                    $leaveAllowance=0;
                    $leaveTaken=0;
                    $leaveAvailable=0;
                    foreach ($leaveBalances as $item){
                        if(in_array($item['id'], [1,2,3])){
                            $leaveAllowance+=$item['allowance'];
                            $leaveTaken+=$item['taken'];
                            $leaveAvailable+=$item['remaining'];
                        }
                    }
                    $data=['title'=>'Total leave allowance', 'icon'=>'<i class="ti ti-calendar-user"></i>', 'counting'=>$leaveAllowance, 'url'=>'#']
                @endphp
                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>

                @php
                    $data=['title'=>'Total leave available', 'icon'=>'<i class="ti ti-calendar-month"></i>', 'counting'=>$leaveAvailable, 'url'=>'#']
                @endphp

                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>

                @php
                    $data=['title'=>'Total leave taken', 'icon'=>'<i class="ti ti-calendar-check"></i>', 'counting'=>$leaveTaken, 'url'=>'#']
                @endphp
                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>

                @php
                    $leavePending=$employee->allLeave(1)->count() ?? 0;
                    $data=['title'=>'Leave request pending', 'icon'=>'<i class="ti ti-calendar-exclamation"></i>', 'counting'=>$leavePending, 'url'=>'#']
                @endphp
                <x-cards.dashboard-card-square :data="$data"></x-cards.dashboard-card-square>
            </div>
        </div>

    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
        <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex items-center justify-between px-4 py-2.5 border-b rounded-t-xl md:px-5 dark:border-neutral-700">
                <h3 class="text-base font-bold text-gray-800 dark:text-white">
                    Holiday
                </h3>
                <a href="{{route('holiday.index')}}" class="submit-button-sm">See All</a>
            </div>
            <div class="overflow-hidden overflow-y-auto max-h-[24rem]">
                @if($holiday->count()>0)
                    <div class="grid grid-cols-1 gap-4 px-5 py-5">
                        @php $today = now(); @endphp
                        @foreach($holiday as $key=>$item)
                            @php
                                $startDate = \Carbon\Carbon::parse($item->start_date);
                                $bgColor = $startDate->lessThan($today) ? 'bg-[#831b94]' : 'bg-teal-600';
                                $borderColor = $startDate->lessThan($today) ? 'border-red-600' : 'border-teal-600';
                            @endphp
                            <div class="">
                                <div class="flex items-center gap-4">
                                    <div class="">
                                        <div class="rounded-md border overflow-hidden w-24 text-center {{$borderColor}}">
                                            <p class="{{$bgColor}} text-white font-medium py-0.5">{{date('d M', strtotime($item->start_date))}}</p>
                                            <p class="py-1">{{date('Y', strtotime($item->start_date))}}</p>
                                        </div>
                                    </div>
                                    <div class="">
                                        <p class="font-semibold">{{$item->name}}</p>
                                        <p class="text-sm text-gray-600">{{date('l', strtotime($item->start_date))}}</p>
                                        @if($item->start_date != $item->end_date)
                                            <div class="flex items-center gap-2">
                                                <p class="text-xs">{{date('d M Y', strtotime($item->start_date))}} to {{date('d M Y', strtotime($item->end_date))}}</p>
                                                <span class="py-0.5 px-1 rounded-md text-xs bg-gray-500 text-white">{{dateToDateCount($item->start_date, $item->end_date)}} days</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center py-32">No data found!</p>
                @endif
            </div>

        </div>

        <div class="col-span-1 md:col-span-2 flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex items-center justify-between px-4 py-2.5 border-b rounded-t-xl md:px-5 dark:border-neutral-700">
                <h3 class="text-base font-bold text-gray-800 dark:text-white">
                    Notice Board
                </h3>
                <a href="{{route('notice.index')}}" class="submit-button-sm">See All</a>
            </div>
            <div class="overflow-hidden overflow-y-auto max-h-[24rem]">
                @if($notices->count()>0)
                    <table class="items-center bg-transparent w-full border-collapse ">
                        <tbody>
                        @foreach($notices as $key=>$item)
                            <tr class="border-b truncate">
                                <td class="border-t-0 px-2 align-center border-l-0 border-r-0 whitespace-nowrap">
                                    @php
                                        $day=date('d', strtotime($item->notice_date));
                                        $month=date('M', strtotime($item->notice_date));
                                        $year=date('Y', strtotime($item->notice_date));
                                    @endphp
                                    <div class="text-center w-24">
                                        <p class="text-4xl text-[#831b94] font-semibold">{{$day}}</p>
                                        <span class="text-sm">{{$month}} {{$year}}</span>
                                    </div>
                                </td>
                                <td class="px-2 align-middle w-full">
                                    <p class="font-medium text-base">{{$item->notice_type}}</p>
                                    <div class="w-full overflow-hidden text-sm text-gray-600">
                                        <p class="w-96 truncate">{{$item->notice_description}}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                @else
                    <p class="text-center py-32">No data found!</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function (){
            $('#selectType').change(function() {
                if ($(this).val() === 'Monthly') {
                    $('#monthField').show();
                    $('#dateField').hide();
                } else {
                    $('#monthField').hide();
                    $('#dateField').show();
                }
            }).trigger('change'); // Trigger change event on page load

            $('#resetvalues').click(function() {
                $('#selectOrganization').val('').trigger('change');
                $('#selectType').val('Daily').trigger('change');

                var currentDate = new Date();
                var formattedDate = currentDate.toISOString().slice(0, 10);
                var formattedMonth = currentDate.toISOString().slice(0, 7);

                $('input[name="date"]').val(formattedDate);
                $('input[name="month"]').val(formattedMonth);

                console.log($('#selectOrganization').val(), $('#selectType').val(), $('input[name="date"]').val(), $('input[name="month"]').val());

                let organization = '';
                let type = '';
                let date = '';
                // dataTable.ajax.url(`{{ route('attendance.index') }}?organization=${organization}&type=${type}&date=${date}`).load();
            });
        })
    </script>
    <script>
        $(function () {
            var lateEmployeeData = '';
            @if(in_array(getUserRole(), ['super-admin','hr']))
            $.ajax(`${baseUrl}/?a=get-attendance-data`).then(function (res) {
                console.log(res)
                if (res.status === 1) {
                    $('#lateCountCard .counting').html(res.data.lateCount)
                    $('#lateEmployeeCount').html(`(${res.data.lateCount})`)
                    $('#notCheckedInCount').html(`(${res.data.notCheckedInCount})`)
                    $('#notCheckedInList').html(res.data.notCheckedInList)
                    getAttendanceStats(res.data)
                    getLateEmployeeData(res.data.lateEmployee);
                } else {

                }
            })
            @elseif (count($manageableEmployeeIds)>0)
            $.ajax(`${baseUrl}/?a=get-dashboard-basic-data`).then(function (res) {
                console.log(res.data.manageableEmployeesAttendanceReports.data)
                if (res.status === 1) {
                    $('#manageableEmployeeCount .counting').html(res.data.manageableEmployeeCount)
                    $('#pendingRequestCount .counting').html(res.data.pendingRequestCount)
                    $('#todayPresentCount .counting').html(res.data.todayPresentCount)
                    $('#todayAbsentCount .counting').html(res.data.todayAbsentCount)
                    $('#todayLeaveCount .counting').html(res.data.todayLeaveCount)
                    let todayData = res.data.manageableEmployeesAttendanceReports.data;
                    console.log("🚀 ~ res.data.manageableEmployeesAttendanceReports.:", res.data);
                    let notCheckedInData = [];
                    let lateEmployeeData = [];
                    let attendanceToday = res.data.manageableEmployeesAttendanceReports.globalSummary;
                    let total = attendanceToday.present + attendanceToday.absent;
                    let regularCount = total - (attendanceToday.absent + attendanceToday.late);
                    let lateCount = attendanceToday.late;
                    let notCheckedInCount = attendanceToday.absent;

                    let attendancetodayData = {
                        regularCount: regularCount,
                        lateCount: lateCount,
                        notCheckedInCount: notCheckedInCount,
                    };
                    console.log("🚀 ~ attendancetodayData:", attendancetodayData);

                    todayData.forEach((employee) => {
                        let dateWiseAttendanceData = Object.values(employee.dateWiseAttendanceData);

                        let hasAbsent = dateWiseAttendanceData.some((attendance) => {
                            return attendance.comment === 'Absent';
                        });

                        let hasLeave = dateWiseAttendanceData.some((attendance) => {
                            return attendance.comment === 'Leave';
                        });

                        let lateData = null;
                        let hasLate = dateWiseAttendanceData.some((attendance) => {
                            if (attendance.clockIn > attendance.dutyThresholdTime) {
                                // Parse clockIn time
                                let [time, modifier] = attendance.clockIn.split(' ');
                                let [hours, minutes] = time.split(':');
                                let formattedClockIn = `${hours}:${minutes} ${modifier}`;
                                lateData = {
                                    'full_name': employee.emp_name,
                                    'emp_id': employee.emp_id,
                                    'clockIn': formattedClockIn,
                                    'dutyThresholdTime': attendance.dutyThresholdTime,
                                    'comment': 'Late'
                                };
                                return true;  // Indicate that there's a late entry
                            }
                            return false;  // No late entry for this attendance record
                        });

                        if (hasAbsent || hasLeave) {
                            notCheckedInData.push({
                                'full_name': employee.emp_name,
                                'emp_id': employee.emp_id,
                                'comment': hasAbsent ? 'Absent' : 'Leave'
                            });
                        }

                        if (hasLate && lateData) {
                            lateEmployeeData.push(lateData);
                        }
                    });

                    console.log("🚀 ~ notCheckedInData:", notCheckedInData);
                    console.log("🚀 ~ lateEmployeeData:", lateEmployeeData);

                    let htmlContent = '';

                    if (notCheckedInData.length > 0) {
                        htmlContent = `
                                <div class="flex flex-col">
                                    <div class="inline-block min-w-full overflow-hidden overflow-y-auto align-middle h-[20rem]">
                                        <table class="min-w-full  divide-y divide-gray-200 dark:divide-neutral-700 !text-xs">
                                            <thead class="sticky top-0">
                                                <tr class="bg-gray-200 dark:bg-neutral-900">
                                                    <th class="text-left px-2 py-2 font-semibold uppercase text-xs dark:text-teal-500">
                                                        Name
                                                    </th>
                                                    <th class="text-left px-2 py-2 font-semibold uppercase text-xs dark:text-teal-500">
                                                        Status
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>`;

                        notCheckedInData.forEach((item) => {
                            htmlContent += `
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                                                        <td class="px-2 py-2 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                                                            <p class="">${item.full_name} <span class="text-xs text-gray-700 dark:text-gray-400">(${item.emp_id})</span></p>
                                                        </td>
                                                        <td class="px-2 py-2 text-xs text-gray-800 whitespace-nowrap dark:text-neutral-200 font-semibold">
                                                            ${item.comment === 'Leave' ? '<span class="text-teal-600">Leave</span>' : '<span class="text-[#831b94]">Absent</span>'}
                                                        </td>
                                                    </tr>`;
                        });

                        htmlContent += `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>`;
                    } else {
                        htmlContent = `<div class="py-10 text-center text-base text-slate-400">No Records Found!</div>`;
                    }

                    $('#lateEmployeeCount').html(`(${lateCount})`)
                    $('#notCheckedInCount').html(`(${notCheckedInCount})`)
                    $('#notCheckedInListManagable').html(htmlContent);
                    getAttendanceStats(attendancetodayData)
                    getLateEmployeeDataManagable(lateEmployeeData)
                } else {

                }
            })
            @endif
        });
        function getAttendanceStats(data) {
            $("#attendanceToday").html('')
            let colors=["#059669", "#dc2626", "#3b82f6"]
            let regularCount=data.regularCount;
            let lateCount=data.lateCount;
            let notCheckedInCount=data.notCheckedInCount;
            var options = {
                series: [{
                    data: [regularCount, lateCount, notCheckedInCount]
                }],
                chart: {
                    height: 300,
                    type: 'bar',
                    events: {
                        click: function(chart, w, e) {
                            // console.log(chart, w, e)
                        }
                    }
                },
                colors: colors,
                plotOptions: {
                    bar: {
                        columnWidth: '45%',
                        distributed: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                xaxis: {
                    categories: [
                        ['Regular', regularCount],
                        ['Late', lateCount],
                        ['Not Checked In', notCheckedInCount],
                    ],
                    labels: {
                        style: {
                            colors: colors,
                            fontSize: '12px'
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#attendanceToday"), options);
            chart.render();
        }
        function getLateEmployeeData(employeeIds) {
            let url=`${baseUrl}?a=get-late-employee-data&id=${employeeIds}`;
            $.ajax(url).then(function (res) {
                if (res.status === 1) {
                    let data = res.data;
                    console.log("🚀 ~ data:", data);
                    $(`#lateEmployeeSpinner`).html('');
                    if (data.length > 0) {
                        let tableHeader = `
                            <thead class="sticky top-0">
                                <tr class="bg-gray-200 dark:bg-neutral-900">
                                    <th class="text-left px-2 pr-0 py-2 font-semibold uppercase dark:text-teal-500">
                                        Name
                                    </th>
                                    <th class="text-left px-0 py-2 font-semibold uppercase dark:text-teal-500">
                                        Threshold
                                    </th>
                                    <th class="text-left px-2 pr-0 py-2 font-semibold uppercase dark:text-teal-500">
                                        Clock In
                                    </th>
                                </tr>
                            </thead>`;

                        let tableBody = data.map(item => {
                            let clockInTime = new Date(item.clockIn);
                            let formattedClockIn = clockInTime.toLocaleString('en-US', {
                                hour: 'numeric',
                                minute: 'numeric',
                                hour12: true
                            });

                            return `
                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                                    <td class="px-2 pr-0 py-2 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                                        <p class="">${item.name} <span class="text-xs text-gray-700 dark:text-gray-400">(${item.emp_id})</span></p>
                                    </td>
                                    <td class="px-0 py-2 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200 uppercase">
                                        ${item.dutyThresholdTime}
                                    </td>
                                    <td class="px-2 pr-0 py-2 text-xs font-semibold text-[#831b94] whitespace-nowrap dark:text-neutral-200">
                                        ${formattedClockIn}
                                    </td>
                                </tr>
                            `;
                        }).join('');

                        $("#lateEmployeesList").html(tableHeader + `<tbody>` + tableBody + `</tbody>`);
                    } else {
                        $("#lateEmployeesList").html(`
                            <tbody>
                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                                    <td class="text-center px-2 pr-0 py-4 pt-24 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                                        Not Found!
                                    </td>
                                </tr>
                            </tbody>
                        `);
                    }
                } else {
                    toastr.error(res.msg);
                }
            })

        }
        function getLateEmployeeDataManagable(data) {
            $(`#lateEmployeeSpinner`).html('');
            if (data.length > 0) {
                let tableHeader = `
                    <thead class="sticky top-0">
                        <tr class="bg-gray-200 dark:bg-neutral-900">
                            <th class="text-left px-2 pr-0 py-2 font-semibold uppercase text-xs dark:text-teal-500">
                                Name
                            </th>
                            <th class="text-left px-0 py-2 font-semibold uppercase text-xs dark:text-teal-500">
                                Threshold
                            </th>
                            <th class="text-left px-2 pr-0 py-2 font-semibold uppercase text-xs dark:text-teal-500">
                                Clock In
                            </th>
                        </tr>
                    </thead>`;

                let tableBody = data.map(item => {
                    let formattedClockIn=''
                    if(item.comment){
                        formattedClockIn=item.clockIn
                    }
                    else{
                        let clockInTime = new Date(item.clockIn);
                        formattedClockIn = clockInTime.toLocaleString('en-US', {
                            hour: 'numeric',
                            minute: 'numeric',
                            hour12: true
                        });
                    }

                    return `
                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                            <td class="px-2 pr-0 py-2 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                                <p class="">${item.full_name} <span class="text-xs text-gray-700 dark:text-gray-400">(${item.emp_id})</span></p>
                            </td>
                            <td class="px-0 py-2 text-xs font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200 uppercase">
                                ${item.dutyThresholdTime}
                            </td>
                            <td class="px-2 pr-0 py-2 text-xs font-semibold text-[#831b94] whitespace-nowrap dark:text-neutral-200">
                                ${formattedClockIn}
                            </td>
                        </tr>
                    `;
                }).join('');

                $("#lateEmployeesList").html(tableHeader + `<tbody>` + tableBody + `</tbody>`);
            } else {
                $("#lateEmployeesList").html(`
                    <tbody>
                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                            <td class="text-center px-2 pr-0 py-4 pt-24 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                                Not Found!
                            </td>
                        </tr>
                    </tbody>
                `);
            }

        }
    </script>
    <script>
        /*let holidayData = <?php echo json_encode($holiday); ?>;
        let weekendData = <?php echo json_encode($weekend); ?>;
        let searchArray = weekendData.map(day => day.charAt(0).toLowerCase() + day.slice(1));

        holidayData.forEach(function(event) {
            var endDate = new Date(event.end);
            endDate.setDate(endDate.getDate() + 1);
            event.end = endDate.toISOString().split('T')[0];
            event.url = "/holiday";
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: 'UTC',
                initialView: 'dayGridMonth',
                // initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                editable: true,
                events: holidayData,
                dayCellContent: function(arg) {
                    return { html: arg.dayNumberText };
                },
                eventDidMount: function(info) {
                    if (info.event.textColor) {
                        info.el.style.color='#000000';
                    }
                },
                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                },
                // eventClassNames: "bg-green-600 p-1 text-xs border-none"

            });


            let weekName = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            let indexArray = searchArray.map(day => weekName.indexOf(day));

            var startDate = new Date(new Date().getFullYear(), 0, 1); // January 1st
            var endDate = new Date(new Date().getFullYear(), 11, 31); // December 31st
            var currentDate = startDate;
            while (currentDate <= endDate) {
                var dayOfWeek = currentDate.getDay();
                indexArray.forEach(index => {

                    if (dayOfWeek === index) {
                        calendar.addEvent({
                            title: 'Weekend',
                            start: currentDate,
                            allDay: true,
                            display: 'background',
                            // color: 'yellow',
                            // backgroundColor: 'red',
                            // textColor: 'green'
                        });
                    }

                });

                currentDate.setDate(currentDate.getDate() + 1);
            }
            calendar.render();
        });*/
    </script>
    <script>
        window.addEventListener('load', () => {
            // Apex Doughnut Chart
            (function() {

            })();
        });
    </script>
@endsection

