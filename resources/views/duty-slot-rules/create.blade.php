<dialog id="createDutySlotRuleModal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl p-0 relative border border-neutral-200 overflow-hidden">
        <div class="flex justify-between items-center border-b p-2 pl-4 sticky top-0 z-50 bg-gray-100">
            <div id="createEmployeeModalTitle" class="font-bold text-lg">Create Duty Slot Rule</div>
            <div class="">
                <div class="modal-action mt-0">
                    <form method="dialog">
                        <button class="w-9 aspect-square rounded-full bg-neutral-50 text-white bg-[#831b94] hover:bg-red-700 hover:shadow-lg hover:text-white duration-200"><i class="fa-solid fa-xmark"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <div id="createDutySlotRuleModalBody">
            <form class="mb-0" action="{{ route('dutySlotRules.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateDutySlotRules()">
                @csrf
                <div class="p-4 overflow-y-scroll max-h-[45vh]">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                        <div>
                            <label for="dutySlotRules" class="inputLabel">
                                Select A Slot Name
                            </label>
                            <select id="duty_slot_rules" name="duty_slot_id" class="inputField">
                                @if(isset($dutySlots))
                                    <option value="" disabled selected>Select A Slot Name</option>
                                    @foreach ($dutySlots as $item)
                                        <option value="{{ $item->id }}">{{ $item->slot_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="error_msg" id="error_slot_name"></span>
                        </div>
                        <div>
                            <label for="title" class="inputLabel">
                                Title
                            </label>
                            <input type="text" id="title" name="title" class="inputField">
                            <span class="error_msg" id="error_title"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                        <div>
                            <label for="dutySlotRules" class="inputLabel">
                                Start Date
                            </label>
                            <input type="date" id="start_date" name="start_date" value="{{date('Y-m-d')}}" class="inputField">
                            <span class="error_msg" id="error_start_date"></span>
                        </div>
                        <div>
                            <label for="dutySlotRules" class="inputLabel">
                                End Date
                            </label>
                            <input type="date" id="end_date" name="end_date" value="{{date('Y-m-d')}}" class="inputField">
                            <span class="error_msg" id="error_end_date"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
                        <div>
                            <label for="dutySlotRules" class="inputLabel">
                                Start Time
                            </label>
                            <input type="time" id="start_time" name="start_time" class="inputField">
                            <span class="error_msg" id="error_start_time"></span>
                        </div>
                        <div>
                            <label for="dutySlotRules" class="inputLabel">
                                Threshold Time
                            </label>
                            <input type="time" id="threshold_time" name="threshold_time" class="inputField">
                            <span class="error_msg" id="error_threshold_time"></span>
                        </div>
                        <div>
                            <label for="dutySlotRules" class="inputLabel">
                                End Time
                            </label>
                            <input type="time" id="end_time" name="end_time" class="inputField">
                            <span class="error_msg" id="error_end_time"></span>
                        </div>
                    </div>
                </div>
                <div class="sticky bottom-0 z-50 bg-gray-100">
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                        <button type="button" onclick="createDutySlotRuleModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                            Cancel
                        </button>
                        <button type="submit" class="submit-button">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</dialog>

