<x-modals.large-modal id="createDutySlotModal" title="Create Duty Slot">
    <form class="mb-0" action="{{ route('dutySlots.store') }}" method="POST" class="mb-0" enctype="multipart/form-data" onsubmit="return validateDutySlot()">
        @csrf
        <div class="p-4 overflow-y-scroll max-h-[45vh]">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="employeeOrganization" class="inputLabel">
                        Slot Name
                    </label>
                    <input type="text" id="slot_name" name="slot_name" onkeyup="validateutySlotSingleData('slot_name', 'error_slot_name')" class="inputField" placeholder="Reqular-1">
                    <span class="error_msg" id="error_slot_name"></span>
                    <input type="hidden" id="isValidate_slot_name" value="0">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="" class="inputLabel">
                        Start Time
                    </label>
                    <input type="time" id="start_time" name="start_time" class="inputField">
                    <span class="error_msg" id="error_start_time"></span>
                </div>
                <div>
                    <label for="" class="inputLabel">
                        Threshold Time
                    </label>
                    <input type="time" id="threshold_time" name="threshold_time" class="inputField">
                    <span class="error_msg" id="error_threshold_time"></span>
                </div>
                <div>
                    <label for="" class="inputLabel">
                        End Time
                    </label>
                    <input type="time" id="end_time" name="end_time" class="inputField">
                    <span class="error_msg" id="error_end_time"></span>
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100">
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="createEmployeeModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    Cancel
                </button>
                <button type="submit" onsubmit="return validateDutySlot()" class="submit-button">
                    Save
                </button>
            </div>
        </div>
    </form>
</x-modals.large-modal>


