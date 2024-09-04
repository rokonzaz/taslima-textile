<x-modals.small-modal id="homeOfficeRequestModal" title="Home Office Form">
    <form class="mb-0" action="{{route('my-requests.store', ['a'=>'home-office'])}}" method="POST" enctype="multipart/form-data" onsubmit="return validateHomeOfficeRequestForm()">
        @csrf
        <div class="p-4 overflow-y-scroll max-h-[60vh]">
            <div class="flex flex-col gap-3">
                <div class="grid grid-cols-2 gap-x-6 gap-y-1">
                    <div>
                        <label for="homeOfficetX" class="inputLabel">
                            Start Date <span class="text-[#831b94]">*</span>
                        </label>
                        <input type="date" id="h_start_date" onchange="calculateLeaveDays('h_','home-office')" name="start_date" value="{{date('Y-m-d')}}" class="inputFieldBorder">
                        <span class="error_msg" id="h_error_start_date"></span>
                    </div>
                    <div>
                        <label for="homeOfficetY" class="inputLabel">
                            End Date <span class="text-[#831b94]">*</span>
                        </label>
                        <input type="date" id="h_end_date" onchange="calculateLeaveDays('h_','home-office')" name="end_date" value="{{date('Y-m-d')}}" class="inputFieldBorder">
                        <span class="error_msg" id="h_error_end_date"></span>
                    </div>
                    {{-- <div id="time" class="hidden">
                        <label for="" class="inputLabel">Time <span class="text-[#831b94]">*</span></label>
                        <input type="time" onchange="calculateTimeDiff()" id="timeValue" name="home_office_time" class="inputFieldBorder">
                        <span class="error_msg" id="err_home_office_time"></span>
                    </div> --}}
                    <div class="self-center">
                        <label for="" class="inputLabel">Intend Of Home Office: </label>
                        <span id="h_home_office_count" class="text-[#831b94] italic font-medium"></span>
                        <p id="h_error_home_office_count" class="text-[#831b94] italic"></p>
                    </div>
                </div>
                <div class="">
                    <label for="leave_typeX" class="inputLabel">
                        Home Office Reason <span class="text-[#831b94]">*</span>
                    </label>

                    <ul class="flex flex-col sm:flex-row">
                        @php $homeOfficeResons=['Meeting', 'Personal', 'Others']; @endphp
                        @foreach($homeOfficeResons as $item)
                            <label for="home-office-reason-{{$item}}" class="inline-flex items-center gap-x-2 py-3 px-2 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                                <div class="relative flex items-start w-full gap-2">
                                    <div class="flex items-center h-5">
                                        <input type="radio" id="home-office-reason-{{$item}}" name="home_office_reason" value="{{$item}}" class="leave-type -mt-0.5 border-gray-200 rounded-full disabled:opacity-50 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                    </div>
                                    <span>{{$item}}</span>
                                </div>
                            </label>
                        @endforeach
                    </ul>
                    <span class="error_msg" id="error_homeOffice_reason_radio"></span>
                </div>
                <div>
                    <label for="" class="inputLabel">Home Office Note <span class="text-[#831b94]">*</span></label>
                    <textarea id="homeOffice_note" name="home_office_note" class="inputFieldBorder" rows="2" placeholder="Please tell us your home office note..."></textarea>
                    <span class="error_msg" id="error_homeOffice_note"></span>
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100">
            <div class="flex justify-end items-center gap-x-2 py-2 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="homeOfficeRequestModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
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

    function validateHomeOfficeRequestForm() {
        // Clear previous error messages

        $('#h_start_date').text('');
        $('#h_end_date').text('');
        $('#h_error_start_date').text('');
        $('#h_error_end_date').text('');
        $('#err_home_office_time').text('');
        $('#error_homeOffice_reason_radio').text('');
        $('#error_homeOffice_note').text('');
        // Get form values
        let submitPermission = true;
        const startDate = $('input[name="start_date"]').val();
        const endDate = $('input[name="end_date"]').val();
        const homeOfficeReason = $('input[name="home_office_reason"]:checked').val();
        const homeOfficeReasonNote = $('#homeOffice_note').val();

        // Validate date
        if (!endDate) {
            $('#h_error_end_date').text('Please select a end date.');
            submitPermission = false;
        }
        if (!startDate) {
            $('#h_error_start_date').text('Please select a start date.');
            submitPermission = false;
        }
        if(($(`#h_end_date`) && $(`#h_end_date`).val()) && ($(`#h_start_date`) && $(`#h_start_date`).val())){
            if($(`#h_end_date`).val()<$(`#h_start_date`).val()){
                submitPermission = false;
                $(`#h_error_end_date`).html(`End Date must be greater than start date!`)
            }else if($(`#h_end_date`).val()===$(`#h_start_date`).val()){
                /* const time= $('#time')
                const timeValue = $('input[name="home_office_time"]').val();
                console.log("🚀 ~ validateHomeOfficeRequestForm ~ time:", timeValue)
                time.removeClass('hidden').addClass('block')
                if (!timeValue) {
                    $('#err_home_office_time').text('Please select a time.');
                    isValid = false;
                } */
                console.log("End date === start date");
            }else{
                $(`#h_error_end_date`).html(``)
                /* const time= $('#time')
                time.removeClass('block').addClass('hidden') */
                console.log("End date!== start date");
            }
        }
        // Validate homeOffice reason
        if (!homeOfficeReason) {
            $('#error_homeOffice_reason_radio').text('Please select a reason.');
            submitPermission = false;
        }

        if (!homeOfficeReasonNote) {
            $('#error_homeOffice_note').text('Please provide a note...');
            submitPermission = false;
            $('#homeOffice_note').focus();
        }
        if(($(`#h_end_date`) && $(`#h_end_date`).val()) && ($(`#h_start_date`) && $(`#h_start_date`).val())){
            if($(`#h_end_date`).val()<$(`#h_start_date`).val()){
                submitPermission = false;
                $(`#h_error_end_date`).html(`End Date must be greater than start date!`)
                $(`#h_end_date`).focus();
            }else $(`#h_error_end_date`).html(``)
        }
        // Return false to prevent form submission if any field is invalid
        return submitPermission;
    }

</script>
