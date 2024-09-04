@extends("layouts.app")
@section("title", "Employee Details")
@section("pageTitle", "Employee Profile")
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Employees Profile', 'url'=>route('employees.index')],['title'=>'('.$employee->emp_id.') '.$employee->full_name, 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection

@section("additionalButton")

@endsection

@section("content")


    <x-containers.container-box>
        @if($employee->is_active==0)
            <div class="mx-[-8px] mt-[-8px] mb-2 p-1 text-white bg-[#831b94] rounded-md rounded-b-none text-center">
                Inactive Employee
            </div>
        @endif
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5 lg:gap-2 pr-2">
            <div class="md:col-span-3 col-span-1 flex flex-col md:flex-row items-center w-full gap-4 md:gap-14 pt-4 md:pt-0 md:pl-14">
                <div class="flex justify-center">
                    <div class="flex-col gap-3 hidden" id="profilePicEdit">
                        <form action="{{ route('employees.update',['id'=>$employee->id, 'a' => 'profile-image']) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="profile-pic w-40 h-40 border-8 border-gray-800 dark:border-neutral-500 dark:hover:border-red-700 hover:border-red-700 dark:hover:shadow-lg hover:shadow-lg duration-300 ease-in-out rounded-full">
                                <label class="-label " for="file">
                                    <span class="fa-solid fa-camera flex items-center"></span>
                                    <span>Change Image</span>
                                </label>
                                <input id="file" name="profile_photo" type="file" accept="image/*" onchange="loadFile(event)" />
                                <img class="" src="{{ employeeProfileImage($employee->emp_id, $employee->profile_photo) }}" onerror="this.onerror=null;this.src='{{employeeDefaultProfileImage($employee->gender)}}';" alt="{{$employee->full_name ??''}}" id="output"/>
                            </div>
                            <div class="flex items-center justify-center gap-x-2 border-t px-2 py-1 dark:border-neutral-700">
                                <button onclick="cancelEmployeeProfilePicEdit('profilePic')" type="button" class="inline-flex items-center rounded-lg bg-black px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800" id="close-form-btn-for-documets-four">
                                    Cancel
                                </button>
                                <button type="submit" class="inline-flex items-center rounded-lg bg-red-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="relative flex flex-col gap-3" id="profilePic">
                        <img class="rounded-full border-8 border-gray-800 dark:border-neutral-500 dark:hover:border-red-700 hover:border-red-700 dark:hover:shadow-lg hover:shadow-lg w-40 h-40 object-cover duration-300 ease-in-out" src="{{ employeeProfileImage($employee->emp_id, $employee->profile_photo) }}" onerror="this.onerror=null;this.src='{{employeeDefaultProfileImage($employee->gender)}}';" alt="profile-images"/>
                        <div id="editProfileBtn" onclick="editEmployeeProfilePic()" class="absolute bottom-3 right-1 flex items-center justify-center p-2.5 bg-neutral-300 hover:bg-red-700 hover:text-white active:bg-red-800 dark:bg-neutral-300 dark:hover:text-white dark:hover:bg-[#831b94] dark:active:bg-red-700 w-9 h-9 rounded-full cursor-pointer">
                            <i class="ti ti-pencil-minus"></i>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-center items-start gap-1">
                    <p class="">
                        @if($employee->emp_id!='')
                            <button class="emp-id-btn">{{ $employee->emp_id }}</button>
                        @endif
                    </p>
                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-white">
                        {{ $employee->full_name }}
                    </h3>
                    <button type="button" class="block">
                        <span class="block">
                            <span class="inline-flex items-center gap-x-1 rounded-full bg-teal-100 px-1.5 py-1 text-xs font-medium text-teal-800 dark:bg-teal-500/10 dark:text-teal-500">
                                <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>
                                Permanent
                            </span>
                        </span>
                    </button>
                    <div class="mt-2"></div>
                    <div id="departmentHeadWrap">
                        <div class="flex flex-col justify-center gap-0.5 text-sm" id="supervisorInfo">
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-password-user">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 17v4" />
                                    <path d="M10 20l4 -2" />
                                    <path d="M10 18l4 2" />
                                    <path d="M5 17v4" />
                                    <path d="M3 20l4 -2" />
                                    <path d="M3 18l4 2" />
                                    <path d="M19 17v4" />
                                    <path d="M17 20l4 -2" />
                                    <path d="M17 18l4 2" />
                                    <path d="M9 6a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                    <path d="M7 14a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2" />
                                </svg>
                                <div class="flex items-center">
                                    <span class="font-medium">Department Head:</span>
                                    @if($employee->empDepartmentHead)
                                        @php $departmentHead=$employee->empDepartmentHead; @endphp
                                        <span class="px-1 py-0.5 text-teal-600 font-medium" id="department-headNameDisplay">{{$departmentHead->full_name ?? ''}}</span>
                                    @else
                                        <span class="px-1 py-0.5 text-teal-600 font-medium" id="department-headNameDisplay">N/A</span>
                                    @endif
                                    <div id="department-headInput" class="select2-xs ml-1"></div>
                                </div>
                                @if(isRoleIn(['super-admin', 'hr']))
                                    <button id="supervisorEditButton" onclick="setEmployeeDepartmentHeadLineManger('department-head', '{{$employee->id}}')" class="size-6 text-gray-500 hover:text-gray-800 bg-transparent hover:bg-gray-100 rounded-full duration-200">
                                        <i class="ti ti-pencil-minus"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-message-circle-user">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M19 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M22 22a2 2 0 0 0 -2 -2h-2a2 2 0 0 0 -2 2" />
                                    <path d="M12.454 19.97a9.9 9.9 0 0 1 -4.754 -.97l-4.7 1l1.3 -3.9c-2.324 -3.437 -1.426 -7.872 2.1 -10.374c3.526 -2.501 8.59 -2.296 11.845 .48c1.667 1.423 2.596 3.294 2.747 5.216" />
                                </svg>

                                <div class="flex items-center">
                                    <span class="font-medium truncate">Line Manager:</span>
                                    @if($employee->empLineManager)
                                        @php $lineManager=$employee->empLineManager; @endphp
                                        <span class="px-1 py-0.5 text-teal-600 font-medium" id="line-managerNameDisplay">{{$lineManager->full_name ?? ''}}</span>
                                    @else
                                        <span class="px-1 py-0.5 text-teal-600 font-medium" id="line-managerNameDisplay">N/A</span>
                                    @endif
                                    <div id="line-managerInput" class="select2-xs ml-1"></div>
                                </div>
                                @if(isRoleIn(['super-admin', 'hr']))
                                    <button id="line-managerEditButton"
                                        onclick="setEmployeeDepartmentHeadLineManger('line-manager', '{{$employee->id}}')"
                                        class="size-6 text-gray-500 hover:text-gray-800 bg-transparent hover:bg-gray-100 rounded-full duration-200">
                                        <i class="ti ti-pencil-minus"></i>
                                    </button>
                                @endif


                            </div>
                        </div>
                        <script>
                            function setEmployeeDepartmentHeadLineManger(a, id) {
                                $(`#${a}NameDisplay, #${a}EditButton`).addClass('hidden')
                                $(`#${a}Input`).addClass('w-full text-center')
                                $(`#${a}Input`).html($("#loader-xs").html())
                                $.ajax(`${baseUrl}/employees/edit/${id}?a=select-${a}-form`).then(function (res) {
                                    if(res.status===1){
                                        $(`#${a}Input`).html(res.html)
                                        $(`#${a}List`).select2();
                                    }else{
                                        toastr.error(res.msg)
                                    }
                                })
                            }
                            function submitDepartmentHeadLineMangerSelection(a, id) {
                                console.log(a, id)
                                let empId=$(`#${a}List`).val();
                                if(!empId) {
                                    toastr.error('Please select Employee!')
                                    return false;
                                }
                                $.ajax({
                                    url: `${baseUrl}/employees/update/${id}?a=select-${a}&type=${a}&empId=${empId}`,
                                    method: 'POST',
                                    processData: false,
                                    contentType: false,
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Adjust selector based on your CSRF token location
                                    },
                                    success: function(res) {

                                        if(res.status===1){
                                            $(`#${a}NameDisplay`).html(res.name)
                                            $(`#${a}NameDisplay, #${a}EditButton`).removeClass('hidden')
                                            $(`#${a}Input`).removeClass('w-full text-center')
                                            $(`#${a}Input`).html('')
                                            toastr.success(res.msg)
                                        }else{
                                            toastr.error(res.msg)
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Error fetching data:', error);
                                    }
                                });
                            }
                            function cancelDepartmentHeadLineMangerSelection(a) {
                                $(`#${a}NameDisplay, #${a}EditButton`).removeClass('hidden')
                                $(`#${a}Input`).removeClass('w-full text-center')
                                $(`#${a}Input`).html('')
                            }

                        </script>
                        {{--<div id="supervisorEditForm">
                            <div id="supervisorInputWrap" class="">
                                <label for="" class="inputLabel">Select Sepervisor</label>
                                <div id="supervisorInput"></div>
                            </div>
                            <div id="lineManagerInputWrap" class="">
                                <label for="" class="inputLabel">Select Line Manager</label>
                                <div id="lineManagerInput"></div>
                            </div>
                        </div>--}}
                    </div>

                    {{--@if($employee->role)
                        @if($employee->role->slug=='department-head')
                            <div class="mt-2">
                                @if($employee->user->supervisedTeam)
                                    Team:
                                    @foreach($employee->user->supervisedTeam as $item)
                                        @php $teamViewPermission=userCan('team.view'); @endphp
                                        <a href="{{$teamViewPermission ? route('team.team-members', ['id'=>$item->id]) : '#'}}" class="rounded-md bg-teal-100 px-1.5 py-1 text-xs font-medium text-teal-800 dark:bg-teal-500/10 dark:text-teal-500">{{$item->name}}</a>
                                    @endforeach
                                @endif
                            </div>
                        @else
                            <div class="mt-2">
                                @if($employee->team->count()>0)
                                    Team:
                                    @foreach($employee->team as $item)
                                        @php $teamViewPermission=userCan('team.view'); @endphp
                                        <a href="{{$teamViewPermission ? route('team.team-members', ['id'=>$item->id]) : '#'}}" class="rounded-md bg-teal-100 px-1.5 py-1 text-xs font-medium text-teal-800 dark:bg-teal-500/10 dark:text-teal-500">{{$item->name}}</a>
                                    @endforeach
                                @endif
                            </div>
                            <div class="flex flex-col text-sm mt-2">
                                @if($employee->supervisors->count()>0)
                                    <span class="font-medium">Supervisors:</span>
                                    @foreach($employee->supervisors as $item)
                                        @if(isset($item->employee))
                                            <a href="{{route('employees.view', ['id'=>$item->employee->id])}}" class="text-xs hover:text-[#831b94] hover:underline cursor-pointer">{{$item->employee->full_name}}</a>
                                        @else
                                            <span class="text-xs">N/A</span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        @endif
                    @endif--}}

                </div>
            </div>
            <div class="col-span-2 w-full flex items-center justify-end">
                <div class="w-full grid items-center justify-center gap-3 sm:grid-cols-2 sm:gap-3 lg:grid-cols-2">
                    <!-- Card -->
                    @php
                        $icon='<svg class="mt-1 size-5 flex-shrink-0 text-gray-800 dark:text-neutral-200" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-buildings" viewBox="0 0 16 16">
                              <path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z"/>
                              <path d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z"/>
                            </svg>';
                    @endphp
                    <x-cards.small-card :data="[
                        'title' => 'Organization',
                        'subtitle' => $employee->empOrganization->name ?? '',
                        'icon' => $icon
                    ]"></x-cards.small-card>
                    @php
                        $icon='<svg class="mt-1 size-6 flex-shrink-0 text-gray-800 dark:text-neutral-200" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>';
                    @endphp
                    <x-cards.small-card :data="[
                        'title' => 'Department',
                        'subtitle' => $employee->empDepartment->name ?? '',
                        'icon' => $icon
                    ]"></x-cards.small-card>

                    @php
                        $icon='<svg class="mt-1 size-6 flex-shrink-0 text-gray-800 dark:text-neutral-200" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                    <path d="M12 17h.01" />
                                </svg>';
                    @endphp
                    <x-cards.small-card :data="[
                        'title' => 'Designation',
                        'subtitle' => $employee->empDesignation->name ?? '',
                        'icon' => $icon
                    ]"></x-cards.small-card>

                    @php
                        $icon='<svg class="mt-1 size-5 flex-shrink-0 text-gray-800 dark:text-neutral-200" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                                  <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z"/>
                                  <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                </svg>';
                        $joiningDate=formatCarbonDate($employee->joining_date) ?? 'N/A';
                    @endphp
                    <x-cards.small-card :data='[
                        "title" => "Joining date",
                        "subtitle" => "<span class=\"font-medium text-[#831b94] decoration-2  dark:text-red-500\">" . $joiningDate . "</span>",
                        "icon" => $icon
                    ]'></x-cards.small-card>
                </div>

            </div>
        </div>
    </x-containers.container-box>
    @php
        $active='personal';
        if(request()->has('active')) $active=request('active');
        if(!in_array($active, ["personal","address","education","documents","skills","leave","biometric",/*"dsignature",*/"security"])) $active='personal';
    @endphp
    <x-containers.container-box>
        <div class="flex">
            <div class="flex rounded-lg bg-gray-200 p-1 transition hover:bg-slate-200 dark:bg-neutral-700 dark:hover:bg-neutral-600">
                <nav class="flex flex-wrap space-x-1" aria-label="Tabs" role="tablist">
                    <button type="button" onclick="setActiveTab('personal')" class="{{$active=='personal' ? 'active' : ''}} inline-flex items-center gap-x-2 rounded-lg bg-transparent px-3 py-2 h-9 text-sm font-medium text-gray-500 hover:hover:text-[#831b94] hover:text-gray-700 disabled:pointer-events-none disabled:opacity-50 hs-tab-active:bg-white hs-tab-active:text-gray-700 dark:text-neutral-400 dark:hover:text-white dark:hs-tab-active:bg-gray-800 hs-tab-active:dark:text-neutral-400" id="segment-item-1" data-hs-tab="#segment-1" aria-controls="segment-1" role="tab">
                        Personal Details
                    </button>
                    <button type="button" onclick="setActiveTab('address')" class="{{$active=='address' ? 'active' : ''}} inline-flex items-center gap-x-2 rounded-lg bg-transparent px-3 py-2 h-9 text-sm font-medium text-gray-500 hover:hover:text-[#831b94] hover:text-gray-700 disabled:pointer-events-none disabled:opacity-50 hs-tab-active:bg-white hs-tab-active:text-gray-700 dark:text-neutral-400 dark:hover:text-white dark:hs-tab-active:bg-gray-800 hs-tab-active:dark:text-neutral-400" id="segment-item-2" data-hs-tab="#segment-2" aria-controls="segment-2" role="tab">
                        Address Details
                    </button>
                    <button type="button" onclick="setActiveTab('education')" class="{{$active=='education' ? 'active' : ''}} inline-flex items-center gap-x-2 rounded-lg bg-transparent px-3 py-2 h-9 text-sm font-medium text-gray-500 hover:hover:text-[#831b94] hover:text-gray-700 disabled:pointer-events-none disabled:opacity-50 hs-tab-active:bg-white hs-tab-active:text-gray-700 dark:text-neutral-400 dark:hover:text-white dark:hs-tab-active:bg-gray-800 hs-tab-active:dark:text-neutral-400" id="segment-item-3" data-hs-tab="#segment-3" aria-controls="segment-3" role="tab">
                        Education
                    </button>
                    <button type="button" onclick="setActiveTab('documents')" class="{{$active=='documents' ? 'active' : ''}} inline-flex items-center gap-x-2 rounded-lg bg-transparent px-3 py-2 h-9 text-sm font-medium text-gray-500 hover:hover:text-[#831b94] hover:text-gray-700 disabled:pointer-events-none disabled:opacity-50 hs-tab-active:bg-white hs-tab-active:text-gray-700 dark:text-neutral-400 dark:hover:text-white dark:hs-tab-active:bg-gray-800 hs-tab-active:dark:text-neutral-400" id="segment-item-4" data-hs-tab="#segment-4" aria-controls="segment-4" role="tab">
                        Documents
                    </button>
                    <button type="button" onclick="setActiveTab('skills')" class="{{$active=='skills' ? 'active' : ''}} inline-flex items-center gap-x-2 rounded-lg bg-transparent px-3 py-2 h-9 text-sm font-medium text-gray-500 hover:hover:text-[#831b94] hover:text-gray-700 disabled:pointer-events-none disabled:opacity-50 hs-tab-active:bg-white hs-tab-active:text-gray-700 dark:text-neutral-400 dark:hover:text-white dark:hs-tab-active:bg-gray-800 hs-tab-active:dark:text-neutral-400" id="segment-item-5" data-hs-tab="#segment-5" aria-controls="segment-5" role="tab">
                        Skills
                    </button>
                    <button type="button" onclick="setActiveTab('leave')" class="{{$active=='leave' ? 'active' : ''}} inline-flex items-center gap-x-2 rounded-lg bg-transparent px-3 py-2 h-9 text-sm font-medium text-gray-500 hover:hover:text-[#831b94] hover:text-gray-700 disabled:pointer-events-none disabled:opacity-50 hs-tab-active:bg-white hs-tab-active:text-gray-700 dark:text-neutral-400 dark:hover:text-white dark:hs-tab-active:bg-gray-800 hs-tab-active:dark:text-neutral-400" id="segment-item-6" data-hs-tab="#segment-6" aria-controls="segment-6" role="tab">
                        Leave History
                    </button>
                    <button type="button" onclick="setActiveTab('biometric')" class="{{$active=='biometric' ? 'active' : ''}} inline-flex items-center gap-x-2 rounded-lg bg-transparent px-3 py-2 h-9 text-sm font-medium text-gray-500 hover:hover:text-[#831b94] hover:text-gray-700 disabled:pointer-events-none disabled:opacity-50 hs-tab-active:bg-white hs-tab-active:text-gray-700 dark:text-neutral-400 dark:hover:text-white dark:hs-tab-active:bg-gray-800  hs-tab-active:dark:text-neutral-400" id="segment-item-6" data-hs-tab="#segment-7" aria-controls="segment-7" role="tab">
                        Biometric
                    </button>
                    {{--<button type="button" onclick="setActiveTab('dsignature')" class="{{$active=='dsignature' ? 'active' : ''}} inline-flex items-center gap-x-2 rounded-lg bg-transparent px-3 py-2 h-9 text-sm font-medium text-gray-500 hover:hover:text-[#831b94] hover:text-gray-700 disabled:pointer-events-none disabled:opacity-50 hs-tab-active:bg-white hs-tab-active:text-gray-700 dark:text-neutral-400 dark:hover:text-white dark:hs-tab-active:bg-gray-800 hs-tab-active:dark:text-neutral-400" id="segment-item-8" data-hs-tab="#segment-8" aria-controls="segment-8" role="tab">
                        Digital Signature
                    </button>--}}
                    {{-- <button type="button" onclick="setActiveTab('security')" class="{{$active=='security' ? 'active' : ''}} inline-flex items-center gap-x-2 rounded-lg bg-transparent px-3 py-2 h-9 text-sm font-medium text-gray-500 hover:hover:text-[#831b94] hover:text-gray-700 disabled:pointer-events-none disabled:opacity-50 hs-tab-active:bg-white hs-tab-active:text-gray-700 dark:text-neutral-400 dark:hover:text-white dark:hs-tab-active:bg-gray-800 hs-tab-active:dark:text-neutral-400" id="segment-item-9" data-hs-tab="#segment-9" aria-controls="segment-9" role="tab">
                        Security
                    </button> --}}
                </nav>
            </div>
        </div>

        <div class="mt-3">
            <div id="segment-1" class="{{$active=='personal' ? 'active' : 'hidden'}}" role="tabpanel" aria-labelledby="segment-item-1">
                <div class="overflow-y-auto">
                    <!-- Card Section -->
                    <div class="mx-auto">
                        <!-- Card -->
                        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg border dark:border-neutral-500">
                            <div class="px-3 py-2 sm:px-4 flex justify-between">
                                <h3 class="text-xl font-semibold text-gray-800 dark:border-neutral-700 dark:text-white">
                                    Personal Details
                                </h3>
                                @if(in_array(getUserRole(), ['super-admin', 'hr']))
                                    <div class="inline-block hs-tooltip">
                                        <button type="button" onclick="editEmployeeForm('{{$employee->id}}', 'personal')" id="update-personal-details-button" class="btn-edit">
                                            <i class="ti ti-edit"></i>
                                            <span class="absolute z-10 invisible inline-block px-2 py-1 text-white transition-opacity bg-[#831b94] rounded-lg shadow-md opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible" role="tooltip">
                                                Edit Personal Details
                                            </span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div id="personal-details-wrap">
                                <div class="px-4 py-5 sm:p-0" id="personal-details-view-wrap">
                                    <dl class="sm:divide-y dark:sm:divide-neutral-500 grid grid-cols-1 sm:grid-cols-2">
                                        <div class="border-t dark:border-neutral-500 px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center text-sm !leading-none">
                                            <dt class="font-semibold text-gray-500 dark:text-neutral-200">
                                                Employee Name
                                            </dt>
                                            <dd class="mt-1 font-medium text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ $employee->full_name ?? 'N/A'}}
                                            </dd>
                                        </div>
                                        <div class="px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center text-sm !leading-none">
                                            <dt class="text-sm font-semibold dark:text-neutral-300 text-gray-500">
                                                Employee Id
                                            </dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ $employee->emp_id ?? 'N/A'}}
                                            </dd>
                                        </div>
                                        <div class="px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center">
                                            <dt class="text-sm font-semibold text-gray-500 dark:text-neutral-300">
                                                Email
                                            </dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ $employee->email ?? 'N/A'}}
                                            </dd>
                                        </div>
                                        <div class="px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center">
                                            <dt class="text-sm font-semibold text-gray-500 dark:text-neutral-300">
                                                Personal Email
                                            </dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ $employee->personal_email ?? 'N/A'}}
                                            </dd>
                                        </div>
                                        <div class="px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center">
                                            <dt class="text-sm font-semibold text-gray-500 dark:text-neutral-300">
                                                Duty Slot
                                            </dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ $employee->dutySlot->slot_name ?? 'N/A'}}
                                            </dd>
                                        </div>
                                        <div class="px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center">
                                            <dt class="text-sm font-semibold text-gray-500 dark:text-neutral-300">
                                                Blood Group
                                            </dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ $employee->blood_group ?? 'N/A'}}
                                            </dd>
                                        </div>
                                        <div class="px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center">
                                            <dt class="text-sm font-semibold text-gray-500 dark:text-neutral-300">
                                                Joining Date
                                            </dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ formatCarbonDate($employee->joining_date) ?? 'N/A'}}
                                            </dd>
                                        </div>
                                        <div class="px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center">
                                            <dt class="text-sm font-semibold text-gray-500 dark:text-neutral-300">
                                                Organization Name
                                            </dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ $employee->empOrganization->name ?? 'N/A'}}
                                            </dd>
                                        </div>
                                        <div class="px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center">
                                            <dt class="text-sm font-semibold text-gray-500 dark:text-neutral-300">
                                                Department Name
                                            </dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ $employee->empDepartment->name ?? 'N/A'}}
                                            </dd>
                                        </div>
                                        <div class="px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center">
                                            <dt class="text-sm font-semibold text-gray-500 dark:text-neutral-300">
                                                Designation
                                            </dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-800 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ $employee->designation ?  $designations->firstWhere('id', $employee->designation)->name ?? 'N/A' : 'N/A' }}
                                            </dd>
                                        </div>
                                        <div class="px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center">
                                            <dt class="text-sm font-semibold text-gray-500 dark:text-neutral-300">
                                                Contact No
                                            </dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ $employee->phone ?? 'N/A'}}
                                            </dd>
                                        </div>
                                        <div class="px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center">
                                            <dt class="text-sm font-semibold text-gray-500 dark:text-neutral-300">
                                                Alternative Contact No
                                            </dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ $employee->alternative_phone!='' ? $employee->alternative_phone : 'N/A'}}
                                            </dd>
                                        </div>
                                        <div class="px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center">
                                            <dt class="text-sm font-semibold text-gray-500 dark:text-neutral-300">
                                                Emergency Contact
                                            </dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ $employee->emergency_contact ?? 'N/A'}}
                                            </dd>
                                        </div>
                                        <div class="px-2.5 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 items-center">
                                            <dt class="text-sm font-semibold text-gray-500 dark:text-neutral-300">
                                                DOB
                                            </dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                                {{ $employee->birth_year ?? 'N/A'}}
                                            </dd>
                                        </div>
                                    </dl>

                                </div>
                                <div id="personal-details-edit-wrap" class="px-4 py-5 sm:p-0 hidden"></div>
                            </div>

                        </div>
                        <!-- End Card -->
                    </div>
                    <!-- End Card Section -->
                </div>
            </div>
            <div id="segment-2" class="{{$active=='address' ? 'active' : 'hidden'}}" role="tabpanel" aria-labelledby="segment-item-2">
                <div class="overflow-y-auto">
                    <!-- Card Section -->
                    <div class="mx-auto">
                        <!-- Card -->
                        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg border dark:border-neutral-500">
                            <div class="px-3 py-2 sm:px-4 flex justify-between">
                                <h3 class="text-xl font-semibold text-gray-800 dark:border-neutral-700 dark:text-white">
                                    Address Details
                                </h3>
                                @if(in_array(getUserRole(), ['super-admin', 'hr']))
                                    <div class="inline-block hs-tooltip">
                                        <button type="button"  onclick="editEmployeeForm('{{$employee->id}}', 'address')" id="update-info-button" class="btn-edit">
                                            <i class="ti ti-edit"></i>
                                            <span class="absolute z-10 invisible inline-block px-2 py-1 text-white transition-opacity bg-[#831b94] rounded-lg shadow-md opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible" role="tooltip">
                                            Edit Address Details
                                        </span>
                                        </button>
                                    </div>
                                @endif

                            </div>
                            <div class="px-4 py-5 sm:p-0" id="personal-details-wrap">
                                <div id="address-details-view-wrap" class="sm:divide-y dark:sm:divide-neutral-500 grid grid-cols-1 sm:grid-cols-2">
                                    <div class="border-t dark:border-neutral-500 py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-semibold text-gray-500 dark:text-neutral-200">
                                            Present Address
                                        </dt>
                                        <dd class="mt-1 text-sm font-medium  text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                            {{ $employee->present_address ?? 'N/A'}}
                                        </dd>
                                    </div>
                                    <div class="border-b dark:border-neutral-500 py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-semibold dark:text-neutral-300 text-gray-500">
                                            Parmanent Address
                                        </dt>
                                        <dd class="mt-1 text-sm font-medium  text-gray-900 dark:text-neutral-200 sm:mt-0 sm:col-span-2">
                                            {{ $employee->permanent_address ?? 'N/A'}}
                                        </dd>
                                    </div>

                                </div>
                                <div id="address-details-edit-wrap" class="px-4 py-5 sm:p-0 hidden"></div>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>
                    <!-- End Card Section -->
                </div>
            </div>
            <div id="segment-3" class="{{$active=='education' ? 'active' : 'hidden'}}" role="tabpanel" aria-labelledby="segment-item-3">
                <div class="overflow-y-auto">
                    <!-- Card Section -->
                    <div class="mx-auto">
                        <!-- Card -->

                        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg border dark:border-neutral-500" id="disable-card-segment-three">
                            <div class="px-3 py-2 sm:px-4 flex justify-between">
                                <h3 class="text-xl font-semibold text-gray-800 dark:border-neutral-700 dark:text-white">
                                    Education Details
                                </h3>
                                @if(in_array(getUserRole(), ['super-admin', 'hr']))
                                <div class="inline-block hs-tooltip">
                                    <button type="button" onclick="addEducationForm({{$employee->id}})" id="update-info-button" class="btn-edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-dotted" viewBox="0 0 16 16">
                                            <path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                        </svg>Add Education
                                    </button>
                                </div>
                                @endif
                            </div>
                            <div class="px-4 py-5 sm:p-0" id="education-details-view">
                                <dl class="sm:divide-y dark:sm:divide-neutral-500 grid grid-cols-1 sm:grid-cols-1">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                        @php $tableHeader=["S/N", "Degree", "Institute", "Department", "Passing Year", "Result"] @endphp
                                        <thead>
                                        <tr>
                                            @foreach($tableHeader as $item)
                                                <th scope="col" class="px-6 py-3 text-start text-sm font-medium text-neutral-700 uppercase dark:text-neutral-500">{{$item}}</th>
                                            @endforeach
                                            @if(in_array(getUserRole(), ['super-admin', 'hr']))
                                                <th scope="col" class="px-6 py-3 text-right text-sm font-medium text-neutral-700 uppercase dark:text-neutral-500">Action</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @if(isset($employee->empEducation))
                                            @if($employee->empEducation->count()>0)
                                                @foreach($employee->empEducation as $key=>$item)
                                                    <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100 dark:odd:bg-neutral-800 dark:even:bg-neutral-700 dark:hover:bg-neutral-700">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">{{$key+1}}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{$item->degree ?? ''}}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{$item->institution_name ?? ''}}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{$item->department ?? ''}}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{$item->passing_year ?? ''}}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{$item->result ?? ''}}</td>
                                                        @if(in_array(getUserRole(), ['super-admin', 'hr']))
                                                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                                <div class="inline-block hs-tooltip">
                                                                    <button onclick="deletePopup('Delete Education', '{{$item->degree ?? ''}}', '{{route('employee.delete.education', ['id'=>$item->id, 'active'=>'education'])}}')"
                                                                            type="button"
                                                                            class="btn-red">
                                                                        <i class="fa-regular fa-trash-can"></i>
                                                                        <span
                                                                            class="absolute z-10 invisible inline-block px-2 py-1 text-white transition-opacity bg-[#831b94] rounded-lg shadow-md opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible"
                                                                            role="tooltip">
                                                                            Delete
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        @endif
                                                    </tr>

                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7">
                                                        <p class="p-4 text-gray-400 text-center">Not Found!</p>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                        </tbody>
                                    </table>
                                </dl>
                            </div>
                            <div id="educationFormsWrap" class="p-4 hidden"></div>
                        </div>

                        <!-- End Card -->
                    </div>
                    <!-- End Card Section -->
                </div>
            </div>
            <div id="segment-4" class="{{$active=='documents' ? 'active' : 'hidden'}}" role="tabpanel" aria-labelledby="segment-item-4">
                <div class="overflow-y-auto">
                    <!-- Card Section -->
                    <div class="mx-auto">
                        <!-- Card -->
                        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg border dark:border-neutral-500" id="disable-card-segment-four">
                            <div class="px-3 py-2 sm:px-4 flex justify-between">
                                <h3 class="text-xl font-semibold text-gray-800 dark:border-neutral-700 dark:text-white">
                                    Documents
                                </h3>
                                @if(in_array(getUserRole(), ['super-admin', 'hr']))
                                    <div class="inline-block hs-tooltip">
                                        <button type="button" onclick="addDocumentForm({{$employee->id}})" id="update-info-button" class="btn-edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-dotted" viewBox="0 0 16 16">
                                                <path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                            </svg>Add Document
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div id="documentFormsWrap"></div>
                            <hr>
                            <div class="px-4 bg-white dark:bg-neutral-800" id="enable-card-segment-four">
                                @if(isset($employee->empDocuments))
                                    @if($employee->empDocuments->count()>0)
                                        <div class="grid grid-cols-4 gap-6 py-4">
                                            @foreach($employee->empDocuments as $key=>$item)
                                                <x-cards.document-card :data="$item" :employee="$employee"></x-cards.document-card>
                                            @endforeach
                                        </div>

                                    @else
                                        <p class="p-4 text-gray-400 text-center">Not Found!</p>
                                    @endif
                                @endif
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div id="segment-5" class="{{$active=='skills' ? 'active' : 'hidden'}}" role="tabpanel" aria-labelledby="segment-item-5">
                <div class="">
                    <!-- Card Section -->
                    <div class="mx-auto">
                        <!-- Card -->

                        <div class="bg-white dark:bg-neutral-800 shadow rounded-lg border dark:border-neutral-500" id="disable-card-segment-three">
                            <div class="px-3 py-2 sm:px-4 flex justify-between">
                                <h3 class="text-xl font-semibold text-gray-800 dark:border-neutral-700 dark:text-white">
                                    Skills Details
                                </h3>
                                @if(in_array(getUserRole(), ['super-admin', 'hr']))
                                    <div class="inline-block hs-tooltip">
                                        <button type="button" id="update-info-button" onclick="editSkills()" class="btn-edit">
                                            <i class="ti ti-edit"></i>
                                            <span class="absolute z-10 invisible inline-block px-2 py-1 text-white transition-opacity bg-[#831b94] rounded-lg shadow-md opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible" role="tooltip">
                                                Edit Skills
                                            </span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6 pt-0" id="skillsViewWrap">
                                @php
                                    $skills = explode(',', $employee->skills);
                                @endphp
                                <div class="font-semibold">
                                    <span class="font-semibold "></span> {{$employee->skills=="" ? "N/A" : ''}}
                                </div>
                                @if($employee->skills!="")
                                    <div class="relative flex flex-wrap justify-start items-center overflow-y-auto gap-2 pt-2" id="keywords">
                                        @foreach($skills as $item)
                                            <span class="px-2 py-1 bg-[#831b94] text-white font-semibold text-sm rounded-md">{{$item}}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="mx-6 py-5 sm:p-0 hidden" id="skillsEditWrap">
                                <form action="{{route('employees.update', ['id'=>$employee->id, 'a'=>'skills'])}}" method="post">
                                    @csrf
                                    <div class="w-1/2">
                                        <select id="skills" name="skills[]" class="" data-value="{{$employee->skills}}" placeholder="Select Skills..."></select>
                                    </div>
                                    <button type="submit" class="submit-button mb-6">
                                        Update
                                    </button>
                                    <button type="button" class="submit-button !bg-neutral-800 mb-6" onclick="cancelEditSkills()">
                                        Cancel
                                    </button>
                                </form>
                            </div>
                            <script>
                                function editSkills(){
                                    var initialValue = $('#skills').data('value');
                                    var initialValuesArray = initialValue !== '' ? initialValue.split(',') : [];
                                    var $select = $('#skills').selectize({
                                        plugins: ["auto_select_on_type", "restore_on_backspace", "remove_button", "clear_button"],
                                        delimiter: ",",
                                        persist: true,
                                        maxItems: 20,
                                        create: true,
                                        options:[
                                            { value: 'JavaScript', text: 'JavaScript' },
                                            { value: 'Python', text: 'Python' },
                                            { value: 'Java', text: 'Java' },
                                            { value: 'C#', text: 'C#' },
                                            { value: 'PHP', text: 'PHP' },
                                            { value: 'SQL', text: 'SQL' },
                                            { value: 'HTML/CSS', text: 'HTML/CSS' },
                                            { value: 'React', text: 'React' },
                                            { value: 'Angular', text: 'Angular' },
                                            { value: 'Node.js', text: 'Node.js' },
                                            { value: 'Ruby', text: 'Ruby' },
                                            { value: 'Swift', text: 'Swift' },
                                            { value: 'C++', text: 'C++' },
                                            { value: 'TypeScript', text: 'TypeScript' },
                                            { value: 'Vue.js', text: 'Vue.js' },
                                            { value: 'AWS', text: 'AWS' },
                                            { value: 'Docker', text: 'Docker' },
                                            { value: 'Kubernetes', text: 'Kubernetes' },
                                            { value: 'Git', text: 'Git' },
                                            { value: 'Jira', text: 'Jira' },
                                            { value: 'CI/CD', text: 'CI/CD' }
                                        ],
                                    });
                                    var selectizeInstance = $select[0].selectize;
                                    if(initialValuesArray.length > 0){
                                        for(var i=0; i<initialValuesArray.length; i++){
                                            selectizeInstance.addOption({text: initialValuesArray[i], value: initialValuesArray[i]});
                                            selectizeInstance.addItem(initialValuesArray[i]);
                                        }
                                    }
                                    $('#skillsViewWrap').addClass('hidden');
                                    $('#skillsEditWrap').removeClass('hidden');
                                }
                                function cancelEditSkills(){
                                    $('#skillsEditWrap').addClass('hidden');
                                    $('#skillsViewWrap').removeClass('hidden');
                                }

                            </script>
                        </div>

                        <!-- End Card -->
                    </div>
                    <!-- End Card Section -->
                </div>
            </div>
            <div id="segment-6" class="{{$active=='leave' ? 'active' : 'hidden'}}" role="tabpanel" aria-labelledby="segment-item-6">
                <div class="overflow-y-auto">
                    <!-- Card Section -->
                    <div class="mx-auto">
                        <!-- Card -->

                        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg border dark:border-neutral-500" id="disable-card-segment-three">
                            <div class="px-3 py-2 sm:px-4 flex justify-between">
                                <h3 class="text-xl font-semibold text-gray-800 dark:border-neutral-700 dark:text-white">
                                    Leave History
                                </h3>
                            </div>
                            <div id="">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr>
                                        <td class="px-3 py-2 whitespace-nowrap border bg-neutral-50 text-sm font-medium text-gray-800 dark:text-neutral-200">SL</td>
                                        <td class="px-3 py-2 whitespace-nowrap border bg-neutral-50 text-sm font-medium text-gray-800 dark:text-neutral-200">Year</td>
                                        @php $leaveTypes=\App\Models\LeaveType::get(); @endphp
                                        @foreach($leaveTypes as $item)
                                            <td class="px-3 py-2 whitespace-nowrap border bg-neutral-50 text-sm font-medium text-gray-800 dark:text-neutral-200 text-center">
                                                <span class="text-sm">{{$item->name}}</span> Leave <span class="text-xs italic">(<span class="text-teal-600 font-semibold text-sm">Remain</span>/Total)</span>
                                            </td>
                                        @endforeach
                                        <td class="px-3 py-2 whitespace-nowrap border bg-neutral-50 text-sm font-medium text-gray-800 dark:text-neutral-200 text-center">Balance</td>
                                    </tr>

                                    @php

                                        $initialYear = $employee->joining_date;
                                        $joiningDate = $joiningDate ? date('Y', strtotime($joiningDate)) : date('Y');
                                        $currentYear = date('Y');
                                        $sl = 1;
                                        $nonPaidLeave=[];
                                        $balance=0;
                                    @endphp

                                    @for($year = $currentYear; $year >= $joiningDate; $year--)
                                        @php
                                            $leaveBalances = $employee->leaveBalance($year);
                                            $balance=0;
                                        @endphp
                                        <tr>
                                            <td class="px-3 py-1 whitespace-nowrap border text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $sl }}</td>
                                            <td class="px-3 py-1 whitespace-nowrap border text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $year }}</td>
                                            @foreach($leaveTypes as $leaveType)
                                                @php
                                                    $leaveBalance = collect($leaveBalances)->firstWhere('id', $leaveType->id);
                                                    $allowance = $leaveBalance ? $leaveBalance['allowance'] : 0;
                                                    $taken = $leaveBalance ? $leaveBalance['taken'] : 0;
                                                    $remaining = $leaveBalance ? $leaveBalance['remaining'] : 0;
                                                @endphp
                                                @if(in_array($leaveType->id, [1,2,3]))
                                                    @php
                                                        $balance+= $remaining;
                                                    @endphp
                                                @else
                                                    @php
                                                        $nonPaidLeave=[
                                                            'allowance' => $allowance,
                                                            'taken' => $taken,
                                                            'remaining' => $remaining,
                                                        ];
                                                    @endphp
                                                @endif
                                                <td class="px-3 py-1 whitespace-nowrap border text-sm font-medium text-gray-800 dark:text-neutral-200 text-center">
                                                    <span class="text-xl  {{$remaining==0 ? 'text-[#831b94]' : 'text-teal-600'}}">{{ $remaining }}</span>/{{ $allowance}}
                                                </td>
                                            @endforeach
                                            <td class="px-3 py-1 whitespace-nowrap border text-sm font-medium text-gray-800 dark:text-neutral-200 text-center">
                                                <span class="text-xl  {{$balance==0 ? 'text-[#831b94]' : 'text-teal-600'}}">{{$balance}}</span>
                                            </td>
                                        </tr>
                                        @php $sl++; @endphp
                                    @endfor

                                </table>
                            </div>
                        </div>
                        @if(in_array(getUserRole(), ['super-admin', 'hr']))
                            <div class="mt-4 bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg border dark:border-neutral-500" id="disable-card-segment-three">
                                <div class="px-3 py-2 sm:px-4 flex justify-between">
                                    <h3 class="text-xl font-semibold text-gray-800 dark:border-neutral-700 dark:text-white">
                                        Manual Leave Balance
                                    </h3>
                                    <div class="inline-block hs-tooltip">
                                        <button type="button" onclick="editModalAjax('add-manual-leave-form', 'largeModal', '{{$employee->id}}')" class="actionBtn red">
                                            <i class="fa-solid fa-circle-plus"></i>
                                            <span class="absolute z-10 invisible inline-block px-2 py-1 text-white transition-opacity bg-[#831b94] rounded-lg shadow-md opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible" role="tooltip">
                                                Add Manual Leave Balance
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div id="">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap border bg-neutral-50 text-sm font-medium text-gray-800 dark:text-neutral-200">SL</td>
                                            <td class="px-3 py-2 whitespace-nowrap border bg-neutral-50 text-sm font-medium text-gray-800 dark:text-neutral-200">Year</td>
                                            <td class="px-3 py-2 whitespace-nowrap border bg-neutral-50 text-sm font-medium text-gray-800 dark:text-neutral-200">Casual Leave</td>
                                            <td class="px-3 py-2 whitespace-nowrap border bg-neutral-50 text-sm font-medium text-gray-800 dark:text-neutral-200">Sick Leave</td>
                                            <td class="px-3 py-2 whitespace-nowrap border bg-neutral-50 text-sm font-medium text-gray-800 dark:text-neutral-200">Annual Leave</td>
                                            <td class="px-3 py-2 whitespace-nowrap border bg-neutral-50 text-sm font-medium text-gray-800 dark:text-neutral-200">Total</td>
                                            <td class="px-3 py-2 whitespace-nowrap border bg-neutral-50 text-sm font-medium text-gray-800 dark:text-neutral-200">Remarks</td>
                                            <td class="px-3 py-2 whitespace-nowrap border bg-neutral-50 text-sm font-medium text-gray-800 dark:text-neutral-200 text-center">Action</td>
                                        </tr>
                                        @php
                                        $manualLeavs=\App\Models\LeaveDaysManual::where('emp_id', $employee->emp_id)->orderBy('year', 'desc')->get();
                                        @endphp
                                        @foreach($manualLeavs as $key=>$item)
                                            <tr>
                                                <td class="px-3 py-1 whitespace-nowrap border text-sm font-medium text-gray-800 dark:text-neutral-200">{{$key+1}}</td>
                                                <td class="px-3 py-1 whitespace-nowrap border text-sm font-medium text-gray-800 dark:text-neutral-200">{{$item->year}}</td>
                                                <td class="px-3 py-1 whitespace-nowrap border text-sm font-medium text-gray-800 dark:text-neutral-200">
                                                    {{$item->casual_leave}}
                                                </td>
                                                <td class="px-3 py-1 whitespace-nowrap border text-sm font-medium text-gray-800 dark:text-neutral-200">
                                                    {{$item->sick_leave}}
                                                </td>
                                                <td class="px-3 py-1 whitespace-nowrap border text-sm font-medium text-gray-800 dark:text-neutral-200">
                                                    {{$item->annual_leave}}
                                                </td>
                                                <td class="px-6 py-2 whitespace-nowrap border text-sm font-bold text-gray-800 dark:text-neutral-200">
                                                    {{$item->total}}
                                                </td>
                                                <td class="px-6 py-2 whitespace-nowrap border text-sm font-bold text-gray-800 dark:text-neutral-200">
                                                    {{$item->remarks}}
                                                </td>
                                                <td class="px-6 py-2 whitespace-nowrap border text-sm font-bold text-gray-800 dark:text-neutral-200">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <div class="inline-block tooltip"  data-tip="Edit Remarks">
                                                            <button type="button" onclick="editModalAjax('leave-manual-count-edit', 'largeModal', {{$item->id}})" class="actionBtn red">
                                                                <i class="ti ti-edit"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php $sl++; @endphp
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- End Card Section -->
                </div>
            </div>
            <div id="segment-7" class="{{$active=='biometric' ? 'active' : 'hidden'}}" role="tabpanel" aria-labelledby="segment-item-7">
                <div class="overflow-y-auto">
                    <!-- Card Section -->
                    <div class="mx-auto">
                        <!-- Card -->

                        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg border dark:border-neutral-500" id="disable-card-segment-three">
                            <div class="px-3 py-2 sm:px-4 flex justify-between">
                                <h3 class="text-xl font-semibold text-gray-800 dark:border-neutral-700 dark:text-white">
                                    Biometric Machine ID: {{$employee->biometric_id ?? 'N/A'}}
                                </h3>
                                @if(in_array(getUserRole(), ['super-admin', 'hr']))
                                    <div class="inline-block hs-tooltip">
                                        <button type="button" onclick="editBiometricForm()" id="update-info-button" class="btn-edit">
                                            <i class="ti ti-edit"></i>
                                            <span class="absolute z-10 invisible inline-block px-2 py-1 text-white transition-opacity bg-[#831b94] rounded-lg shadow-md opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible" role="tooltip">
                                                Edit Machine ID
                                            </span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div id="biometricFormsWrap"></div>
                        </div>

                        <!-- End Card -->
                    </div>
                    <!-- End Card Section -->
                </div>
            </div>
            <div id="segment-8" class="{{$active=='dsignature' ? 'active' : 'hidden'}}" role="tabpanel" aria-labelledby="segment-item-8">
                <div class="overflow-y-auto">
                    <!-- Card Section -->
                    <div class="mx-auto">
                        <!-- Card -->
                        @php
                            $defaultDSignature=asset('assets/img/No_signature.png');
                            $digital_signature=employeeDigitalSignature($employee->emp_id, $employee->digital_signature);
                        @endphp
                        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg border dark:border-neutral-500">
                            <div class="px-3 py-2 sm:px-4 flex justify-between">
                                <div class="flex flex-col gap-2 text-xl font-semibold text-gray-800 dark:border-neutral-700 dark:text-white">
                                    <span>Digital Signature {!! $employee->digital_signature ? '' : '(<span class="text-[#831b94] italic">Not Available</span>)' !!}</span>
                                    @if ($employee->digital_signature)
                                        <figure class="w-28 h-24 ">
                                            <img class="w-full h-full object-cover rounded-md ring-2 ring-white dark:ring-gray-800" src="{{$digital_signature}}" alt="Employee Digital Signature" onerror="this.onerror=null;this.src='{{$defaultDSignature}}';">
                                        </figure>
                                    @else
                                        <p class="font-semibold italic text-sm">Please Insert Your Digital Signature</p>
                                    @endif
                                </div>
                                @if(in_array(getUserRole(), ['employee']))
                                    <div class="inline-block hs-tooltip">
                                        <button type="button" onclick="editModalAjax('dsignature', 'smallModal', {{$employee->id}})" id="update-info-button" class="btn-edit">
                                            <i class="ti ti-edit"></i>
                                            <span class="absolute z-10 invisible inline-block px-2 py-1 text-white transition-opacity bg-[#831b94] rounded-lg shadow-md opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible" role="tooltip">
                                                Edit Digital Signature
                                            </span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- End Card -->
                    </div>
                    <!-- End Card Section -->
                </div>
            </div>
            <div id="segment-9" class="{{$active=='security' ? 'active' : 'hidden'}}" role="tabpanel" aria-labelledby="segment-item-9">
                <div class="overflow-y-auto">
                    <!-- Card Section -->
                    <div class="mx-auto">
                        <!-- Card -->

                        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg border dark:border-neutral-500" id="disable-card-segment-three">
                            <div class="px-3 py-2 sm:px-4 flex justify-between">
                                <h3 class="text-xl font-semibold text-gray-800 dark:border-neutral-700 dark:text-white">
                                    {{-- Biometric Machine ID: {{$employee->biometric_id ?? 'N/A'}} --}}
                                    Security Options
                                </h3>
                                <div class="inline-block hs-tooltip">
                                    <button type="button" onclick="editEmployeeForm('{{$employee->id}}', 'security')" id="update-info-button" class="btn-edit">
                                        <i class="ti ti-edit"></i>
                                        <span class="absolute z-10 invisible inline-block px-2 py-1 text-white transition-opacity bg-[#831b94] rounded-lg shadow-md opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible" role="tooltip">
                                            Edit Security Information
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div id="security-details-edit-wrap" class="px-4 py-5 sm:p-0 hidden"></div>
                        </div>

                        <!-- End Card -->
                    </div>
                    <!-- End Card Section -->
                </div>
            </div>
        </div>
    </x-containers.container-box>

    <div id="educationForm" class="hidden">
        <div class="border p-4 rounded-md">
            <form action="{{route('employees.update', ['id'=>$employee->id, 'a'=>'education'])}}" onsubmit="return validateEmployeeEditData('education')" method="post">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 lg:gap-6 mt-2">
                    <div>
                        <label for="institution_name_one" class="inputLabel">
                            Institution Name
                        </label>
                        <input id="institution_name_one" name="institution_name" type="text" class="institution_name inputField" placeholder="Institution name" >
                        <span class="error_msg" id="error_institution_name_one"></span>
                    </div>
                    <div>
                        <label for="degree_one" class="inputLabel">
                            Select Degree Type
                        </label>
                        @php
                            $empDegrees = $employee->empEducation->pluck('degree')->toArray(); // Get an array of degrees the employee has
                            $degrees = ["SSC", "HSC", "BSC", "MSC"];
                        @endphp

                        <select id="degree_one" name="degree" class="inputField">
                            <option value="" disabled selected>Select Degree</option>
                            @foreach($degrees as $degree)
                                <option value="{{ $degree }}" {{ in_array($degree, $empDegrees) ? 'disabled' : '' }}>{{ $degree }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div>
                        <label for="department_one" class="inputLabel">
                            Department / Subject Name
                        </label>
                        <input id="department_one" name="department" type="text" class="inputField" placeholder="Enter department/subject">
                        <span class="error_msg" id="error_department_one"></span>
                    </div>
                    <div>
                        <label for="passing_year_one" class="inputLabel">
                            Passing Year
                        </label>
                        <select id="passing_year_one" name="passing_year" type="number" class="inputField">
                            <option value="">Select Year</option>
                            @for($i=date('Y'); $i>=1960; $i--)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                        <span class="error_msg" id="error_passing_year_one"></span>
                    </div>
                    <div>
                        <div>
                            <label for="result_one" class="inputLabel">
                                Result
                            </label>
                            <input id="result_one" name="result" type="number" step="0.01" class="inputField" placeholder="Enter Result...">
                            <span class="error_msg" id="error_result_one"></span>
                        </div>
                    </div>
                </div>
                <div class="mt-5 flex items-center justify-end gap-x-2 border-t px-4 pt-3 dark:border-neutral-700">
                    <button type="button" onclick="removeInnerHtml('educationFormsWrap')" class="inline-flex items-center rounded-lg bg-black px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800" id="close-form-btn-for-education">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                        Add
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div id="addDocumentForm" class="hidden ">
        <div class="p-4">
            <form action="{{route('employees.update', ['id'=>$employee->id, 'a'=>'documents'])}}" onsubmit="return validateEmployeeEditData('document')" method="post" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 lg:gap-6 mt-2">
                    <div>
                        <label for="title" class="inputLabel">
                            Document Title
                        </label>
                        <input id="" name="title" type="text" class="inputField documentTitle" placeholder="Document Title">
                    </div>
                    <div>
                        <label for="" class="inputLabel">
                            Document Type
                        </label>
                        <select id="" name="document_type" class="inputField">
                            <option value="" disabled>Select Degree</option>
                            @foreach($documentType as $item)
                                <option value="{{$item->id}}">{{$item->name??''}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="" class="inputLabel">
                            Document Type
                        </label>
                        <label class="block">
                            <span class="sr-only">Choose File</span>
                            <input type="file" name="documentFile" class="block w-full text-sm text-gray-500
                                file:me-4 file:py-3 file:px-6
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-600 file:text-white
                                hover:file:bg-blue-700
                                file:disabled:opacity-50 file:disabled:pointer-events-none
                                dark:text-neutral-500
                                dark:file:bg-blue-500
                                dark:hover:file:bg-blue-400
                              ">
                        </label>
                    </div>

                </div>
                <div class="mt-5 flex items-center justify-end gap-x-2 border-t px-4 py-3 dark:border-neutral-700">
                    <button type="button" onclick="removeInnerHtml('documentFormsWrap')" class="inline-flex items-center rounded-lg bg-black px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800" id="close-form-btn-for-document">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="biometricForm" class="hidden ">
        <div class="p-4">
            <form action="{{route('employees.update', ['id'=>$employee->id, 'a'=>'biometric'])}}" onsubmit="return validateEmployeeEditData('biometric')" method="post" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 lg:gap-6 mt-2">
                    <div>
                        <label for="title" class="inputLabel">
                            Biometric ID
                        </label>
                        <input id="" name="biometric_id" value="{{$employee->biometric_id}}" type="text" class="inputField biometric_id" placeholder="1234">
                    </div>
                </div>
                <div class="mt-5 flex items-center justify-end gap-x-2 border-t px-4 py-3 dark:border-neutral-700">
                    <button type="button" onclick="removeInnerHtml('biometricFormsWrap')" class="inline-flex items-center rounded-lg bg-black px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800" id="close-form-btn-for-biometric">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
/* Profile photo change */
function editEmployeeProfilePic() {
    $("#profilePic").hide();
    $("#profilePicEdit").show();
}

function cancelEmployeeProfilePicEdit() {
    const fileInput = document.getElementById('file');
    fileInput.value = ''; // Reset file input
    const image = document.getElementById("output");
    image.src = "{{ employeeProfileImage($employee->id, $employee->profile_photo) }}";
    image.onerror = function() {
        this.onerror = null;
        this.src = "{{employeeDefaultProfileImage($employee->gender)}}";
    };
    $("#profilePicEdit").hide();
    $("#profilePic").show();
}

var loadFile = function(event) {
    const image = document.getElementById("output");
    const file = event.target.files[0];
    const fileType = file.type.toLowerCase();

    // Check if the selected file is an image
    if (fileType.startsWith('image/')) {
        image.src = URL.createObjectURL(file);
    } else {
        // Reset file input and show error message
        const fileInput = document.getElementById('file');
        fileInput.value = '';
        toastr.error('Please select a valid image file.');
    }
};
/* End of Profile photo change */


/* // For personal details
const sectionIDBasicInfo1 = document.getElementById('disable-card');
const sectionIDBasicInfo2 = document.getElementById('edit-card');
const editBtnBasicInfo1 = document.getElementById('update-info-button');
const closeBtnBasicInfo2 = document.getElementById('close-form-btn');


editBtnBasicInfo1.addEventListener('click', () => {
    sectionIDBasicInfo1.style.display = 'none'
    sectionIDBasicInfo2.style.display = 'block'
    $("input").prop("disabled", false);
    $("select").prop("disabled", false);
    $("option").prop("disabled", false);
})
closeBtnBasicInfo2.addEventListener('click', ()=> {
    sectionIDBasicInfo2.style.display = 'none'
    sectionIDBasicInfo1.style.display = 'block'
    $("input").prop("disabled", true);
    $("select").prop("disabled", true);
    $("option").prop("disabled", true);
})
// End personal Details

// For Address
const sectionIDAddress1 = document.getElementById('disable-card-segment-two');
const sectionIDAddress2 = document.getElementById('enable-card-segment-two');
const editBtnAddress1 = document.getElementById('update-info-button-segment-two');
const closeBtnAddress2 = document.getElementById('close-form-btn-for-address');
sectionIDAddress2.style.display = 'none';


editBtnAddress1.addEventListener('click', () => {
    sectionIDAddress1.style.display = 'none'
    sectionIDAddress2.style.display = 'block'
})
closeBtnAddress2.addEventListener('click', ()=> {
    sectionIDAddress2.style.display = 'none'
    sectionIDAddress1.style.display = 'block'
})
// End Address



// For personal details
const sectionIDEducationInfo1 = document.getElementById('disable-card-segment-three');
const sectionIDEducationInfo2 = document.getElementById('enable-card-segment-three');
const editBtnEducationInfo1 = document.getElementById('update-info-button-segment-three');
const closeBtnEducationInfo2 = document.getElementById('close-form-btn-for-education');
sectionIDEducationInfo2.style.display = 'none';


editBtnEducationInfo1.addEventListener('click', () => {
    sectionIDEducationInfo1.style.display = 'none'
    sectionIDEducationInfo2.style.display = 'block'
    $("input").prop("disabled", false);
    $("select").prop("disabled", false);
    $("option").prop("disabled", false);
})
closeBtnEducationInfo2.addEventListener('click', ()=> {
    sectionIDEducationInfo2.style.display = 'none'
    sectionIDEducationInfo1.style.display = 'block'
    $("input").prop("disabled", true);
    $("select").prop("disabled", true);
    $("option").prop("disabled", true);
})
// End personal Details

// For Documents
const sectionIDDocuments1 = document.getElementById('disable-card-segment-four');
const sectionIDDocuments2 = document.getElementById('enable-card-segment-four');
const editBtnDocuments1 = document.getElementById('update-info-button-segment-four');
const closeBtnDocuments2 = document.getElementById('close-form-btn-for-documets-four');
sectionIDDocuments2.style.display = 'none';


editBtnDocuments1.addEventListener('click', () => {
    sectionIDDocuments1.style.display = 'none'
    sectionIDDocuments2.style.display = 'block'
})
closeBtnDocuments2.addEventListener('click', ()=> {
    sectionIDDocuments2.style.display = 'none'
    sectionIDDocuments1.style.display = 'block'
})
// End Documents */
</script>

@endsection
