<x-modals.small-modal id="createDepartment" title="Create a Department">
    <form class="mb-0" action="{{ route('department.store')}}" method="POST" enctype="multipart/form-data" onsubmit="return validateFormRequest('c_', 'department')">
        @csrf
        <div class="p-4 overflow-y-scroll max-h-[70vh]">
            <div class="p-4">
                <label for="" class="inputLabel">
                    Department Name
                </label>
                <input id="c_department_name" type="text" name="name" value="" class="inputField">
                <span class="text-sm text-[#831b94]" id="c_error_department_name"></span>
                <div class="flex items-center gap-x-4">
                    <label for="hs-checkbox-group-1" class="text-base font-semibold text-gray-700 dark:text-neutral-400 mt-2">Is Active</label>
                    <input type="checkbox" checked name="is_active" class="shrink-0 mt-2 border-gray-400 rounded text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-checkbox-group-1">
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100">
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="createDepartment.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    Cancel
                </button>
                <button type="submit" class="submit-button">
                    Submit
                </button>
            </div>
        </div>
    </form>
</x-modals.small-modal>
