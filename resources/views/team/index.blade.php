@extends('layouts.app')
@section('title', 'Team')

@section('additionalButton')

@endsection
@section('content')

    <div class="grid grid-cols-5 h-[calc(100vh_-_90px)] overflow-y-hidden">
        <div class="">
            <div class="w-full relative mt-1" id="activeTeam"></div>
            <div class="flex justify-between items-center my-2 ">
                <h4 class="text-lg font-semibold pr-2">Team List <span class="text-[#831b94] italic">({{$teams->count()}})</span></h4>
                <button onclick="initializeSelectize('c_department_head','get-department-head-list', '');createTeamModal.showModal();" class="actionBtn bg-[#831b94] hover:bg-red-700 hover:shadow-lg"><i class="fa-solid fa-circle-plus"></i></button>
            </div>
            <div class="grid grid-cols-1 gap-4 pb-1 pr-3 h-[calc(100vh_-_240px)] overflow-y-scroll " id="teamCardWrap">
                @foreach ($teams as $item)
                    <div class="w-full relative" id="team_{{$item->id}}">
                        <div onclick="getTeamMembersData({{$item->id}}, 'team-members');" class="teamCard max-w-xs relative hover:bg-[#831b94] hover:!text-white hover:shadow-md border border-gray-200 rounded-md  dark:hover:bg-[#831b94] dark:hover:!text-white dark:hover:shadow-lg dark:bg-neutral-800 dark:border-neutral-700 cursor-pointer group">
                            <div class="flex items-center p-1 truncate">
                                <div class="flex-shrink-0">
                                    <span class="m-1 inline-flex justify-center items-center size-8 rounded-full bg-gray-100 text-neutral-600 dark:bg-neutral-700"><i class="fa-solid fa-people-group"></i></span>
                                </div>
                                <div class="grow ms-3 me-0">
                                    <div class="flex justify-between">
                                        <h3 class="teamName w-full truncate text-gray-800 group-hover:text-white font-medium text-md dark:text-white">{{ $item->name }}</h3>
                                        <div class="hidden additionalButton">
                                            <div class="flex items-center">
                                                <button type="button" onclick="editModalAjax('team-edit', 'smallModal', {{$item->id}})" class="text-white size-6 aspect-square hover:bg-red-100 rounded-full hover:text-indigo-600">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button type="button" onclick="deletePopup('Delete Team', '{{$item->name}}', '{{route('team.delete', ['id'=>$item->id])}}')" class="text-white size-6 aspect-square hover:bg-red-100 rounded-full hover:text-[#831b94]">
                                                    <i class="ti ti-trash"></i>
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $departmentHead=$item->getDepartmentHead->first();
                                    @endphp

                                    <div class="flex flex-col gap-x-3">
                                        <span class="memberCount block text-xs group-hover:text-white text-red-500 dark:text-neutral-400"><span id="teamMemberCount_{{$item->id}}">{{$item->teamMember->count()}}</span> Employee</span>
                                    </div>
                                    <p class="teamSupervisor  truncate text-xs group-hover:text-slate-50 text-gray-500 dark:text-neutral-400">Sup: {{$departmentHead->employee->full_name ?? ''}}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-span-4">
            <div class="" id="teamMemberListWrap">
                <div class="w-full h-full flex items-center justify-center">
                    <div class="py-8 px-12 text-2xl font-bold italic flex flex-col items-center justify-center gap-4 border border-dashed border-red-600 rounded">
                        <span class="text-3xl"><i class="ti ti-click"></i></span>

                        <p>Please select a team first to view more details</p>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
@include('team.create')
{{--@include('team.edit')--}}
@section('scripts')
    @if(request()->has('active'))
        @if(request('active')!='')
            <script>
                $(function (){
                    getTeamMembersData({{request('active')}}, 'team-members');
                    initializeActiveTab();
                })
            </script>

        @endif
    @endif

@endsection



