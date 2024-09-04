<x-modals.small-modal id="createDesignationModal" title="Create a new Designation">
    <form class="mb-0" action="{{ route('designation.store') }}" method="POST" enctype="multipart/form-data" class="mb-0" onsubmit="return validateDesignationData('c_')">
        @csrf
        <div class="p-4 overflow-y-scroll max-h-[60vh]">
            <div class="grid grid-cols-1 gap-4 ">
                <div>
                    <label for="designation_name" class="block mb-2 text-sm font-medium text-gray-800 dark:text-white">Designation Name <span class="text-[#831b94]">*</span></label>
                    <input class="inputFieldBorder" id="c_designation_name" name="name" type="text" autocomplete="off" placeholder="Designation Name...">
                    <span class="error_msg" id="c_error_designation_name"></span>
                </div>
                <div class="flex items-center gap-x-4">
                    <label for="hs-checkbox-group-1" class="text-base font-semibold text-gray-700 dark:text-neutral-400">Is Active</label>
                    <input type="checkbox" checked name="is_active" class="shrink-0 mt-0.5 border-gray-400 rounded text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-checkbox-group-1">
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100">
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="createDesignationModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    Cancel
                </button>
                <button type="submit" class="submit-button">
                    Save
                </button>
            </div>
        </div>
    </form>
</x-modals.small-modal>






