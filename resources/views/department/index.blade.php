@extends('layouts.app')
@section('title', 'Department List')
@section('pageTitle', 'Department List')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Department List', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection

@section('content')
    <div id="departmentList">
        <x-containers.container-box>
            <div class="">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end ">
                    <div>
                        <label for="select-filter" class="inputLabel">Search</label>
                        <input type="text" class="search inputField" id="searchKey" placeholder="Type to search...">
                    </div>
                    <div>
                        <label for="select-filter" class="inputLabel">Sort</label>
                        <div class="">
                            <button class="sort button-outline !mb-3 text-2xl hover:text-[#831b94]" data-sort="name"><i class="ti ti-sort-ascending-letters"></i></button>
                        </div>
                    </div>
                    {{--<div>
                        <label for="select-filter" class="inputLabel">Sort By Name</label>
                        <div class="">
                                <button class="sort button-outline !mb-3 text-2xl hover:text-[#831b94] group" data-sort="count">
                                    <i class="ti ti-sort-ascending-letters group-[.desc]:hidden block"></i>
                                </button>
                        </div>
                    </div>--}}
                    <div class=""></div>
                    <div class="h-full flex flex-col items-end justify-center mr-2">
                        <div class="flex items-center align-middle gap-x-2">
                            @if(userCan('leave.create'))
                                <div class="inline-block tooltip tooltip-left" data-tip="Add Department">
                                    <button type="button" onclick="createDepartment.showModal()" class="actionBtn bg-[#831b94] ">
                                        {{-- <i class="ti ti-plus"></i> --}}
                                        <i class="fa-solid fa-circle-plus"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </x-containers.container-box>
        @if($departments->count()>0)
            <div class="list grid grid-cols-4 gap-6 mt-6">
                @foreach($departments as $key=>$item)
                    <x-cards.department-card :data="$item"></x-cards.department-card>
                @endforeach
            </div>
        @else
            <p class="text-lg text-center py-12 text-gray-600">No records found!</p>
        @endif
    </div>

@endsection
@include('department.create')
@section('scripts')

    <script>
        var options = {
            valueNames: [ 'name', 'count' ]
        };

        var departmentList = new List('departmentList', options);
    </script>
@endsection



