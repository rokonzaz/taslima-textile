<form action="{{ route('roles.update', ['id'=>$role->id]) }}" method="POST" class="mb-0" enctype="multipart/form-data" onsubmit="return validateFormRequest('e_', 'role')">
    @csrf
    <div class="p-4">
        @if ($role->is_changeable)
            <div>
                <label for="name" class="inputLabel">Role Name <span class="text-[#831b94]">*</span></label>
                <input type="text" id="e_role_name" name="name" value="{{$role->name ?? ''}}" class="inputField" >
                <span class="error_msg" id="e_error_role_name"></span>
            </div>
            <div class="flex items-center mt-2">
                <label for="checkbox" class="block dark:text-neutral-200 text-sm font-medium">Is Active?</label>
                <div class="form-control">
                    <label class="cursor-pointer label">
                        <input type="checkbox" id="checkbox" name="is_active" class="shrink-0 mt-0.5 border-gray-200 rounded text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-red-800 ml-2 " {{$role->is_active ? 'checked' : ''}}>
                    </label>
                </div>
            </div>
        @endif
    </div>

    <div class="">
        <div class="mt-5 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
            <button type="button" onclick="smallModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                Cancel
            </button>
            <button type="submit" class="submit-button">
                Update Role
            </button>

        </div>
    </div>
</form>
