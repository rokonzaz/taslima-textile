<form action="{{ route('leave-type.update', ['id'=>$leaveType->id]) }}" method="POST" enctype="multipart/form-data" >
    @csrf
    <div class="p-4">
        <div class="flex flex-col gap-2">
            <div class="">
                <label for="" class="inputLabel">Days</label>
                <input id="e_leave_type_days" type="number" name="leave_type_days" value="{{$leaveType->days}}" class="inputField">
                <span class="text-sm text-[#831b94]" id="e_error_leave_type_days"></span>
            </div>
            <div class="">
                <label for="" class="inputLabel">Remarks</label>
                <textarea id="e_leave_type_remarks" name="leave_type_remarks" class="inputField" rows="2" placeholder="Remarks..." >{{$leaveType->remarks ?? ''}}</textarea>
                <span class="text-sm text-[#831b94]" id="e_error_leave_type_remarks"></span>
            </div>
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

