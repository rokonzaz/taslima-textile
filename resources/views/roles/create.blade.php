<x-modals.small-modal id="createRoleModal" title="Create a Role">
    <form action="{{ route('roles.store') }}" method="POST" class="mb-0" enctype="multipart/form-data" onsubmit="return validateFormRequest('c_', 'role')">
        @csrf
        <div class="p-4">
            <label for="name" class="inputLabel">Role Name <span class="text-[#831b94]">*</span></label>
            <input type="text" id="c_role_name" name="name" value="{{old('name') }}" class="inputField" >
            <span class="error_msg" id="c_error_role_name"></span>
        </div>
        <div class="">
            <div class="mt-5 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="createRoleModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    Cancel
                </button>
                <button type="submit" class="submit-button">
                    Create Role
                </button>

            </div>
        </div>
    </form>
</x-modals.small-modal>
