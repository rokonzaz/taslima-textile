<form class="items-center w-full" action="{{ route('designation.update', $designation->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <div class="flex items-center justify-between px-4 py-3 border-b dark:border-neutral-700">
        <h3 class="font-bold text-gray-800 dark:text-white">Update Designation</h3>
    </div>
    <div>
        <div class="p-4">
            <div class="max-w-full mt-3">
                <label for="designation_name"
                    class="block mb-2 text-sm font-medium text-gray-800 dark:text-white">Designation
                    Name</label>
                <input
                    class="block w-full px-4 py-3 text-sm text-gray-800 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                    id="designation_name" name="name" type="text" value="{{ $designation->name }}"
                    autocomplete="off">
            </div>

            <div class="max-w-full mt-3">
                <label for="is_active" class="block mb-2 text-sm font-medium text-gray-800 dark:text-white">Is
                    Active</label>
                <div class="relative" id="is_active">
                    <div class="relative hs-select">
                        <select name="is_active"
                            class="block w-full px-4 py-3 text-sm text-gray-800 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                            <option value="1" {{ $designation->is_active == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $designation->is_active == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end p-4 border-t gap-x-2 dark:border-neutral-700">

            <button onclick="smallSizeModal.close()" type="button"
                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-500 border rounded-lg border-neutral-300 gap-x-2 hover:border-red-600 hover:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                Cancel
            </button>
            <button type="submit"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                Update Designation
            </button>
        </div>

    </div>
</form>
