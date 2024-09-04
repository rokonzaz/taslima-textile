@extends('layouts.app')
@section('title', 'Team')
@section('pageTitle', 'Team List')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Team List', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection
@section('content')
    <x-containers.container-box class="">
        <div class="col-span-3 pl-4" id="teamMemberListWrap"></div>
    </x-containers.container-box>
    {{--<x-containers.container-box class="">
        <div class="grid grid-cols-4 divide-x">
            <div class="pr-2">
                <div class="flex justify-between items-center">
                    <h4 class="text-lg font-medium px-2">All Teams</h4>
                    <button onclick="initializeSelectize('c_supervisor','get-supervisor-list', '');createTeamModal.showModal();" class="actionBtn bg-[#831b94]"><i class="fa-solid fa-circle-plus"></i></button>
                </div>
                <div class="h-[calc(100vh_-_3.5rem)] pr-3 overflow-y-scroll mt-4">
                    <div class="grid grid-cols-1 gap-6 px-2 pt-2">
                        @foreach ($teams as $item)
                            <div id="team_{{$item->id}}" class="teamCard max-w-xs relative bg-white hover:bg-[#831b94] hover:!text-white hover:shadow-lg border border-gray-200 rounded-xl shadow-lg dark:hover:bg-[#831b94] dark:hover:!text-white dark:hover:shadow-lg dark:bg-neutral-800 dark:border-neutral-700 cursor-pointer group" onclick="getTeamMembersData({{$item->id}}, 'team-members'); setActiveTab('{{$item->id}}', 'team-tab') ">
                                <div class="flex items-center p-2">
                                    <div class="flex-shrink-0">
                                        <span class="m-1 inline-flex justify-center items-center size-12 rounded-full bg-gray-100 text-neutral-600 dark:bg-neutral-700"><i class="fa-solid fa-people-group"></i></span>
                                    </div>

                                    <div class="grow ms-3 me-5">
                                        <h3 class="text-gray-800 group-hover:text-white font-medium text-lg dark:text-white">{{ $item->name }}</h3>
                                        <p class="block mb-1.5 text-sm group-hover:text-slate-50 text-gray-500 dark:text-neutral-400">--}}{{--{{ $item->getDepartmentHead->name }} --}}{{--</p>
                                        <div class="mt-2 flex flex-col gap-x-3">
                                            <span class="memberCount block mb-1.5 text-xs group-hover:text-white text-red-500 dark:text-neutral-400"><span id="teamMemberCount_{{$item->id}}">{{$item->teamMember->count()}}</span> Employee</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>

            </div>
            <div class="col-span-3 pl-4" id="teamMemberListWrap"></div>
        </div>
    </x-containers.container-box>--}}

@endsection
@include('team.create')
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



