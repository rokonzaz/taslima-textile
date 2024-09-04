@extends('layouts.app')
@section('title', 'Roles')
@section('pageTitle', 'Roles List')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Roles List', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')
    <!-- create button -->
    <div class="inline-block tooltip"  data-tip="Create Role">
        <button type="button" onclick="createRoleModal.showModal()" class="actionBtn bg-[#831b94]"><i class="fa-solid fa-circle-plus"></i></button>
    </div>
@endsection
@section('content')
    <div class="grid mt-5 sm:grid-cols-1 md:grid-cols-4 xl:grid-cols-5 gap-2 sm:gap-8">

        @foreach ($roles as $item)
            <div class="group hover:shadow-xl bg-white border border-gray-200 rounded-lg dark:bg-neutral-800 dark:dark:border-neutral-700">

                <div class="flex justify-between px-4 pt-4">
                    @if ($item->is_changeable)
                        <div class="relative inline-flex hs-dropdown">
                            <button id="hs-dropdown-custom-icon-trigger" type="button"
                                class="flex items-center justify-center text-sm font-semibold text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm hs-dropdown-toggle size-9 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                <svg class="flex-none text-gray-600 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1" />
                                    <circle cx="12" cy="5" r="1" />
                                    <circle cx="12" cy="19" r="1" />
                                </svg>
                            </button>
                                <div class=" hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-52 bg-white shadow-xl rounded-lg p-2 mt-2 border dark:bg-neutral-800 dark:border dark:dark:border-neutral-700"
                                aria-labelledby="hs-dropdown-custom-icon-trigger">

                                    <button type="button"
                                        class="editUserBtn w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer"
                                        onclick="editRoleModal('Edit Role for {{ $item->name }}', '{{ url('/roles/edit/' . $item->id) }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <button type="button"
                                        onclick="deletePopup('Delete Role', '{{ $item->name }}', '{{ route('roles.delete', ['id'=>$item->id]) }}')"
                                        class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-person-x" viewBox="0 0 16 16">
                                            <path
                                                d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z" />
                                            <path
                                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708" />
                                        </svg>
                                        Delete
                                    </button>

                                </div>


                        </div>
                    @endif
                    <div
                        class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-lg !text-xs font-medium  dark:bg-red-800/30 dark:text-white {{ $item->is_active ? 'bg-teal-100 dark:bg-teal-700 text-teal-800' : 'bg-red-100 dark:bg-red-700 text-black dark:text-white' }}">
                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                    </div>
                </div>
                <div class="flex flex-col items-center pb-10">
                    <div class="text-center py-10 text-6xl text-neutral-600 group-hover:text-[#831b94] duration-200"><i class="fa-solid fa-user-shield"></i></div>
                    <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $item->name }}</h5>
                </div>
            </div>
        @endforeach

    </div>
    <div class="flex justify-between items-center mt-8">
        <h4 class="text-xl font-medium">All Permission</h4>
        @if(userCan('role.edit'))
            <div class="tooltip tooltip-left"  data-tip="Edit Permissions"><a href="{{route('permission.edit')}}" class="actionBtn bg-[#831b94]"><i class="ti ti-edit"></i></a></div>
        @endif
    </div>
    <div class="border rounded-md shadow-xl mt-2 bg-white">
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
                                if($role->slug=='super-admin'){
                                    $isChecked=true;
                                }else {
                                    $isChecked = $rolePermissions->contains(function ($rolePermission) use($roleId, $permissionId){
                                        return $rolePermission->role_id == $roleId && $rolePermission->permission_id == $permissionId;
                                    });
                                }
                            @endphp
                            @if($isChecked)
                                <td class="border-x text-center p-0 bg-green-50 hover:bg-green-100">
                                    <p class="text-green-700 text-lg"><i class="fa-solid fa-check"></i></p>
                                </td>
                            @else
                                <td class="border-x text-center p-0">
                                    <p class="text-red-300"><i class="fa-solid fa-xmark"></i></p>
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            @endforeach

        </table>
    </div>
@endsection
@includeWhen(userCan('role.create'), 'roles.create' )
@section('scripts')
@endsection




