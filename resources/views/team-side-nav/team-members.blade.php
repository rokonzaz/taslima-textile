<input type="hidden" id="selectedTeam" value="{{$team->id}}">
<div class="grid grid-cols-7 divide-x">
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
            <div class="h-[calc(100vh_-_6.5rem)] overflow-y-scroll" id="employeeList">
                <div class="w-full my-2 pl-3  pr-2 flex items-center gap-2">
                    <input class="search inputField !bg-white" placeholder="Search" />
                    <button class="sort button-outline !mb-3 text-2xl hover:text-[#831b94]" data-sort="name"><i class="ti ti-sort-ascending-letters"></i></button>
                </div>
                <div class="list grid grid-cols-1 gap-y-4 pl-3 pr-1">
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
                                            <span class="email truncate">{{$item->email ?? ''}}</span>
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
        @endif
    </div>
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
        <div class="h-[calc(100vh_-_3.5rem)] overflow-y-scroll">
            <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                @if(isset($team->teamMember))
                    @if($team->teamMember->count()>0)
                        @foreach($team->teamMember as $item)
                            <div class="bg-white border border-gray-200 rounded-md dark:bg-neutral-800 dark:dark:border-neutral-700">
                                <div class="flex justify-end p-2">
                                    @if(userCan('team.edit'))
                                        <div class="dropdown dropdown-end">
                                            <div tabindex="0" role="button" class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </div>
                                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-40">
                                                <li>
                                                    <button  onclick="employeeToTeamAction({{$team->id}}, '{{$item->emp_id}}', 'remove-employee')" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                        Remove
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-col items-center pb-4">
                                    <img class="w-24 aspect-square mb-3 rounded-full shadow-lg" src="{{employeeProfileImage($item->employee->emp_id ?? '', $item->employee->profile_photo ?? '')}}" onerror="this.onerror=null;this.src='{{employeeDefaultProfileImage($item->employee->profile_photo ?? 'Male')}}';"/>
                                    <h5 class="mb-1 font-medium text-gray-900 dark:text-white">{{ $item->employee->full_name ?? '' }}</h5>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item->employee->email ?? '' }}</span>
                                    <div class="flex mt-2 md:mt-3">
                                        <a href="@if(userCan('employee.view')) {{route('employees.view', ['id'=>$item->employee->emp_id])}} @endif" class="button-light">{{ $item->employee->emp_id ?? 'N/A' }}</a>
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
