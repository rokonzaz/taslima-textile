<form action="{{ route('department.update', ['id'=>$department->id]) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateFormRequest('e_', 'department')">
    @csrf
    <div class="p-4">
        <label for="" class="inputLabel">
            Department Name
        </label>
        <input type="text" id="e_department_name" name="name" value="{{$department->name}}" class="inputField">
        <span class="text-sm text-[#831b94]" id="e_error_department_name"></span>
        <div class="flex items-center gap-x-4">
            <label for="hs-checkbox-group-1" class="text-base font-semibold text-gray-700 dark:text-neutral-400 mt-2">Is Active</label>
            <input type="checkbox" {{$department->is_active == 1 ? 'checked' : ''}} name="is_active" class="shrink-0 mt-2 border-gray-400 rounded text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-checkbox-group-1">
        </div>
    </div>

    <div class="">
        <div class="mt-5 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
            <button type="submit" class="submit-button">
                Update
            </button>
        </div>
    </div>
</form>

@php

    //TODO:
@endphp

