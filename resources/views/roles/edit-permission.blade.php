@extends('layouts.app')
@section('title', 'Edit Role Permission')
@section('pageTitle', 'Edit Role Permission')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Roles List', 'url'=>route('roles.index')],['title'=>'Edit Permission', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection
@section('content')
    <div class="border rounded-md shadow-xl mt-6 bg-white">
        <form action="{{route('permission.update')}}" method="post">
            @csrf
            <div class="flex justify-between items-center">
                <div class="p-4">
                    <p class="text-lg font-semibold text-center">All Permissions</p>
                </div>
                <div class="px-4">
                    <button class="submit-button">Save</button>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="w-48 h-1 bg-[#831b94]"></div>
                <div class="w-full h-1 bg-neutral-200"></div>
            </div>
            <div class="">
                @php
                    $roleCount=$roles->count();
                @endphp
                <table class="table border-b">
                    <tr class="border-b">
                        <th class="w-1/2">Category</th>
                        @foreach($roles as $role)
                            <th class="text-center" style="width:{{50/$roleCount}}%">{{$role->name??''}}</th>
                        @endforeach
                    </tr>
                    @foreach($permissions as $category=>$data)
                        <tr>
                            <td colspan="{{$roleCount+1}}" class="bg-neutral-50 text-[#831b94] text-base font-medium">
                                <i class="fa-solid fa-shield-virus"></i> {{ucfirst($category)}}
                            </td>
                        </tr>
                        @foreach($data as $item)
                            <tr class="border-y-none">
                                <td class="font-medium text-neutral-900">
                                    {{$item->label}}
                                </td>
                                @foreach($roles as $role)
                                    @php
                                        $roleId=$role->id;
                                        $permissionId=$item->id;
                                        $isChecked = $rolePermissions->contains(function ($rolePermission) use($roleId, $permissionId){
                                                return $rolePermission->role_id == $roleId && $rolePermission->permission_id == $permissionId;
                                        });
                                    @endphp
                                    <td class="border-x text-center p-0 hover:bg-neutral-50">
                                        <label for="checkbox_{{$roleId}}_{{$permissionId}}" class="block">
                                            <input type="checkbox" {{$isChecked ? 'checked' : ''}} name="permission[{{$roleId}}][{{$permissionId}}]" id="checkbox_{{$role->id}}_{{$item->id}}" {{$role->slug=='super-admin' ? 'disabled' : ''}} class="shrink-0 mt-0.5 border-gray-200 rounded text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800">
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach

                </table>
            </div>
            <div class="flex justify-end p-4">
                <button class="submit-button">Save</button>
            </div>
        </form>
    </div>
    {{--<x-containers.container-box>
        <div class="flex flex-wrap">
            <div class="border-e-2 border-gray-200 dark:border-neutral-700">
                <nav class="flex flex-col space-y-2" aria-label="Tabs" role="tablist" data-hs-tabs-vertical="true">
                    @php $i=0; @endphp
                    @foreach($permissions as $category=>$item)
                        <button type="button" class="tabButton {{$i==0 ? 'active' : ''}}" id="vertical-tab-item-{{$i}}" data-hs-tab="#vertical-tab-{{$i}}" aria-controls="vertical-tab-{{$i}}" role="tab">
                            {{ucfirst($category)}}
                        </button>
                        @php $i++; @endphp
                    @endforeach
                </nav>
            </div>

            <div class="ms-3">
                @php $i=0; @endphp
                @foreach($permissions as $category=>$data)
                    <div id="vertical-tab-{{$i}}" class="{{$i!=0 ? 'hidden' : ''}}" role="tabpanel" aria-labelledby="vertical-tab-item-{{$i}}">
                        @foreach($data as $item)
                            <div class="permissions">
                                <button class="select-all-btn mb-2" data-hs-tab="tab-1">Select All</button>
                                <div class="permission-group" id="tab-1">
                                    <label class="block"><input type="checkbox" class="mr-2"> View</label>
                                    <label class="block"><input type="checkbox" class="mr-2"> Edit</label>
                                    <label class="block"><input type="checkbox" class="mr-2"> Create</label>
                                    <label class="block"><input type="checkbox" class="mr-2"> Delete</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @php $i++; @endphp
                @endforeach


            </div>
        </div>
    </x-containers.container-box>--}}
@endsection
@section('scripts')

@endsection



