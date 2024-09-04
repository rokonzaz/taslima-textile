<div class="">
    <input type="hidden" id="emp_id" value="{{$employee->id}}" autocomplete="off">
    <div class="p-4 overflow-y-scroll max-h-[50vh]">
        <div class="">
            <label for="" class="inputLabel">
                Select status <span class="text-[#831b94]">*</span>
            </label>
            <select id="status" name="is_active" class="inputFieldBorder">
                <option value="1" {{$employee->is_active===1 ? 'disabled':''}}>Active</option>
                <option value="0" {{$employee->is_active===0 ? 'disabled':''}}>Inactive</option>
            </select>
            <span class="error_msg" id="error_status"></span>
        </div>
        <div class="mt-2">
            <label for="reason" class="inputLabel">
                Reason <span class="text-[#831b94]">*</span>
            </label>
            <textarea class="inputFieldBorder" id="status_reason" name="status_reason"></textarea>
            <span class="error_msg" id="error_status_reason"></span>
        </div>

    </div>

    <div class="sticky bottom-0 z-50 bg-gray-100">
        <div class="mt-5 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
            <button type="button" onclick="smallModal.close()" class="cancel-button" >
                Cancel
            </button>
            <button type="submit" id="changeActiveStatusBtn"  onclick="changeActiveStatus({{$employee->id}})" class="submit-button">
                Save
            </button>
        </div>
    </div>
</div>
