@extends('layouts.app')
@php
    $title=$team->name;
    if(isset($team->teamOrganization->name)){
        $title.=" ({$team->teamOrganization->name})";
    }
@endphp
@section('title', $team->name)
@section('pageTitle', $title)
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>$title, 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection

@section('additionalButton')
    @php
    $editPermission=userCan('team.edit');
    $deletePermission=userCan('team.delete');
    @endphp
    @if($editPermission || $deletePermission)
        <div class="flex justify-end p-2">
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </div>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-40">
                    <li>
                        @if($editPermission)
                            <button  onclick="initializeSelectize('e_supervisor','get-supervisor-list', '{{$team->getDepartmentHead->pluck('supervisor_id')}}', 'supervisor-spinner'); editTeamModal.showModal();" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                                <i class="ti ti-edit"></i>
                                Edit Team
                            </button>
                        @endif
                        @if($deletePermission)
                            <button onclick="deletePopup('Delete Team', '{{$team->name}}', '{{route('team.delete', ['id'=>$team->id])}}')" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                                <i class="fa-regular fa-trash-can"></i>
                                Delete
                            </button>
                        @endif

                    </li>
                </ul>
            </div>
        </div>
    @endif
    @if(userCan('team.create'))
        @php
            $departmentHeadIds = $team->getDepartmentHead->pluck('supervisor_id')->toArray();
            $departmentHeadIdsString = implode(', ', $departmentHeadIds);
        @endphp
        <div class="tooltip tooltip-left"  data-tip="Create Team">
            <button onclick="initializeSelectize('c_supervisor','get-supervisor-list', '');createTeamModal.showModal();" class="actionBtn bg-[#831b94]"><i class="fa-solid fa-circle-plus"></i></button>
        </div>
    @endif

@endsection
@section('content')
    <x-containers.container-box data="">
        <div class="text-center flex items-center justify-between">
            <p class="block text-sm text-gray-500 dark:text-neutral-400">
                Line Manager:
                @if($team->getDepartmentHead->count()>0)
                    @foreach($team->getDepartmentHead as $item)
                        @if($item->employee)
                            <a href="{{route('employees.view', ['id'=>$item->employee->id])}}" class="bg-[#831b94] px-2 py-1 text-white rounded-md">{{ $item->employee->full_name }}</a>
                        @else
                            <a href="#" title="Just a user" class="bg-[#831b94] px-2 py-1 text-white rounded-md">{{ $item->employee->full_name }}</a>
                        @endif

                    @endforeach
                @else
                    N/A
                @endif
            </p>
            <div class=""> Total Members: <span class="text-[#831b94] font-medium" id="teamMemberCount">0</span></div>
            <p class="block mb-1.5 text-sm text-gray-500 dark:text-neutral-400">Department: <span class="font-medium">{{ $team->teamDepartment->name ?? '' }}</span></p>
            {{--<p class="block mb-1.5 text-sm text-gray-500 dark:text-neutral-400">Organization: {{ $team->teamOrganization->name ?? '' }}</p>--}}
        </div>
    </x-containers.container-box>
    <x-containers.container-box data="!p-0">
        <div id="teamMemberListWrap" class=""></div>
    </x-containers.container-box>

@endsection
@include('team.create')
@include('team.edit')
@section('scripts')
    <script src="{{asset('assets/js/list.min.js')}}"></script>
    <script>
        $(function (){
            getTeamMembersData({{$team->id}}, 'team-members');
            initializeActiveTab();
        })
    </script>

@endsection



