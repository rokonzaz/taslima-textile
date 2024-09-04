    <form action="{{ route('dutySlots.update', ['id'=>$dutySlot->id]) }}" method="POST" class="mb-0" enctype="multipart/form-data" onsubmit="return validateDutySlot('edit')">
        @csrf
        <div class="p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="" class="inputLabel">
                        Slot Name
                    </label>
                    <input type="text" id="edit_slot_name" name="slot_name" value="{{$dutySlot->slot_name}}" onkeyup="validateutySlotSingleData('slot_name', 'error_slot_name')" class="inputField" placeholder="Reqular-1">
                    <span class="error_msg" id="edit_error_slot_name"></span>
                    <input type="hidden" id="edit_isValidate_slot_name" value="1">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="" class="inputLabel">
                        Start Time
                    </label>
                    <input type="time" id="edit_start_time" name="start_time" value="{{ date('H:i', strtotime($dutySlot->start_time)) }}" class="inputField">
                    <span class="error_msg" id="edit_error_start_time"></span>
                </div>
                <div>
                    <label for="" class="inputLabel">
                        Threshold Time
                    </label>
                    <input type="time" id="edit_threshold_time" name="threshold_time" value="{{ date('H:i', strtotime($dutySlot->threshold_time)) }}" class="inputField">
                    <span class="error_msg" id="edit_error_threshold_time"></span>
                </div>
                <div>
                    <label for="" class="inputLabel">
                        End Time
                    </label>
                    <input type="time" id="edit_end_time" name="end_time" value="{{ date('H:i', strtotime($dutySlot->end_time)) }}" class="inputField">
                    <span class="error_msg" id="edit_error_end_time"></span>
                </div>
            </div>
        </div>
        <div class="">
            <div class="mt-5 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="createEmployeeModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    Cancel
                </button>
                <button type="submit" onsubmit="return validateDutySlot('edit')" class="submit-button">
                    Save
                </button>
            </div>
        </div>
    </form>


