
<form class="mb-0" action="{{route('designation.update', ['id'=>$designation->id])}}" method="post"  onsubmit="return validateDesignationData('e_')" enctype="multipart/form-data">
    @csrf
    <div class="p-4 overflow-y-scroll max-h-[60vh]">
        <div class="grid grid-cols-1 gap-4 ">
            <div>
                <label for="designation_name" class="block mb-2 text-sm font-medium text-gray-800 dark:text-white">Designation Name <span class="text-[#831b94]">*</span></label>
                <input class="inputFieldBorder" id="e_designation_name" name="name" value="{{ $designation->name }}" type="text" autocomplete="off" placeholder="Designation Name...">
                <span class="error_msg" id="e_error_designation_name"></span>
            </div>
            <div class="flex items-center gap-x-4">
                <label for="hs-checkbox-group-5" class="text-base font-semibold text-gray-700 dark:text-neutral-400">Is Active</label>
                <input type="checkbox" {{$designation->is_active == 1 ? 'checked' : ''}} name="is_active" class="shrink-0 mt-0.5 border-gray-400 rounded text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-checkbox-group-5">
            </div>
        </div>
    </div>
    <div class="sticky bottom-0 z-50 bg-gray-100">
        <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
            <button type="button" onclick="smallModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                Cancel
            </button>
            <button type="submit" class="submit-button">
                Update
            </button>
        </div>
    </div>
</form>
