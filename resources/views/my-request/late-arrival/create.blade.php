<x-modals.small-modal id="lateArrivalRequestModal" title="Late Arrival Form">
    <form class="mb-0" action="{{route('my-requests.store', ['a'=>'late-arrival'])}}" method="POST" enctype="multipart/form-data" onsubmit="return validateLateRequestForm()">
        @csrf
        <div class="p-4 overflow-y-scroll max-h-[60vh]">
            <div class="flex flex-col gap-3">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="" class="inputLabel">Late Date <span class="text-[#831b94]">*</span></label>
                        <input type="date" name="date" value="{{date('Y-m-d')}}" class="inputField">
                        <span class="error_msg" id="err_date"></span>
                    </div>
                    <div>
                        <label for="" class="inputLabel">Late Time <span class="text-[#831b94]">*</span></label>
                        <input type="time" name="time" value="{{date('H:i')}}" class="inputField">
                        <span class="error_msg" id="err_time"></span>
                    </div>
                </div>
                <div class="">
                    <label for="leave_typeX" class="inputLabel">
                        Late Reason <span class="text-[#831b94]">*</span>
                    </label>

                    <ul class="flex flex-col sm:flex-row">
                        @php $lateResons=['Meeting', 'Personal', 'Others']; @endphp
                        @foreach($lateResons as $item)
                            <label for="late-reason-{{$item}}" class="inline-flex items-center gap-x-2 py-3 px-2 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                                <div class="relative flex items-start w-full gap-2">
                                    <div class="flex items-center h-5">
                                        <input type="radio" id="late-reason-{{$item}}" name="late_reason" value="{{$item}}" class="leave-type -mt-0.5 border-gray-200 rounded-full disabled:opacity-50 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                    </div>
                                    <span>{{$item}}</span>
                                </div>
                            </label>
                        @endforeach
                    </ul>
                    <span class="error_msg" id="error_late_reason_radio"></span>
                </div>
                <div>
                    <label for="" class="inputLabel">Late Note <span class="text-[#831b94]" id="late_note_start_mark"></span></label>
                    <textarea id="late_note" name="late_note" class="inputField" rows="2" placeholder="Please tell us your late note..."></textarea>
                    <span class="error_msg" id="error_late_note"></span>
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100">
            <div class="flex justify-end items-center gap-x-2 py-2 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="lateArrivalRequestModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    Cancel
                </button>
                <button type="submit" class="submit-button">
                    Submit
                </button>
            </div>
        </div>
    </form>
</x-modals.small-modal>
<script>

    function validateLateRequestForm() {
        // Clear previous error messages
        $('#err_date').text('');
        $('#err_time').text('');
        $('#error_late_reason_radio').text('');
        $('#error_late_note').text('');

        // Get form values
        let isValid = true;
        const date = $('input[name="date"]').val();
        const time = $('input[name="time"]').val();
        const lateReason = $('input[name="late_reason"]:checked').val();
        const lateReasonNote = $('#late_note').val();

        // Validate date
        if (!date) {
            $('#err_date').text('Please select a date.');
            isValid = false;
        }
        if (!time) {
            $('#err_time').text('Please select a time.');
            isValid = false;
        }

        // Validate late reason
        if (!lateReason) {
            $('#error_late_reason_radio').text('Please select a reason.');
            isValid = false;
        }

        if (!lateReasonNote) {
            $('#error_late_note').text('Please provide a note...');
            isValid = false;
        }

        // Return false to prevent form submission if any field is invalid
        return isValid;
    }

</script>
