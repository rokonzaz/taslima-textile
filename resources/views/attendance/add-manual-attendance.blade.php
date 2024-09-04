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
        <div class="flex items-center justify-between">
            <label for="" class="inputLabel">
                <i class="fa-regular fa-calendar-days"></i> Date: {{date('d M Y', strtotime($date))}}
            </label>
            <label for="" class="inputLabel">
                    <i class="fa-regular fa-clock ml-2"></i>

                    Entry:
                    @if($clockData['clockIn']!='')
                        {{date('h:ia', strtotime($clockData['clockIn']))}}
                    @else
                        N/A
                    @endif
                <i class="ti ti-line-dotted"></i>
                     Exit:
                    @if($clockData['clockOut']!='')
                        {{date('h:ia', strtotime($clockData['clockOut']))}}
                    @else
                        N/A
                    @endif

            </label>
        </div>
    </div>
    <hr class="mb-4">

    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 lg:gap-y-1 lg:gap-x-6" >
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
        <div class="">
            <label for="dutySlotRules" class="inputLabel">Time</label>
            <input type="time" id="time" name="time" value={{date('H:i')}} class="inputField">
            <span class="error_msg" id="error_time"></span>
        </div>
        <div class="">
            <label for="" class="inputLabel">Reason</label>
            <textarea id="finger_note" name="finger_note" class="inputField" rows="2" placeholder="Please tell us your reason."></textarea>
            <span class="error_msg" id="error_finger_note"></span>
        </div>
    </div>

</div>
<div class="sticky bottom-0 z-50 bg-gray-100">
    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
        <button type="button" onclick="smallModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
            Cancel
        </button>
        <button type="button" id="createEmployeeAttendanceDetailsSubmitButton" onclick="createEmployeeAttendanceDetails('{{$employee->emp_id}}', '{{$date}}','add-manual-attendance')" class="submit-button">
            Save
        </button>
    </div>
</div>
