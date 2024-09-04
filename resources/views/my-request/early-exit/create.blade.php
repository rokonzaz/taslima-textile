<x-modals.small-modal id="earlyExitRequestModal" title="Early Exit Form">
    <form class="mb-0" action="{{ route('my-requests.store', ['a' => 'early-exit']) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateEarlyExitRequestForm()">
        @csrf
        <div class="p-4 overflow-y-scroll max-h-[60vh]">
            <div class="flex flex-col gap-3">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="early_exit_date" class="inputLabel">Early Exit Date <span class="text-[#831b94]">*</span></label>
                        <input type="date" id="early_exit_date" name="early_exit_date" value="{{ date('Y-m-d') }}" class="inputField">
                        <span class="error_msg" id="err_early_exit_date"></span>
                    </div>
                    <div>
                        <label for="early_exit_time" class="inputLabel">Early Exit Time <span class="text-[#831b94]">*</span></label>
                        <input type="time" id="early_exit_time" name="early_exit_time" value="{{ date('H:i') }}" class="inputField">
                        <span class="error_msg" id="err_early_exit_time"></span>
                    </div>
                </div>
                <div>
                    <label for="leave_typeX" class="inputLabel">
                        Early Exit Reason <span class="text-[#831b94]">*</span>
                    </label>
                    <ul class="flex flex-col sm:flex-row">
                        @php $reasons = ['Meeting', 'Personal', 'Others']; @endphp
                        @foreach($reasons as $item)
                            <label for="early-exit-reason-{{ $item }}" class="inline-flex items-center gap-x-2 py-3 px-2 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                                <div class="relative flex items-start w-full gap-2">
                                    <div class="flex items-center h-5">
                                        <input type="radio" id="early-exit-reason-{{ $item }}" name="early_exit_reason" value="{{ $item }}" class="leave-type -mt-0.5 border-gray-200 rounded-full disabled:opacity-50 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                    </div>
                                    <span>{{ $item }}</span>
                                </div>
                            </label>
                        @endforeach
                    </ul>
                    <span class="error_msg" id="error_early_exit_reason_radio"></span>
                </div>
                <div>
                    <label for="early_exit_note" class="inputLabel">Early Exit Note <span class="text-[#831b94]" id="early_exit_note_start_mark"></span></label>
                    <textarea id="early_exit_note" name="early_exit_note" class="inputField" rows="2" placeholder="Please tell us your early exit note..."></textarea>
                    <span class="error_msg" id="error_early_exit_note"></span>
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100">
            <div class="flex justify-end items-center gap-x-2 py-2 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="earlyExitRequestModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
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

    function validateEarlyExitRequestForm() {
        // Clear previous error messages
        $('#err_early_exit_date').text('');
        $('#err_early_exit_time').text('');
        $('#error_early_exit_reason_radio').text('');
        $('#error_early_exit_note').text('');

        // Get form values
        let isValid = true;
        const early_exit_date = $('input[name="date"]').val();
        const early_exit_time = $('input[name="early_exit_time"]').val();
        const earlyExitReason = $('input[name="early_exit_reason"]:checked').val();
        const earlyExitNote = $('#early_exit_note').val();

        // Validate early exit date
        if (!early_exit_date) {
            $('#err_early_exit_date').text('Please select a date.');
            isValid = false;
        }
        // Validate early exit time
        if (!early_exit_time) {
            $('#err_early_exit_time').text('Please select a time.');
            isValid = false;
        }

        // Validate early exit reason
        if (!earlyExitReason) {
            $('#error_early_exit_reason_radio').text('Please select a reason.');
            isValid = false;
        }

        // Validate note if the reason is 'Others'
        if (!earlyExitNote) {
            $('#error_early_exit_note').text('Please provide a note.');
            isValid = false;
        }

        // Return false to prevent form submission if any field is invalid
        return isValid;
    }

</script>
