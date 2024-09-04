<div class="p-4 overflow-y-scroll max-h-[55vh]">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
        <div class="flex items-center gap-3">
            <div class="">
                @php
                    $defaultProfileImage=employeeDefaultProfileImage($employee->gender);
                    $profile_img=employeeProfileImage($employee->emp_id, $employee->profile_photo);
                @endphp
                <figure class="w-12 aspect-square rounded-full overflow-hidden">
                    <img class="w-full h-full object-cover" src="{{$profile_img}}" onerror="this.onerror=null;this.src='{{$defaultProfileImage}}';" alt="{{$employee->name ??''}}"/>
                </figure>
            </div>
            <div class="flex flex-col">
                <span class="font-bold">{{$employee->full_name ?? ''}}</span>
                <span>{{$employee->email ?? ''}}</span>
                <span>{{$employee->phone ?? ''}}</span>
            </div>
        </div>
    </div>

    <hr class="w-full mt-4">
    <div class="my-2 text-base">
        <input id="date" type="hidden" value="{{$date}}" class="inputField" readonly>
        <label for="" class="inputLabel"><i class="fa-regular fa-calendar-days"></i> Date: {{date('d M Y', strtotime($date))}}</label>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-y-1 lg:gap-x-6 mt-2">
        <div class="col-span-2">
            <label for="reason" class="inputLabel">Select a Reason</label>
            <select id="reason" name="reason" class="inputField">
                <option value="">Select</option>
                @foreach($reasons as $item)
                    <option value="{{$item->name}}">{{$item->name}}</option>
                @endforeach
            </select>
            <span class="error_msg" id="error_reason"></span>
        </div>
        <div class="col-span-2">
            <label for="additional_note" class="inputLabel">Additional Note</label>
            <textarea id="additional_note" name="additional_note" class="inputField" rows="2" placeholder="Additional Note..."></textarea>
            <span class="error_msg" id="error_additional_note"></span>
        </div>
        <div>
            <label for="dutySlotRules" class="inputLabel">
                Start Time
            </label>
            <input type="time" id="start_time" name="start_time" value="{{date('H:i')}}" class="inputField">
            <span class="error_msg" id="error_start_time"></span>
        </div>
        <div>
            <label for="dutySlotRules" class="inputLabel">
                End Time
            </label>
            <input type="time" id="end_time" name="end_time" value="{{date('H:i')}}" class="inputField">
            <span class="error_msg" id="error_end_time"></span>
        </div>
    </div>
    {{-- <hr class="my-4">
    <div class="flex items-center">
        <input type="checkbox"  id="toggleAttendanceButton" class="relative shrink-0 w-[3.25rem] h-7 p-px bg-gray-100 border-transparent text-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-red-600 disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:text-[#831b94] checked:border-red-600 focus:checked:border-red-600 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-600 before:inline-block before:size-6 before:bg-white checked:before:bg-red-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-400 dark:checked:before:bg-red-200">
        <label for="toggleAttendanceButton" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Add an Attendance</label>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-y-1 lg:gap-x-6 mt-2" id="attendaceWrap" style="display: none">
        <div class="">
            <label for="leave_typeX" class="inputLabel">
                Attendance Type <span class="text-[#831b94]">*</span>
            </label>

            <ul class="flex flex-col sm:flex-row" id="c_leave_type">
                <li class="inline-flex items-center gap-x-2 py-3 px-2 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                        <div class="flex items-center h-5">
                            <input id="hs-horizontal-list-group-item-radio-1" name="attendance_type" value="entry-time" type="radio" checked class="border-gray-200 rounded-full disabled:opacity-50 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                        </div>
                        <label for="hs-horizontal-list-group-item-radio-1" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-500 cursor-pointer">
                            Entry Time
                        </label>
                    </div>
                </li>
                <li class="inline-flex items-center gap-x-2 py-3 px-2 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <div class="relative flex items-start w-full">
                        <div class="flex items-center h-5">
                            <input id="hs-horizontal-list-group-item-radio-2" name="attendance_type" value="exit-time" type="radio" class="border-gray-200 rounded-full disabled:opacity-50 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                        </div>
                        <label for="hs-horizontal-list-group-item-radio-2" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-500 cursor-pointer">
                            Exit Time
                        </label>
                    </div>
                </li>
            </ul>
        </div>
        <div>
            <label for="dutySlotRules" class="inputLabel">
                Time
            </label>
            <input type="time" id="time" name="time" class="inputField">
            <span class="error_msg" id="error_time"></span>
        </div>
        <div class="col-span-3"></div>
        <div class="">
            <label for="" class="inputLabel">Reason</label>
            <input id="finger_note" name="finger_note" class="inputField">
            <span class="error_msg" id="error_finger_note"></span>
        </div>
    </div> --}}

</div>
<div class="sticky bottom-0 z-50 bg-gray-100">
    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
        <button type="button" onclick="smallModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
            Cancel
        </button>
        <button type="button" id="createEmployeeAttendanceDetailsSubmitButton" onclick="createEmployeeAttendanceDetails('{{$employee->emp_id}}', '{{$date}}','attendance-details')" class="submit-button">
            Save
        </button>
    </div>
</div>

{{-- <script>
    $('#toggleAttendanceButton').click(function (){
        console.log(13)
        $('#attendaceWrap').toggle()
    })
</script> --}}
