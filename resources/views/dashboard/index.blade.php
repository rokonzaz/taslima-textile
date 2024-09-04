@php
    use App\Models\Employee;
    use App\Models\Attendance;
    use Illuminate\Support\Facades\DB;
    use App\Http\Controllers\AttendanceController;
    use App\Models\Organization;
    $date = date('Y-m-d');
    $manageableEmployeeIds=getManageableEmployeesIDs();
    $manageableEmployeeIdsCount=count($manageableEmployeeIds);
    $userRole=getUserRole();
    $isRoleSuperAdminHr=isRoleIn(['super-admin','hr']);

    $authEmpId=getAuthEmpId();

@endphp

@extends('layouts.app')
@section('title', 'Dashboard')
@section('pageTitle', 'Dashboard')
@section('content')

    @if($isRoleSuperAdminHr || $manageableEmployeeIdsCount>0)
        @php
            $organizations = Organization::get();
        @endphp
        <x-containers.container-box>
            <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-5 sm:gap-6">
                @if($isRoleSuperAdminHr)
                    <div>
                        <select id="organization"  name="select-filter" class="inputFieldCompact select2">
                            <option value="">Organization (All)</option>
                            @foreach($organizations as $item)
                                <option value="{{$item->id}}" {{request()->has('organization') ? request('organization') : ''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div id="dateField" class="max-w-sm space-y-3">
                    <input type="date" id="date" value="{{date('Y-m-d')}}" class="inputFieldCompact " placeholder="Select a Date...">
                </div>
            </div>
        </x-containers.container-box>


        <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-2 mt-3">
            @php
                $data=['title'=>'Total Employees', 'counting'=>"", 'icon'=>'<i class="ti ti-users-group"></i>', 'url'=>'#', 'id'=>'employeeCountCard', 'loader'=>true]
            @endphp
            <x-cards.dashboard-card :data="$data"></x-cards.dashboard-card>
            @php
                $data=['title'=>'Present', 'counting'=>'', 'icon'=>'<i class="ti ti-calendar-user"></i>', 'url'=>'#', 'id'=>'presentCountCard', 'loader'=>true];
            @endphp
            <x-cards.dashboard-card :data="$data"></x-cards.dashboard-card>
            @php
                $data=['title'=>'Absent', 'counting'=>0, 'icon'=>'<i class="ti ti-user-exclamation"></i>', 'url'=>'#', 'id'=>'absentCountCard', 'loader'=>true];
            @endphp
            <x-cards.dashboard-card :data="$data"></x-cards.dashboard-card>
            @php
                $data=['title'=>'On leave', 'counting'=>0, 'icon'=>'<i class="ti ti-user-share"></i>', 'url'=>'#', 'id'=>'leaveCountCard', 'loader'=>true];
            @endphp
            <x-cards.dashboard-card :data="$data"></x-cards.dashboard-card>
        </div>

        <div class="mt-3">
            <div class="grid grid-cols-3 gap-4">
                @php $data=['title'=>'Attendance Today', 'id'=>'attendanceReport'] @endphp
                <x-cards.dashboard-table :data="$data"></x-cards.dashboard-table>
                @php $data=['title'=>'Absent Employees', 'id'=>'absentEmployee', 'export'=>true] @endphp
                <x-cards.dashboard-table :data="$data"></x-cards.dashboard-table>
                @php $data=['title'=>'Late Employees', 'id'=>'lateEmployee', 'export'=>true] @endphp
                <x-cards.dashboard-table :data="$data"></x-cards.dashboard-table>
            </div>
        </div>
    @endif

    @if($authEmpId)
        <div class="mt-4">
            <div class="flex items-center justify-between">
                <h3 class="pb-1 font-semibold text-gray-800 text-md dark:text-white">My Reports</h3>
                <div id="monthField" class="max-w-sm space-y-3">
                    <input type="month" id="month" value="{{date('Y-m')}}" class="inputFieldCompact !bg-white" placeholder="Select a Month...">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-1">
                @php $data=['title'=>'Leave List', 'id'=>'leaveReport', 'export'=>true] @endphp
                <x-cards.dashboard-table :data="$data"></x-cards.dashboard-table>
                @php $data=['title'=>'Late List', 'id'=>'lateReport', 'export'=>true] @endphp
                <x-cards.dashboard-table :data="$data"></x-cards.dashboard-table>
                @php $data=['title'=>'Monthly Summary', 'id'=>'monthlyReportSummary', 'export'=>true] @endphp
                <x-cards.dashboard-table :data="$data"></x-cards.dashboard-table>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-3 gap-4 mt-4">
        <div class="">
            @include('dashboard.holiday')
        </div>
        <div class="col-span-2">
            @include('dashboard.notices')
        </div>
    </div>

@endsection

@section('scripts')
    @if($isRoleSuperAdminHr || $manageableEmployeeIdsCount>0)
        <script>
            $(function () {
                getDashboardData();
                $('#organization, #date').change(function() {
                    getDashboardData();
                });
            })
        </script>
    @endif
    @if($authEmpId)
        <script>
            $(function () {
                getDashboardData('my-report');
                $('#month').change(function() {
                    getDashboardData('my-report');
                });
            })
        </script>
    @endif


    <script>
        $(function () {
            $('#periodType').change(function() {
                if ($(this).val() === 'Monthly') {
                    $('#monthField').show();
                    $('#dateField').hide();
                } else {
                    $('#monthField').hide();
                    $('#dateField').show();
                }
            }).trigger('change');
        })
    </script>
@endsection

