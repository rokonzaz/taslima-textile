<x-modals.small-modal id="createHolidayModal" title="Create Holiday">
    <form action="{{ route('holiday.store') }}" method="POST" class="mb-0" enctype="multipart/form-data" onsubmit="return validateFormRequest('c_', 'holiday')">
        @csrf
        <div class="p-4 overflow-y-scroll max-h-[40vh]">
            <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="holiday_name" class="inputLabel">
                        Holiday Name <span class="text-[#831b94]">*</span>
                    </label>
                    <input type="text" id="c_holiday_name" name="holiday_name" class="inputField" placeholder="Holiday name...">
                    <span class="error_msg" id="c_error_holiday_name"></span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="start_date" class="inputLabel">
                        Start Date <span class="text-[#831b94]">*</span>
                    </label>
                    <input type="date" id="c_start_date" name="start_date" value="{{date('Y-m-d')}}" onchange="calculateLeaveDays('c_','holiday')" class="inputField">
                    <span class="error_msg" id="c_error_start_date"></span>
                </div>
                <div>
                    <label for="end_date" class="inputLabel">
                        End Date <span class="text-[#831b94]">*</span>
                    </label>
                    <input type="date" id="c_end_date" name="end_date" value="{{date('Y-m-d')}}"  onchange="calculateLeaveDays('c_','holiday')" class="inputField">
                    <span class="error_msg" id="c_error_end_date"></span>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="total_days" class="inputLabel">
                        Total Days
                    </label>
                    <span id="c_total_days" class="text-[#831b94] italic font-medium"></span>

                </div>
            </div>
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
            <button type="button" onclick="createHolidayModal.close()" class="cancel-button">
                Cancel
            </button>
            <button type="submit" class="submit-button">
                Save
            </button>
        </div>
    </form>
</x-modals.small-modal>

