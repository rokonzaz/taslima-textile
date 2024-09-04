<input type="hidden" id="selectedTeam" value="{{$team->id}}">
<x-containers.container-box data="ml-4">
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
        {{--<div class=""> Total Members: <span class="text-[#831b94] font-medium" id="teamMemberCount">0</span></div>--}}
        <p class="block mb-1.5 text-sm text-gray-500 dark:text-neutral-400">{{ $team->teamOrganization->name ?? '' }} ({{ $team->teamDepartment->name ?? '' }})</p>
    </div>
</x-containers.container-box>
<div class="grid grid-cols-7 divide-x">
    <div class="pl-4 col-span-5">
        @if(userCan('team.edit'))
            <div class="pt-3 mr-3 mt-2 relative bg-red-50/30">
                <div id="dropbox" class="absolute z-10 w-full top-0 left-0 h-32  py-6 text-center border-[2px] rounded-md text-gray-400 border-red-600 border-dashed"></div>
                <div class="py-6 flex flex-col items-center justify-center gap-2 ">
                    <span class="text-4xl text-neutral-500 z-0"><i class="fa-solid fa-users"></i></span>
                    <p class="m-0 text-neutral-400 text-lg z-0">Drag Your Employees Here...</p>
                </div>

            </div>
        @endif
        <div class="h-[calc(100vh_-_310px)] overflow-y-scroll">
            <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                @if(isset($team->teamMember))
                    @if($team->teamMember->count()>0)
                        @foreach($team->teamMember as $item)
                            <div class="group bg-white border border-gray-200 rounded-md dark:bg-neutral-800 dark:dark:border-neutral-700 relative duration-200">
                                <div class="absolute top-0 right-0 flex justify-end p-2">
                                    @if(userCan('team.edit'))
                                        <button type="button" onclick="removeEmployeeModal({{$team->id}}, '{{$item->emp_id}}',  '{{$item->employee->full_name}}', )"  class="text-transparent group-hover:text-[#831b94] size-6 aspect-square hover:bg-red-100 rounded-full hover:text-[#831b94]">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    @endif
                                </div>

                                <div class="">
                                    <div class="flex items-center justify-between px-2 py-1">
                                        <div class="flex items-center gap-2">
                                        </div>
                                    </div>

                                    <div class="px-2 py-1 text-center">
                                        <img class="size-10 aspect-square rounded-full shadow-md mx-auto mb-1" src="{{employeeProfileImage($item->employee->emp_id ?? '', $item->employee->profile_photo ?? '')}}" onerror="this.onerror=null;this.src='{{employeeDefaultProfileImage($item->employee->profile_photo ?? 'Male')}}';"/>
                                        <a href="@if(userCan('employee.view')) {{route('employees.view', ['id'=>$item->employee->emp_id])}} @endif" class="">{{ $item->employee->emp_id ?? 'N/A' }}</a>
                                        <h5 class="w-full truncate mb-1 font-medium text-gray-900 dark:text-white">{{ $item->employee->full_name ?? '' }}</h5>
                                        <div class="w-full truncate text-xs text-gray-500 dark:text-gray-400">{{ $item->employee->email ?? '' }}</div>
                                    </div>

                                </div>
                            </div>

                        @endforeach
                    @else
                        <div class="col-span-3 w-full py-10 text-lg">
                            <p class="text-center">No Employee Found!</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>


    </div>
    <div class="py-2 col-span-2 bg-neutral-50/50">
        @if(isset($employee))
            <div class="text-lg flex justify-between items-center px-3">
                <div>
                    Employees
                    @if($employee->count()>0)
                        <span class="text-teal-600">({{$employee->count()}})</span>
                    @else
                        <span class="text-[#831b94]">(0)</span>
                    @endif
                </div>
                <span class="text-xs italic font-medium text-[#831b94]">(Click Employee to Select)</span>
            </div>
            <hr class="mt-2 mb-2">
            <div class="" id="employeeList">
                <div class="w-full my-2 pl-3  pr-2 flex items-center gap-2">
                    <input class="search inputField !bg-white" placeholder="Search" />
                    <button class="sort button-outline !mb-3 text-2xl hover:text-[#831b94]" data-sort="name"><i class="ti ti-sort-ascending-letters"></i></button>
                </div>
                <div class="h-[calc(100vh_-_285px)] overflow-y-scroll">
                    <div class="list grid grid-cols-1 gap-y-4 pl-3 pr-1 ">
                        @if($employee->count()>0)
                            @foreach($employee as $item)
                                <div class="draggable border rounded-md shadow-lg shadow-neutral-100 bg-white overflow-hidden relative group min-h-20" @if(userCan('team.edit')) draggable="true"  data-id="{{$item->emp_id}}" @endif>
                                    @php $checkId="emp_checkbox_{$item->emp_id}"; @endphp
                                    <input id="{{$checkId}}" name="emp_id[]" value="{{$item->emp_id}}" type="checkbox" class="peer/emp hidden border-gray-200 rounded text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800" aria-describedby="hs-checkbox-delete-description">
                                    <div class="peer-checked/emp:bg-[#831b94] peer-checked/emp:text-white px-2 py-1 flex items-center h-full ">
                                        {{--<button onclick="employeeToTeamAction({{$team->id}}, {{$item->id}}, 'add-employee')" class="px-1.5 bg-[#831b94] h-full flex items-center justify-center text-white cursor-pointer">
                                            <i class="fa-regular fa-circle-left"></i>
                                        </button>--}}
                                        <label for="emp_checkbox_{{$item->emp_id}}" class="h-full flex items-center gap-2 ">
                                            @php
                                                $defaultProfileImage=employeeDefaultProfileImage($item->gender);
                                                $profile_img=employeeProfileImage($item->emp_id, $item->profile_photo);
                                            @endphp
                                            <figure class="w-8 aspect-square object-cover rounded-full overflow-hidden">
                                                <img class="w-full h-full object-cover" src="{{$profile_img}}" onerror="this.onerror=null;this.src='{{$defaultProfileImage}}';"/>
                                            </figure>
                                            <div class="flex flex-col justify-center truncate">
                                                <span class="name font-bold  truncate">{{$item->full_name ?? ''}}</span>
                                                <span class="email truncate">{{$item->email ? $item->email : $item->personal_email}}</span>
                                                <span class="phone truncate">{{$item->phone ?? ''}}</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center">No Employee Exist!</p>
                        @endif
                    </div>
                </div>

            </div>
        @endif
    </div>


