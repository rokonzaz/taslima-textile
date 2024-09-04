
    <form action="{{ route('holiday.update', ['id'=>$holiday->id]) }}" method="POST" class="mb-0" enctype="multipart/form-data" onsubmit="return validateFormRequest('e_', 'holiday')">
        @csrf
        <div class="p-4 overflow-y-scroll max-h-[40vh]">
            <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="holiday_name" class="inputLabel">
                        Holiday Name <span class="text-[#831b94]">*</span>
                    </label>
                    <input value="{{$holiday->name}}" type="text" id="e_holiday_name" name="holiday_name" class="inputField" placeholder="Eid Holiday">
                    <span class="error_msg" id="e_error_holiday_name"></span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="start_date" class="inputLabel">
                        Start Date <span class="text-[#831b94]">*</span>
                    </label>
                    <input type="date" id="e_start_date" onchange="calculateLeaveDays('e_','holiday')" name="start_date" class="inputField" value="{{$holiday->start_date}}">
                    <span class="error_msg" id="e_error_start_date"></span>
                </div>
                <div>
                    <label for="end_date" class="inputLabel">
                        End Date <span class="text-[#831b94]">*</span>
                    </label>
                    <input type="date" id="e_end_date" onchange="calculateLeaveDays('e_','holiday')" name="end_date" class="inputField" value="{{$holiday->end_date}}">
                    <span class="error_msg" id="e_error_end_date"></span>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="total_days" class="inputLabel">
                        Total Days
                    </label>
                    <span id="e_total_days" class="text-[#831b94] italic font-medium">({{ $holiday->total_days < 10 ? '0' . $holiday->total_days : $holiday->total_days }})</span>

                </div>
            </div>
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
            <button type="button" onclick="smallModal.close()" class="cancel-button">
                Cancel
            </button>
            <button type="submit" class="submit-button">
                Save
            </button>
        </div>
    </form>

