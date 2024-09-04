<form action="" method="" class="mb-0" enctype="multipart/form-data" onsubmit="return true">
    @csrf
    <div class="p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
            <p>Hi</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2"  >
           hi
        </div>
    </div>
    <div class="">
        <div class="mt-5 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
            <button type="button" onclick="editNoticeModal.close()" class="cancel-button" data-hs-overlay="#edit-new-notice-modal">
                Cancel
            </button>
            <button type="submit" onsubmit="return validateDutySlot()" class="submit-button">
                Send Mail
            </button>
        </div>
    </div>
</form>


<script>
    // $(document).ready(function() {
    //     var organization_id = 1; // Set default as nexdecade

    //     $('#organizations').change(function() {
    //         organization_id = $(this).val(); // Get the selected organization ID
    //     });

    //     $('#department').change(function() {
    //         if (!organization_id) {
    //             alert('Please select a organization first');
    //             return;
    //         }

    //         var department_id = $(this).val(); // Get the selected department ID

    //         // Generate the URL with the Laravel route helper
    //         var url = "{{ route('employees.data', ['id' => '__department_id__', 'organization_id' => '__organization_id__']) }}";
    //         url = url.replace('__department_id__', department_id).replace('__organization_id__', organization_id);

    //         // Make the AJAX request
    //         $.ajax({
    //             url: url,
    //             method: 'GET',
    //             success: function(res) {
    //                 console.log(res);

    //                 // Assume res is an array of users with properties 'id' and 'name'
    //                 var userListHtml = '';
    //                 if (res.length <= 0) {
    //                     userListHtml += `<li>
    //                         <span class="text-red-700">No employee available in this department.</span>
    //                     </li>`;
    //                 } else {
    //                     res.forEach(function(user) {
    //                         userListHtml += `
    //                             <li>
    //                                 <input type="checkbox" class="userCheckbox" value="${user.id}"> <span>${user.full_name}</span>
    //                             </li>
    //                         `;
    //                     });
    //                 }

    //                 // Set the generated HTML to the target div
    //                 $('#UserList').html(userListHtml);
    //             },
    //             error: function(xhr, status, error) {
    //                 toastr.error('An error occurred: ' + error);
    //             }
    //         });
    //     });

    //     // Add event listener to the master checkbox
    //     $(document).on('change', '#selectAllCheckboxes', function() {
    //         var isChecked = $(this).is(':checked');
    //         $('#UserList .userCheckbox').prop('checked', isChecked);
    //     });

    //     // Add event listener to dynamically added checkboxes to toggle master checkbox state
    //     $(document).on('change', '#UserList .userCheckbox', function() {
    //         var allChecked = $('#UserList .userCheckbox').length === $('#UserList .userCheckbox:checked').length;
    //         $('#selectAllCheckboxes').prop('checked', allChecked);
    //     });
    // });
</script>


