<x-modals.small-modal id="editWeekendModal" title="Update Weekend">
    <form action="{{ route('weekend.update') }}" method="POST" class="mb-0" enctype="multipart/form-data" onsubmit="return validateFormRequest('e_', 'weekend')">
        @csrf
        <div class="p-4 overflow-y-scroll min-h-[25vh] max-h-[50vh]">
            <div class="col-span-1 relative">
                <label for="select_weekendX" class="inputLabel">
                    Select Weekend <span class="text-[#831b94]">*</span>
                </label>
                <select id="e_weekend" name="weekends[]" class="" placeholder="Select Weekend..."></select>
                <span class="error_msg" id="e_error_weekend"></span>
            </div>
        </div>
        <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700 sticky bottom-0 z-50 bg-gray-100">
            <button type="button" onclick="editWeekendModal.close()" class="cancel-button">
                Cancel
            </button>
            <button type="submit" class="submit-button">
                Update
            </button>
        </div>
    </form>
</x-modals.small-modal>