</div>

<script>

    var options = {
        valueNames: [ 'name', 'email', 'phone' ]
    };

    var employeeList = new List('employeeList', options);


    $(function () {
        $('#dropbox').on('dragenter', function(event) {
            $(this).addClass('animate-pulse !border-[3px]');
            event.preventDefault();
        });

        // Remove the class when dragging leaves the dropbox area
        $('#dropbox').on('dragleave', function(event) {
            $(this).removeClass('animate-pulse !border-[3px]');
            event.preventDefault();
        });
        $draggedId='';
        $('.draggable').on('dragstart', function(event) {
            $draggedId=$(this).attr('data-id');
        });

        $('#dropbox').on('drop', function(event) {
            event.preventDefault();
            var checkedValues = $('input[name="emp_id[]"]:checked').map(function() {
                return $(this).val();
            }).get();
            if(checkedValues.length>0){
                employeeToTeamAction($('#selectedTeam').val(), checkedValues, 'add-bulk-employee');
            }
            else {
                if($draggedId!=''){
                    employeeToTeamAction($('#selectedTeam').val(), $draggedId, 'add-employee');
                }
            }
        });

        // Prevent default behavior for dragover event to allow drop
        $('#dropbox').on('dragover', function(event) {
            event.preventDefault();
        });
    })

    // Add the class when dragging enters the dropbox area

</script>
