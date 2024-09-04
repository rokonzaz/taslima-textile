var baseUrl= window.location.origin;

var tableDef = {
    layout: {
        topStart: {},
        topEnd: {},
        bottomStart: {
            pageLength: true,
            info: true,
        }
    },
    columnDefs:{
        "targets": '_all',
        "createdCell": function (td, cellData, rowData, row, col) {
            $(td).css('padding', '5px 6px')
        },
    }
};

function debounce(func, delay) {
    let timer;
    return function() {
        const context = this;
        const args = arguments;
        clearTimeout(timer);
        timer = setTimeout(() => {
            func.apply(context, args);
        }, delay);
    };
}
function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function formatDateTime(dateTimeString, type='time-with-a') {
    let formattedTime=dateTimeString;
    let dateTime = new Date(dateTimeString);
    if(type==='time-with-a'){
        formattedTime = dateTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    if(type==='time-with-a-no-sec'){
        formattedTime = dateTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    return formattedTime;

}
function createEployeeModal() {

    if($('#createEmployee').val()===1){
        console.log($('#createEmployee').val())
        largeModal.showModal();
    }else{
        $('#largeModalTitle').html('Add a new employee');
        let spinner=$('#spinner-1').html();
        let html=`
        <div class="py-12 flex items-center justify-center">${spinner}</div>
    `
        $('#largeModalBody').html(html)
        largeModal.showModal()
        $.ajax(`${baseUrl}/employees/create`).then(function (res) {
            if(res.status===1){
                $('#largeModalBody').html(res.html)
            }
        })
    }


}

function removeInnerHtml(id) {
    $(`#${id}`).addClass('hidden');
    $(`#${id}`).html('');
}
function removeHtmlElement(id) {
    $(`#${id}`).remove();
}

function validateEmployeeSingleData(dataContent, inputId, destinationId) {

    let inputValue=$(`#${inputId}`).val();
    let isAjax=false;
    $(`#${destinationId}`).html('');
    $(`#isValidate_${inputId}`).val(0)
    if(dataContent==='email' && inputValue.length!==0){
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(regex.test(inputValue)) {
            isAjax=true;
        } else {
            $(`#${destinationId}`).html(`<i class="fa-regular fa-circle-xmark"></i> Invalid email format`);
        }
    }
    if(dataContent==='phone' && inputValue.length>10){
        isAjax=true;
    }
    if(dataContent==='emp_id' && inputValue.length>3){
        isAjax=true;
    }
    if(isAjax) {
        $(`#${destinationId}`).html($('#spinner-small').html());
        $.ajax(`${baseUrl}/employees/validate-single-data?a=${dataContent}&val=${inputValue}`).then(function (res) {
            if (res.status === 1) {
                $(`#isValidate_${inputId}`).val(1)
                $(`#${destinationId}`).html(`<span class="text-green-600"><i class="fa-regular fa-circle-check"></i> ${res.msg}</span>`);
            } else {
                $(`#isValidate_${inputId}`).val(0)
                $(`#${destinationId}`).html(`<i class="fa-regular fa-circle-xmark"></i> ${res.msg}`);
            }
        })
    }
}
function validateDesignationData(sectionId) {
    let submitPermission=true;
    let errorText='This field is required!';
    let designation=$(`#${sectionId}designation_name`);
    if (designation.val()=='') {
        submitPermission=false;
        $(`#${sectionId}error_designation_name`).html(errorText)
        designation.focus();
    }else{
        $(`#${sectionId}error_designation_name`).html('')
    }
    return submitPermission;
}
function validateLeaveType(sectionId) {
    let submitPermission = true;
    let errorText = 'This field is required!';
    let leave_type_days = $(`#${sectionId}leave_type_days`);
    let leave_type_remarks = document.getElementById(`${sectionId}leave_type_remarks`);
    if (leave_type_days.val() === '') {
        submitPermission = false;
        $(`#${sectionId}error_leave_type_days`).html(errorText);
        leave_type_days.focus();
    } else {
        $(`#${sectionId}error_leave_type_days`).html('');
    }
    if (leave_type_remarks && leave_type_remarks.value.trim() === '') {
        submitPermission = false;
        $(`#${sectionId}error_leave_type_remarks`).html('Please provide a remark.');
        leave_type_remarks.focus();
    } else {
        $(`#${sectionId}error_leave_type_remarks`).html('');
    }
    // return false;
    return submitPermission;
}

function validateEmployeeData() {
    let submitPermission=true;
    let errorText='This field is required!';
    let employeeFullName=$('#employeeFullName');
    let employeeId=$('#employeeId');
    let isValidate_employeeId=$('#isValidate_employeeId');
    let employee_email=$('#employee_email');
    let isValidate_employee_email=$('#isValidate_employee_email');
    let employeePhone=$('#employeePhone');
    let isValidate_employeePhone=$('#isValidate_employeePhone');
    let firstError=null;
    let firstErrorMsg=null;

    if(employeeFullName.val()===''){
        submitPermission=false;
        $('#error_employeeFullName').html(errorText)
        if(firstError===null){
            firstError=employeeFullName
            firstErrorMsg='Full Name required!';
        }
    }else{
        $('#error_employeeFullName').html('')
    }
    if(isValidate_employeeId.val()!=='1'){
        submitPermission=false;
        $('#error_employeeId').html(errorText)
        if(firstError===null){
            firstError=employeeId;
            firstErrorMsg='Employee ID required!';
        }
    }else{
        if(employeeId.val()==='') $('#error_employeeId').html('')
    }
    if(isValidate_employee_email.val()!=='1'){
        submitPermission=false;
        $('#error_employee_email').html(errorText)
        if(firstError===null){
            firstError=employee_email
            firstErrorMsg='Email required!';
        }
    }else{
        if(employee_email.val()==='') $('#error_employee_email').html('')
    }
    if(isValidate_employeePhone.val()!=='1'){
        submitPermission=false;
        $('#error_employeePhone').html(errorText)
        if(firstError===null){
            firstError=employeePhone
            firstErrorMsg='Phone required!';
        }
    }else{
        if(employeePhone.val()==='') $('#error_employeePhone').html('')
    }

    if(!submitPermission){
        activeTab('employee-tab-item-1')
        firstError.focus();
    }
    return submitPermission;
}
function validateEmployeeEditData(section) {
    console.log("section===>",section);
    let submitPermission=true;
    let errorText='This field is required!';
    let firstError=null;
    let firstErrorMsg=null;
    switch (section) {
        case 'personal':
            let employeeFullName=$('#employeeFullName');
            let employee_email=$('#employee_email');
            let isValidate_employee_email=$('#isValidate_employee_email');
            let isValidate_employeePhone=$('#isValidate_employeePhone');
            let employeePhone=$('#employeePhone');
            if(employeeFullName.val().length===0){
                submitPermission=false;
                $('#error_employeeFullName').html(errorText)
                if(firstError===null){
                    firstError=employeeFullName
                    firstErrorMsg='Full Name required!';
                }
            }else{
                $('#error_employeeFullName').html('')
            }
            if(isValidate_employee_email.val()!=='1'){
                submitPermission=false;
                $('#error_employee_email').html(errorText)
                if(firstError===null){
                    firstError=employee_email
                    firstErrorMsg='Email required!';
                }
            }else{
                if(employee_email.val()==='') $('#error_employee_email').html('')
            }
            if(isValidate_employeePhone.val()!=='1'){
                submitPermission=false;
                $('#error_employeePhone').html(errorText)
                if(firstError===null){
                    firstError=employeePhone
                    firstErrorMsg='Phone required!';
                }
            }else{
                if(employeePhone.val()==='') $('#error_employeePhone').html('')
            }
            break;

        case 'education':
            let institution_name=$('#institution_name_one');
            let department_name=$('#department_one');
            let passing_year=$('#passing_year_one');
            let education_result=$('#result_one');

            if(institution_name.val()=='' ){
                submitPermission=false;
                $('#error_institution_name_one').html(errorText)
                if(firstError===null){
                    firstError=institution_name
                    firstErrorMsg='Institution name is required!';
                }
            } else $('#error_institution_name_one').html('');
            if(department_name.val()==''){
                submitPermission=false;
                $('#error_department_one').html(errorText)
                if(firstError===null){
                    firstError=department_name;
                    firstErrorMsg='Department name is required!';
                }
            } else $('#error_department_one').html('');

            if(passing_year.val()=='') {
                submitPermission=false;
                $('#error_passing_year_one').html(errorText)
                if(firstError===null){
                    firstError=passing_year
                    firstErrorMsg='Passing year is required!';
                }
            }else $('#error_passing_year_one').html('');
            if(education_result.val()=='') {
                submitPermission=false;
                $('#error_result_one').html(errorText)
                if(firstError===null){
                    firstError=education_result;
                    firstErrorMsg='Result is required!';
                }
            }else $('#error_institution_name_one').html('');

            break;
        case 'security':
            let currentPassword = $('#employeeCurrentPass').val();
            let newPassword = $('#employeeNewPass').val();
            let retypeNewPassword = $('#employeeRetypeNewPass').val();

            function setError(element, message) {
                $(`#${element}`).focus();
                $(`#error_${element}`).html(message);
            }

            function clearErrors() {
                setError('employeeCurrentPass', '');
                setError('employeeNewPass', '');
                setError('employeeRetypeNewPass', '');
            }

            clearErrors();
            if (!currentPassword || !newPassword || !retypeNewPassword) {
                submitPermission = false;
                if (!currentPassword) setError('employeeCurrentPass', 'Current Password is required!');
                if (!newPassword) setError('employeeNewPass', 'New Password is required!');
                if (!retypeNewPassword) setError('employeeRetypeNewPass', 'Retype New Password is required!');
            } else if (currentPassword === newPassword) {
                submitPermission = false;
                setError('employeeNewPass', 'New password cannot be the same as the current password!');
            } else if (newPassword.length < 8) {
                submitPermission = false;
                setError('employeeNewPass', 'Password should be at least 8 characters!');
            } else if (newPassword !== retypeNewPassword) {
                submitPermission = false;
                setError('employeeRetypeNewPass', 'Passwords do not match!');
            }

            break;
        case 'dsignature':
            let digitalSignature = $('#digital_signature')[0].files[0];
            let errorElement = $('#error_digital_signature');
            errorElement.html(''); // Clear any previous errors

            if (digitalSignature) {
                let validImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validImageTypes.includes(digitalSignature.type)) {
                    errorElement.html('Please upload a valid image file (JPEG, JPG, PNG, GIF).');
                    submitPermission = false;
                } else if (digitalSignature.size > 2 * 1024 * 1024) { // 2 MB in bytes
                    errorElement.html('The image must be less than or equal to 2 MB.');
                    submitPermission = false;
                }
            } else {
                errorElement.html('Please upload a digital signature.');
                submitPermission = false;
            }
            break;
        default:
            //submitPermission=false;
            break;
    }
    if(submitPermission === false){
        firstError? firstError.focus() : '';
        //  activeTab('employee-tab-item-1')
    }
    console.log("🚀 ~ submitPermission:", submitPermission);
    return submitPermission;
}



function deletePopup(title, deletedText, url) {
    $('#deleteModalTitle').html(title)
    $('#deletedText').html(deletedText)
    $('#deleteForm').attr('action', url)
    deleteModal.showModal()
}

function copyToClipboardForDeletePopup() {
    let text = $('#deletedText').html();
    try {
        navigator.clipboard.writeText(text)
            .then(() => {
                $("#deletedTextInput").val(text)
                $("#deleteButton").prop("disabled", false);
            })
            .catch((error) => {
                //showAlert('Unable to copy text to clipboard:', error);
            });
    } catch (error) {
        console.error('Clipboard API not supported:', error);
    }
}


function editEmployeeForm(id, section) {
    $(`#${section}-details-view-wrap`).addClass('hidden')
    $(`#${section}-details-edit-wrap`).html($("#spinner-large").html())
    $(`#${section}-details-edit-wrap`).removeClass('hidden')
    $.ajax(`${baseUrl}/employees/edit/${id}?a=${section}`).then(function (res) {
        if(res.status===1){
            $(`#${section}-details-edit-wrap`).html(res.html)
        }
    })
}
function cancelEmployeeEdit(section) {
    $(`#${section}-details-view-wrap`).removeClass('hidden')
    $(`#${section}-details-edit-wrap`).addClass('hidden')
}


function addEducationForm(id) {
    $('#educationFormsWrap').removeClass('hidden');
    $('#educationFormsWrap').html($('#educationForm').html())
    $('.institution_name').focus();
}

function addDocumentForm(id) {
    $('#documentFormsWrap').html($('#addDocumentForm').html())
    $('.documentTitle').focus();
}
function editBiometricForm() {
    $('#biometricFormsWrap').html($('#biometricForm').html())
    $('.biometric_id').focus();
}
/* function setActiveTab(active , section='') {
    var currentUrl = new URL(window.location.href);
    var params = new URLSearchParams(currentUrl.search);
    if(currentUrl.href.includes(`active=${active}`) && $(`#team_${active}`)){
        console.log($(`#team_${active}`));
        $(`#team_${active}`).addClass('bg-[#831b94] !text-white font-semibold dark:bg-[#831b94] dark:text-white shadow-lg active:bg-[#831b94] active:text-white active:font-semibold active:bg-[#831b94] dark:active:text-white');
        $(`#team_${active}`).removeClass('bg-white');
    }if(currentUrl.href.includes(`active=${active}`) === false) {
        $(`#team_${!active}`).removeClass('bg-[#831b94] !text-white font-semibold dark:bg-[#831b94] dark:text-white shadow-lg active:bg-[#831b94] active:text-white active:font-semibold active:bg-[#831b94] dark:active:text-white');
        $(`#team_${!active}`).addClass('bg-white');
    }
    if (params.has('active')) {
        params.set('active', active);
    } else {
        params.append('active', active);
    }
    currentUrl.search = params.toString();
    window.history.pushState({ path: currentUrl.href }, '', currentUrl.href);
    console.log(currentUrl.href.includes(`active=${active}`));
} */
function setActiveTab(active, section='') {
    var currentUrl = new URL(window.location.href);
    var params = new URLSearchParams(currentUrl.search);

    // Add or update the active parameter in the URL
    if (params.has('active')) {
        params.set('active', active);
    } else {
        params.append('active', active);
    }
    currentUrl.search = params.toString();
    window.history.pushState({ path: currentUrl.href }, '', currentUrl.href);
}
// Function to initialize the active tab based on URL query parameter
function initializeActiveTab() {
    var currentUrl = new URL(window.location.href);
    var params = new URLSearchParams(currentUrl.search);
    if (params.has('active')) {
        var active = params.get('active');
        setActiveTab(active);
    }
}

function assignAsUserPopup(empId) {

    $.ajax(`${baseUrl}/employees/assign-role-form/${empId}`).then(function (res) {
        if(res.status===1){
            $(`#smallModalBody`).html(res.html)
            smallModal.showModal();
            $('#smallModalTitle').html('Assign As User');
        }else{
            toastr.error(res.msg)
        }
    })
}
function assignEmployeeAsUser(empId) {
    let submitPermission=true;
    let role=$('#role').val();
    let email=$('#assignRoleEmployeeEmail').val();
    if(!email){
        submitPermission=false;
        toastr.error("There is no email found!")
    }
    if(submitPermission){
        $('#assignEmployeeAsUserBtn').html($('#spinner-small-white').html()+'creating...')
        $.ajax({
            url:`${baseUrl}/employees/assign-role/${empId}`,
            data:{
                role:role,
                email:email,
            }
        }).then(function (res) {
            if(res.status===1){
                toastr.success(res.msg)
                dataTable.ajax.reload();
                //window.location.reload();
            }else{
                toastr.error(res.msg)
            }
            smallModal.close()
        })
    }

}
function changeActiveStatusPopup(empId) {

    $.ajax(`${baseUrl}/employees/change-status-form/${empId}`).then(function (res) {
        if(res.status===1){
            $(`#smallModalBody`).html(res.html)
            smallModal.showModal();
            $('#smallModalTitle').html('Change Status');
        }else{
            toastr.error(res.msg)
        }
    })
}
function changeActiveStatus(empId) {
    let submitPermission=true;
    let status=$('#status').val();
    let reason=$('#status_reason').val();
    if(!status){
        submitPermission=false;
        $('#error_status').html('This field is required!')
    }else{
        $('#error_status').html('');
    }
    if(!reason){
        submitPermission=false;
        $('#error_status_reason').html('This field is required!')
        toastr.error("Please give a reason for changing the status!")
    }else{
        $('#error_status_reason').html('');
    }
    if(submitPermission){
        $('#changeActiveStatusBtn').html($('#spinner-small-white').html()+'Changing...')
        $.ajax({
            url:`${baseUrl}/employees/change-status/${empId}`,
            data:{
                is_active:status,
                status_reason:reason,
            }
        }).then(function (res) {
            if(res.status===1){
                toastr.success(res.msg)
                dataTable.ajax.reload();
                //window.location.reload();
            }else{
                toastr.error(res.msg)
            }
            smallModal.close()
        })
    }

}


function validateutySlotSingleData(inputId, destinationId) {
    let inputValue=$(`#${inputId}`).val();
    let isAjax=false;
    if(inputValue.length>1) isAjax=true;
    $(`#${destinationId}`).html('');
    $(`#isValidate_${inputId}`).val(0)
    if(isAjax){
        $(`#${destinationId}`).html($('#spinner-small').html());
        $.ajax(`${baseUrl}/duty-slots/validate-single-data?a=${inputId}&val=${inputValue}`).then(function (res) {
            if (res.status === 1) {
                $(`#isValidate_${inputId}`).val(1)
                $(`#${destinationId}`).html(`<span class="text-green-600"><i class="fa-regular fa-circle-check"></i> ${res.msg}</span>`);
            } else {
                $(`#isValidate_${inputId}`).val(0)
                $(`#${destinationId}`).html(`<i class="fa-regular fa-circle-xmark"></i> ${res.msg}`);
            }
        })
    }

}

function validateDutySlot(action='create') {
    let prefix = '';
    if (action === 'edit') prefix = 'edit_';

    let submitPermission = true;
    let slot_name = $(`#${prefix}slot_name`);
    let start_time = $(`#${prefix}start_time`);
    let threshold_time = $(`#${prefix}threshold_time`);
    let end_time = $(`#${prefix}end_time`);
    let isValidate_slot_name = $(`#${prefix}isValidate_slot_name`);

    let firstError = null;

    if (isValidate_slot_name.val() !== '1') {
        submitPermission = false;
        $(`#${prefix}error_slot_name`).html('This field is required!');
        if (firstError === null) {
            firstError = slot_name;
        }
    } else {
        $(`#${prefix}error_slot_name`).html('');
    }

    if (start_time.val() === '') {
        submitPermission = false;
        $(`#${prefix}error_start_time`).html('This field is required!');
        if (firstError === null) {
            firstError = start_time;
        }
    } else {
        $(`#${prefix}error_start_time`).html('');
    }

    if (threshold_time.val() === '') {
        submitPermission = false;
        $(`#${prefix}error_threshold_time`).html('This field is required!');
        if (firstError === null) {
            firstError = threshold_time;
        }
    } else {
        $(`#${prefix}error_threshold_time`).html('');
    }

    if (end_time.val() === '') {
        submitPermission = false;
        $(`#${prefix}error_end_time`).html('This field is required!');
        if (firstError === null) {
            firstError = end_time;
        }
    } else {
        $(`#${prefix}error_end_time`).html('');
    }

    if (firstError !== null) {
        firstError.focus();
    }

    return submitPermission;
}

function validateDutySlotRules(edit='') {
    let submitPermission = true;
    let slot_name=$(`#${edit}duty_slot_rules`);
    let title=$(`#${edit}title`);
    let start_date=$(`#${edit}start_date`);
    let end_date=$(`#${edit}end_date`);
    let start_time=$(`#${edit}start_time`);
    let threshold_time=$(`#${edit}threshold_time`);
    let end_time=$(`#${edit}end_time`);
    let firstError=null;
    if(slot_name.val()=== null){
        submitPermission = false;
        $(`#${edit}error_slot_name`).html(`This field is required!`)
        if(firstError===null){
            firstError=slot_name;
        }
    }else $(`#${edit}error_slot_name`).html(``)
    if(title.val()=== ''){
        submitPermission = false;
        $(`#${edit}error_title`).html(`This field is required!`)
        if(firstError===null){
            firstError=title;
        }
    }else $(`#${edit}error_title`).html(``)

    if(start_date.val()===``){
        submitPermission = false;
        $(`#${edit}error_start_date`).html(`This field is required!`)
        if(firstError===null){
            firstError=start_date;
        }
    }else $(`#${edit}error_start_date`).html(``)
    if(end_date.val()===``){
        submitPermission = false;
        $(`#${edit}error_end_date`).html(`This field is required!`)
        if(firstError===null){
            firstError=end_date;
        }
    }else $(`#${edit}error_end_date`).html(``)
    if(end_date.val() && start_date.val()){
        if(end_date.val()<start_date.val()){
            submitPermission = false;
            $(`#${edit}error_end_date`).html(`End Date must be greater than start date!`)
            if(firstError===null){
                firstError=end_date;
            }
        }else $(`#${edit}error_end_date`).html(``)
    }
    if(start_time.val()===``){
        submitPermission = false;
        $(`#${edit}error_start_time`).html(`This field is required!`)
        if(firstError===null){
            firstError=start_time;
        }
    }else $(`#${edit}error_start_time`).html(``)
    if(threshold_time.val()===``){
        submitPermission = false;
        $(`#${edit}error_threshold_time`).html(`This field is required!`)
        if(firstError===null){
            firstError=threshold_time;
        }
    }else $(`#${edit}error_end_time`).html(``)
    if(end_time.val()===``){
        submitPermission = false;
        $(`#${edit}error_end_time`).html(`This field is required!`)
        if(firstError===null){
            firstError=end_time;
        }
    }else $(`#${edit}error_end_time`).html(``)
    if(firstError!==null){
        firstError.focus();
    }
    console.log(submitPermission);
    // return false;
    return submitPermission;
}
function validateFormRequest(edit='', formType='leaveRequest', management='') {
    let submitPermission = true;
    let firstError=null;
    switch (formType) {
        case 'leaveRequest':
            // let select_employee=$(`#${edit}leave_select_employee`);
            // let select_reliever=$(`#${edit}leave_select_reliever`)[0].selectize.items;
            // let leave_type_others=$(`#hs-horizontal-list-group-item-radio-6-${edit}`)[0].checked;
            let requisition_type=$('input[name="requisition_type"]:checked').val();
            let leave_type=$(`input[name="leave_type"]:checked`).val();
            let leave_reason = document.getElementById(`${edit}leave_reason`)
            let leave_remarks = document.getElementById(`${edit}leave_remarks`)
            // let start_date=$(`#${edit}start_date`);
            // let end_date=$(`#${edit}end_date`);
            /* if(select_employee.length > 0){
                if(select_employee[0].selectize.items.length=== 0){
                    console.log("geeting false");
                    submitPermission = false;
                    select_employee[0].selectize.focus();
                    $(`#${edit}error_leave_select_employee`).html(`This field is required!`)
                }else $(`#${edit}error_leave_select_employee`).html(``)
            } */
            if(management !== 'management'){
                if(!leave_type){
                    submitPermission = false;
                    $(`#${edit}error_leave_type`).html(`Please select a Leave Type!`)

                }else $(`#${edit}error_leave_type`).html(``)
            }
            if(leave_reason && leave_reason.value ===''){
                submitPermission = false;
                leave_reason.focus();
                $(`#${edit}error_leave_reason`).html(`Please tell us your leave reason first!`)

            }else $(`#${edit}error_leave_reason`).html(``)
            if(document.getElementById(`${edit}submitBtn_approved`) != null && document.getElementById(`${edit}submitBtn_approved`).value ==='2' && leave_remarks.value ==='' || document.getElementById(`${edit}submitBtn_rejected`) != null && document.getElementById(`${edit}submitBtn_rejected`).value === '3' && leave_remarks.value ===''){
                submitPermission = false;
                leave_remarks.focus();
                $(`#${edit}error_leave_remarks`).html(`You have give a remarks first!`)
            }else $(`#${edit}error_leave_remarks`).html(``)
            /* if(select_reliever.length=== 0){
                submitPermission = false;
                $(`#${edit}error_leave_select_reliever`).html(`This field is required!`)
            }else $(`#${edit}error_leave_select_reliever`).html(``)
            if($(`#${edit}start_date`) && $(`#${edit}start_date`).val()===``){
                submitPermission = false;
                $(`#${edit}error_start_date`).html(`This field is required!`)
                if(firstError===null){
                    firstError=$(`#${edit}start_date`);
                }
            }else $(`#${edit}error_start_date`).html(``)
            if($(`#${edit}end_date`) && $(`#${edit}end_date`).val()===``){
                submitPermission = false;
                $(`#${edit}error_end_date`).html(`This field is required!`)
                if(firstError===null){
                    firstError=$(`#${edit}end_date`);
                }
            }else $(`#${edit}error_end_date`).html(``)*/
                var startDate =$(`#${edit}start_date`).val();
                var endDate = $(`#${edit}end_date`).val();
                var errorMsgStart = $(`#${edit}error_start_date`);
                var errorMsgEnd = $(`#${edit}error_end_date`);
                selected = $(`input[type="radio"]:checked`);
                var leaveDaysCount = '';
                errorMsgStart.text('');
                errorMsgEnd.text('');
                leaveDaysCount = $(`#${edit}leave_days_count`);
                if (startDate && endDate) {
                    if (endDate < startDate) {
                        submitPermission = false;
                        errorMsgEnd.text('End date cannot be less than start date');
                        return;
                    }

                    // Calculate number of days in the selected range
                    var timeDiff = new Date(endDate).getTime() - new Date(startDate).getTime();
                    var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Adding 1 to include the start date
                    var finalDiff = daysDiff < 10 ? '0' + daysDiff : daysDiff;

                    // Example remaining leave balance (replace with actual method to get remaining balance)
                    let remainingBalance=$('input[name="leave_type"]:checked').data('remaining');
                    if(typeof remainingBalance ==='undefined'){
                        submitPermission = false;
                        leaveDaysCount.text('(Select a type first!)');
                    }
                    // remainingBalance=0;
                    // Check if selected leave days exceed remaining balance
                    else if (daysDiff > remainingBalance) {
                        submitPermission = false;
                        errorMsgEnd.text('Selected leave days exceed remaining balance!');
                        leaveDaysCount.text('(Exceed balance!)');
                        ($(`#${edit}end_date`)).focus();
                    } else {
                        leaveDaysCount.text('(' + finalDiff + ')');
                    }
                }

            // submitPermission=false;
            break;
        case 'team':
            let team_name=$(`#${edit}team_name`);
            let select_team=$(`#${edit}supervisor`)[0].selectize.items;
            if(team_name.val()===''){
                submitPermission = false;
                $(`#${edit}error_team_name`).html(`This field is required!`)
                if(firstError===null){
                    firstError=team_name;
                }
            }else $(`#${edit}error_team_name`).html(``)
            if(select_team.length=== 0){
                submitPermission = false;
                $(`#${edit}error_supervisor`).html(`This field is required!`)
            }else $(`#${edit}error_supervisor`).html(``)
            break;
        case 'holiday':
                let holiday_name=$(`#${edit}holiday_name`);
                if(($(`#${edit}end_date`) && $(`#${edit}end_date`).val()) && ($(`#${edit}start_date`) && $(`#${edit}start_date`).val())){
                    if($(`#${edit}end_date`).val()<$(`#${edit}start_date`).val()){
                        submitPermission = false;
                        $(`#${edit}error_end_date`).html(`End Date must be greater than start date!`)
                        if(firstError===null){
                            firstError=$(`#${edit}end_date`);
                        }
                    }else $(`#${edit}error_end_date`).html(``)
                }
                if(holiday_name.val()===''){
                    submitPermission = false;
                    $(`#${edit}error_holiday_name`).html(`This field is required!`)
                    if(firstError===null){
                        firstError=holiday_name;
                    }
                }else $(`#${edit}error_holiday_name`).html(``)
                if($(`#${edit}start_date`) && $(`#${edit}start_date`).val()===``){
                    submitPermission = false;
                    $(`#${edit}error_start_date`).html(`This field is required!`)
                    if(firstError===null){
                        firstError=$(`#${edit}start_date`);
                    }
                }else $(`#${edit}error_start_date`).html(``)
                if($(`#${edit}end_date`) && $(`#${edit}end_date`).val()===``){
                    submitPermission = false;
                    $(`#${edit}error_end_date`).html(`This field is required!`)
                    if(firstError===null){
                        firstError=$(`#${edit}end_date`);
                    }
                }else $(`#${edit}error_end_date`).html(``)
            break;

        case 'weekend':
            let select_weekend=$(`#${edit}weekend`)[0].selectize.items;
            if(select_weekend.length=== 0){
                submitPermission = false;
                $(`#${edit}error_weekend`).html(`This field is required!`)
            }else $(`#${edit}error_weekend`).html(``)
            break;

        case 'noticeRequest':
            let notice_type=$(`#${edit}notice_type`);
            let notice_date=$(`#${edit}notice_date`);
            let notice_by=$(`#${edit}notice_by`);
            let notice_description=$(`#${edit}notice_description`);
            if(notice_type.val()===''){
                submitPermission = false;
                $(`#${edit}error_notice_type`).html(`This field is required!`)
                if(firstError===null){
                    firstError=notice_type;
                }
            }else $(`#${edit}error_notice_type`).html(``)

            if(notice_date.val()===''){
                submitPermission = false;
                $(`#${edit}error_notice_date`).html(`This field is required!`)
                if(firstError===null){
                    firstError=notice_date;
                }
            }else $(`#${edit}error_notice_date`).html(``)

            if(notice_by.val()===''){
                submitPermission = false;
                $(`#${edit}error_notice_by`).html(`This field is required!`)
                if(firstError===null){
                    firstError=notice_by;
                }
            }else $(`#${edit}error_notice_by`).html(``)

            if(notice_description.val()===''){
                submitPermission = false;
                $(`#${edit}error_notice_description`).html(`This field is required!`)
                if(firstError===null){
                    firstError=notice_description;
                }
            }else $(`#${edit}error_notice_description`).html(``)



            break;
        case 'role':
            let role_name=$(`#${edit}role_name`);
            if(role_name.val()===''){
                submitPermission = false;
                $(`#${edit}error_role_name`).html(`This field is required!`)
                if(firstError===null){
                    firstError=role_name.focus();
                }
            }else $(`#${edit}error_role_name`).html(``)
            break;
        case 'department':
            let department_name=$(`#${edit}department_name`);
            if(department_name.val()===''){
                submitPermission = false;
                $(`#${edit}error_department_name`).html(`This field is required!`)
                if(firstError===null){
                    firstError=department_name.focus();
                }
            }else $(`#${edit}error_department_name`).html(``)
            break;
        case 'user':
            let create_name=$(`#${edit}create_name`);
            let create_email=$(`#${edit}create_email`);
            if(create_name.val()===''){
                submitPermission = false;
                $(`#${edit}error_create_name`).html(`This field is required!`)
                if(firstError===null){
                    firstError=create_name.focus();
                }
            }else $(`#${edit}error_create_name`).html(``)
            if(create_email.val()===''){
                submitPermission = false;
                $(`#${edit}error_create_email`).html(`This field is required!`)
                if(firstError===null){
                    firstError=create_email.focus();
                }
            }else $(`#${edit}error_create_email`).html(``)
            break;
        default:
            break;
    }
    if(firstError!==null){
        firstError.focus();
    }
    console.log("🚀 ~ validateFormRequest ~ submitPermission:", submitPermission);
    // return false;
    return submitPermission;
}
function validateLateRequest(edit='',formType='late_arrival') {
    let submitPermission = false;
    let select_employee=$(`#${edit}late_select_employee`)[0].selectize.items;
    let late_type_others=$(`#${edit}late_type #hs-horizontal-list-group-item-radio-4`)[0].checked;
    let justification = document.getElementById(`${edit}justification`)
    let late_arrival_date=$(`#${edit}late_arrival_date`);
    let late_arrival_time=$(`#${edit}late_arrival_time`);
    let early_exit_date=$(`#${edit}early_exit_date`);
    let early_exit_time=$(`#${edit}early_exit_time`);
    let firstError=null;
    if(select_employee.length=== 0){
        submitPermission = false;
        $(`#${edit}error_late_select_employee`).html(`This field is required!`)
    }else $(`#${edit}error_late_select_employee`).html(``)
    if(justification.value ===''){
        submitPermission = false;
        $(`#${edit}error_justification`).html(`Please tell us your reason first!`)
        if(firstError===null){
            firstError=justification;
        }
    }else $(`#${edit}error_justification`).html(``)
    switch(formType){
        case 'late_arrival':
            if(late_arrival_date.val()===``){
                submitPermission = false;
                $(`#${edit}error_late_arrival_date`).html(`This field is required!`)
                if(firstError===null){
                    firstError=late_arrival_date;
                }
            }else $(`#${edit}error_late_arrival_time`).html(``)
            if(late_arrival_time.val()===``){
                submitPermission = false;
                $(`#${edit}error_late_arrival_time`).html(`This field is required!`)
                if(firstError===null){
                    firstError=late_arrival_time;
                }
            }else $(`#${edit}error_late_arrival_time`).html(``)
            break;
        case 'early_exit':
            if(early_exit_date.val()===``){
                submitPermission = false;
                $(`#${edit}error_early_exit_date`).html(`This field is required!`)
                if(firstError===null){
                    firstError=early_exit_date;
                }
            }else $(`#${edit}error_early_exit_time`).html(``)
            if(early_exit_time.val()===``){
                submitPermission = false;
                $(`#${edit}error_early_exit_time`).html(`This field is required!`)
                if(firstError===null){
                    firstError=early_exit_time;
                }
            }else $(`#${edit}error_early_exit_time`).html(``)
            break;
        default:
            break;
    }

    if(firstError!==null){
        firstError.focus();
    }
    console.log(submitPermission);
    return false;
    // return submitPermission;
}
function handleChangeFormType(e) {
    if (e.value === 'late_arrival') {
        console.log("founded");
        $('#early_exit_type').addClass('hidden');
        $('#late_arrival_type').removeClass('hidden');
    } else {
        console.log("late_arrival_type");
        $('#late_arrival_type').addClass('hidden');
        $('#early_exit_type').removeClass('hidden');
    }
}


function editDutySlotRuleModal(title, url) {
    largeModal.showModal();
    $('#largeModalTitle').html(title);
    $(`#largeModalBody`).html($('#spinner-large').html())
    $.ajax(`${url}`).then(function (res) {
        if(res.status===1){
            $(`#largeModalBody`).html(res.html)
        }else{
            toastr.error(res.msg)
            $('#largeModalTitle').close()
        }
    })
}
/* function createLeaveRequestModal(title, url) {
    largeModal.showModal();
    $('#largeModalTitle').html(title);
    $(`#largeModalBody`).html($('#spinner-large').html())
    $.ajax(`${url}`).then(function (res) {
        if(res.status===1){
            $(`#largeModalBody`).html(res.html)
        }else{
            toastr.error(res.msg)
        }
    })
} */

/* Selectize control */

function getEmployeeLeaveBalance(emp_id,spinner=''){
    $.ajax({
        url: `${baseUrl}/selectize/get-employee-leave-balance?emp_id=${emp_id}`,
        method: 'GET',
        success: function(res) {
            if (res.status === 1) {
                console.log(res.data);
                res.data.leaveBalance.forEach(element => {
                    $(`#leave_balance_${element.id}`).html(`(${element.remaining}/${element.allowance})`)
                    if (element.remaining === 0) {
                        $(`#hs-horizontal-list-group-item-radio-${element.id}-c_`).prop('checked', false);
                        $(`#hs-horizontal-list-group-item-radio-${element.id}-c_`).prop('disabled', true);
                        // Automatically check the next radio button if available
                        let nextId = element.id ==3 ? 6 : element.id + 1;
                        $(`#hs-horizontal-list-group-item-radio-${nextId}-c_`).prop('checked', true);
                    } else {
                        $(`#hs-horizontal-list-group-item-radio-${element.id}-c_`).prop('disabled', false);
                    }
                    $(`#hs-horizontal-list-group-item-radio-${element.id}-c_`).attr({
                        'data-remaining': element.remaining,
                        'data-allowance': element.allowance,
                    });
                });
                /* if(spinner!==''){
                    $(`#${spinner}`).remove()
                }else{
                    $(`#employee-leave-balance-spinner${emp_id??''}`).remove()
                } */
                toastr.success(res.msg);
            }
            else{
                toastr.error(res.msg);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching Leave Balance:', error);
            toastr.error('Error fetching Leave Balance', error);
        }
    });
}

// Variable to store cached data
var cachedEmployeeData = null;
var cachedEmployeeDataEdit = null;
var lastUrl = '';
var lastSelectedValue = '';
var lastSelectElementId='';
function initializeSelectize(selectElementId, url, selectedValue, spinner='', section='') {
    if(url!=='') {
        $.ajax({
            url: `${baseUrl}/selectize/${url}`,
            method: 'GET',
            success: function (res) {
                if (res.status === 1) {
                    // Populate options from API response
                    populateOptions(res.data);
                    if (spinner !== '') {
                        $(`#${spinner}`).remove()
                    } else {
                        $(`#employee-spinner${selectedValue ?? ''}`).remove()
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    }

    // Initialize selectize with initial options
    $(`#${selectElementId}`).selectize({
        // Initial options
        plugins: url==="get-supervisor-list" || section=== 'weekend' ? ["remove_button"] : [],
        options: section==='weekend'? [
            {value: 'Friday', name: 'Friday', organization: 'Week'},
            {value: 'Saturday', name: 'Saturday', organization: 'Week'},
            {value: 'Sunday', name: 'Sunday', organization: 'Week'},
            {value: 'Monday', name: 'Monday', organization: 'Week'},
            {value: 'Tuesday', name: 'Tuesday', organization: 'Week'},
            {value: 'Wednesday', name: 'Wednesday', organization: 'Week'},
            {value: 'Thursday', name: 'Thursday', organization: 'Week'}
        ] :[],
        optionGroups: [],
        optgroupField: 'organization',
        labelField: 'name',
        searchField: ['name','value', 'organization'],
        sortField: 'name',
        maxItems: url==='get-supervisor-list' || section=== 'weekend' ? 2 : 1,
        onInitialize: function() {
            // Set the selected value after initialization
            if (selectedValue) {
                this.setValue(selectedValue);
                if(section ==='leave' && url === 'get-employee-list'){
                    getEmployeeLeaveBalance(selectedValue);
                }
            }
        },
        onItemAdd: (item)=>{
            // use a callback function to get the selected item employee data
            console.log("onItemAdd called->", item);
            if(section ==='leave' && url === 'get-employee-list'){
                getEmployeeLeaveBalance(item);
            }
        },
    });

    // Function to populate options from cached or API response
    function populateOptions(data) {
        // Map API response to selectize options format
        var options = data.map(function(item) {
            return {
                organization: item.organization,
                value: item.id,
                name: item.name
            };
        });

        // Get unique organization names
        var uniqueorganizations = [...new Set(data.map(item => item.organization))];

        // Map organizations to option groups
        var optionGroups = uniqueorganizations.map(function(organization) {
            return {
                id: organization,
                name: 'Organization: ' + organization
            };
        });

        // Get selectize instance
        var selectize = $(`#${selectElementId}`)[0].selectize;
        // Clear previous options and add new options and option groups
        selectize.clearOptions();
        selectize.clearOptionGroups();
        optionGroups.forEach(group => selectize.addOptionGroup(group.id, {label: group.name}));
        selectize.addOption(options);

        // Set the selected value again after adding options
        if (selectedValue) {
            selectize.setValue(selectedValue);
        }
    }
}



/* End Of Selectize control */

/* Print View */


function PrintView(elementId) {
    console.log(elementId);
    const printFrame = document.createElement('iframe');
    printFrame.style.display = 'none';
    printFrame.src = `${elementId}`;
    document.body.appendChild(printFrame);
    printFrame.onload = function() {
        const printWindow = printFrame.contentWindow;
        /* const style = printWindow.document.createElement('style');
        style.textContent = `@page { margin: 1cm; }`;
        printWindow.document.head.appendChild(style); */
        printWindow.print();
    }
}

/* End Of Print View */

function leaveEditModal(title, url, management='') {
    largeModal.showModal();
    $('#largeModalTitle').html(title);
    $(`#largeModalBody`).html($('#spinner-large').html())
    $.ajax(`${url}`).then(function (res) {
        if(res.status===1){
            $(`#largeModalBody`).html(res.html)
            if(management !=='management'){
                initializeSelectize(`c_leave_select_reliever_${res.id}`,'get-employee-list', res.reliever_emp_id ?? '', `employee-spinner${res.id}`);
            }
        }else{
            toastr.error(res.msg)
            largeModal.close()
        }
    })
}
function sendUserNoticeMail(title, url) {
    largeModal.showModal();
    $('#largeModalTitle').html(title);
    $(`#largeModalBody`).html($('#spinner-large').html())
    $.ajax(url).then(function (res) {
        if(res.status===1){
            $(`#largeModalBody`).html(res.html)
        }else{
            toastr.error(res.msg)
            $('#largeModalTitle').close()
        }
    })
}
function leaveViewModal(title, url) {
    largeModal.showModal();
    $('#largeModalTitle').html(title);
    $(`#largeModalBody`).html($('#spinner-large').html())
    $.ajax(`${url}`).then(function (res) {
        if(res.status===1){
            $(`#largeModalBody`).html(res.html)
        }else{
            toastr.error(res.msg)
            largeModal.close()
        }
    })
}

function editAttendanceDetailsPopup(id, date, title) {
    smallModal.showModal();
    $('#smallModalTitle').html(title);
    $(`#smallModalBody`).html($('#spinner-large').html())
    $.ajax(`${baseUrl}/attendance/edit-attendance-details/${id}/${date}`).then(function (res) {
        if(res.status===1){
            $(`#smallModalBody`).html(res.html)
        }else{
            toastr.error(res.msg)
            smallModal.close()
        }
    })
}
function addManualAttendancePopup(id, date, title) {
    smallModal.showModal();
    $('#smallModalTitle').html(title);
    $(`#smallModalBody`).html($('#spinner-large').html())
    $.ajax(`${baseUrl}/attendance/add-manual-attendance/${id}/${date}`).then(function (res) {
        if(res.status===1){
            $(`#smallModalBody`).html(res.html)
        }else{
            toastr.error(res.msg)
            smallModal.close()
        }
    })
}

function createEmployeeAttendanceDetails(id, date, page) {
    let submitPermission = true;
    let isSelectedReasonSection=false;
    let isSelectedAttendanceSection=false;
    let firstError=null;
    let fd=new FormData();
    switch (page) {
        case 'attendance-details':
            console.log('attendance-details');
            isSelectedReasonSection=true;
            let reason=$('select[name="reason"]').val();
            let additional_note=$('#additional_note').val();
            let start_time=$('#start_time').val();
            let end_time=$('#end_time').val();
            if(reason!=='' || additional_note=='' || start_time!=='' || end_time!=''){
                $('#error_reason').html('')
                $('#error_additional_note').html('')
                if(reason===''){
                    $('#error_reason').html('This field is required!')
                    $('#error_additional_note').html('')
                    submitPermission=false;
                    if(!firstError) firstError=$('#reason').focus();
                }else if(reason==='Others' && additional_note===''){
                    $('#error_additional_note').html('Please provide a Additional Note.')
                    submitPermission=false;
                    if(!firstError) firstError=$('#additional_note').focus();
                }else{
                    fd.append('isReasonSection', 1);
                    fd.append('reason', reason);
                    fd.append('additional_note', additional_note);
                    fd.append('start_time', start_time);
                    fd.append('end_time', end_time);
                }
            }
            break;
        case 'add-manual-attendance':
            console.log('add-manual-attendance');
            isSelectedAttendanceSection=true;
            if ($('#time').val() === "") {
                $('#error_time').text('Please select a time.');
                submitPermission=false;
                if(!firstError) firstError=$('#time').focus();
            }else{
                $('#error_time').text('');
            }

            if ($('#finger_note').val().trim() === "") {
                $('#error_finger_note').text('Please provide a note.');
                submitPermission=false;
                if(!firstError) firstError=$('#finger_note').focus();
            }else{
                $('#error_finger_note').text('');
            }

            fd.append('isNewAttendance', 1);
            fd.append('attendance_type', $('input[name="attendance_type"]:checked').val());
            fd.append('time', $('#time').val());
            fd.append('finger_note', $('#finger_note').val().trim());
            break;
        default:
            break;
    }
    console.log(submitPermission);

    if(isSelectedReasonSection || isSelectedAttendanceSection){
        if(submitPermission){
            $('#createEmployeeAttendanceDetailsSubmitButton').html($('#spinner-small-white').html()+" Loading...")
            $.ajax({
                url: `${baseUrl}/attendance/update-attendance-details/${id}/${date}`,
                method: 'POST',
                data:fd,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Adjust selector based on your CSRF token location
                },
                success: function(res) {
                    if (res.status === 1) {
                        dataTable.ajax.reload();
                        smallModal.close();
                        toastr.success(res.msg)

                    }else{
                        toastr.error(res.msg);
                        $('#createEmployeeAttendanceDetailsSubmitButton').html("Submit")
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        }
    }else{
        toastr.error('Please do something or cancel')
    }
}

function getTeamMembersData(teamId, action) {
    if(action==='team-members'){
        let spinner=$('#spinner-large').html();
        $(`#teamMemberListWrap`).html(`<div class="flex items-center justify-center" style="min-height: 400px">${spinner}</div>`)
        $.ajax(`${baseUrl}/team/team-members/${teamId}`).then(function (res) {
            if(res.status===1){
                $(`#teamMemberCount`).html(res.teamMemberCount)
                $(`#teamMemberListWrap`).html(res.html)
            }else{
                toastr.error(res.msg)
            }
        })
    }
    setActiveTab(teamId, 'team-tab');
    setActiveTeamCard(teamId);
}
function setActiveTeamCard(teamId){
    $(`#activeTeam`).html(`<div class="text-[#831b94] font-semibold text-lg">Active Team:</div>`)
    $(`#activeTeam`).append($(`#team_${teamId}`).html())
    $(`#activeTeam .additionalButton`).removeClass('hidden')
    $('#activeTeam .teamCard').removeAttr('onclick');
    $(`#activeTeam .teamCard`).addClass('bg-[#831b94]')
    $(`#activeTeam .teamName, #activeTeam .memberCount, #activeTeam .teamSupervisor`).addClass('text-white')

    $(`#teamCardWrap .teamCard`).removeClass('active bg-neutral-100')
    $(`#team_${teamId} .teamCard`).addClass('active bg-neutral-100')

    $('#activeTeam').animate({'zoom': 0.9}, 200).delay(100).animate({'zoom': 1}, 200)
}



function editRoleModal(title, url) {
    smallModal.showModal();
    $('#smallModalTitle').html(title);
    $(`#smallModalBody`).html($('#spinner-large').html())
    $.ajax(`${url}`).then(function (res) {
        if(res.status===1){
            $(`#smallModalBody`).html(res.html)
        }else{
            toastr.error(res.msg)
            smallModal.close()
        }
    })
}
function openAttendanceLogsPopUp(id ,title) {

    smallModal.showModal();
    $('#smallModalTitle').html(title);
    $(`#smallModalBody`).html($(`#dot-${id}`).html())
}
/* function calculateLeaveDays(section='',page='') {
    console.log(section,page);
    var startDate =new Date($(`#${section}start_date`).val());
    var endDate = new Date($(`#${section}end_date`).val());
    var errorMsgStart = $(`#${section}error_start_date`);
    var errorMsgEnd = $(`#${section}error_end_date`);
    var leaveDaysCount = ''
    if(page==='holiday'){
        leaveDaysCount=$(`#${section}total_days`);
    }else{
        leaveDaysCount=$(`#${section}leave_days_count`);
    }


    errorMsgStart.text('');
    errorMsgEnd.text('');
    leaveDaysCount.text('');

    if (startDate && endDate) {
        if (endDate < startDate) {
            errorMsgEnd.text('End date cannot be less than start date');
            return;
        }

        var timeDiff = endDate.getTime() - startDate.getTime();
        var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Adding 1 to include the start date
        var finalDiff = daysDiff<10 ? '0'+ daysDiff : daysDiff;
        leaveDaysCount.text('(' + finalDiff + ')');
    }
} */
    function calculateLeaveDays(section='', page='') {
        console.log(section, page);
        var startDate =$(`#${section}start_date`).val();
        var endDate = $(`#${section}end_date`).val();
        // console.log("🚀 ~ calculateLeaveDays ~ startDate:", startDate,"~ endDate:", endDate)
        var errorMsgStart = $(`#${section}error_start_date`);
        var errorMsgEnd = $(`#${section}error_end_date`);
        selected = $(`input[type="radio"]:checked`);
        var dataRemaining = selected.data('remaining');
        var leaveDaysCount = '';
        errorMsgStart.text('');
        errorMsgEnd.text('');
        // leaveDaysCount.text('');
        if (page === 'holiday') {
            leaveDaysCount = $(`#${section}total_days`);
            if (startDate && endDate) {
                if (endDate < startDate) {
                    errorMsgEnd.text('End date cannot be less than start date');
                    return;
                }
                var timeDiff = new Date(endDate).getTime() - new Date(startDate).getTime();
                var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Adding 1 to include the start date
                var finalDiff = daysDiff<10 ? '0'+ daysDiff : daysDiff;
                leaveDaysCount.text('(' + finalDiff + ')');
            }
        }else if(page === 'home-office'){
            leaveDaysCount = $(`#${section}home_office_count`);
            if (startDate && endDate) {
                // console.log("🚀 ~ calculateLeaveDays ~ startDate:", startDate===endDate)
                if (endDate < startDate) {
                    errorMsgEnd.text('End date cannot be less than start date');
                    return;
                }/* if(startDate === endDate){
                    const time= $('#time')
                    time.removeClass('hidden').addClass('block')
                    console.log("End date === start date");
                    calculateTimeDiff(leaveDaysCount);
                } */
                else{
                    /* const time= $('#time')
                    time.removeClass('block').addClass('hidden') */
                    // console.log("End date!== start date");
                    var timeDiff = new Date(endDate).getTime() - new Date(startDate).getTime();
                    var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Adding 1 to include the start date
                    var finalDiff = daysDiff<10 ? '0'+ daysDiff : daysDiff;
                    leaveDaysCount.text(`(${finalDiff}) ${finalDiff==0o1 ? 'day' : 'days'}`);
                }
            }
        } else {
            leaveDaysCount = $(`#${section}leave_days_count`);
            if (startDate && endDate) {
                if (endDate < startDate) {
                    errorMsgEnd.text('End date cannot be less than start date');
                    return;
                }

                // Calculate number of days in the selected range
                var timeDiff = new Date(endDate).getTime() - new Date(startDate).getTime();
                var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Adding 1 to include the start date
                var finalDiff = daysDiff < 10 ? '0' + daysDiff : daysDiff;

                // Example remaining leave balance (replace with actual method to get remaining balance)
                let remainingBalance=$('input[name="leave_type"]:checked').data('remaining');
                if(typeof remainingBalance ==='undefined'){
                    leaveDaysCount.text('(Select type first!)');
                }
                // remainingBalance=0;
                // Check if selected leave days exceed remaining balance
                else if (daysDiff > remainingBalance) {
                    errorMsgEnd.text('Selected leave days exceed remaining balance!');
                    leaveDaysCount.text('(Exceed balance!)');
                    ($(`#${section}end_date`)).focus();
                } else {
                    leaveDaysCount.text('(' + finalDiff + ')');
                }
            }
        }
    }

function calculateTimeDiff(leaveDaysCount){
    var leaveDaysCount = $(`#h_home_office_count`);
    const timeValue = $('input[name="home_office_time"]').val();
    var finalDiff=0;
    if(timeValue <= '12:00 Pm'){
        finalDiff=1
        return leaveDaysCount.html(`(${finalDiff}) day`);
    }else {
        finalDiff = 0.5
        return leaveDaysCount.html(`(${finalDiff}) day`);
    }

}
function changeRequisitionType() {
    let requisition_type = $('input[name="requisition_type"]:checked').val();

    if (requisition_type == 1) {
        $('#leave-type-item-radio-1-c_').prop('checked', false).prop('disabled', true);
        $('#leave-type-item-radio-2-c_').prop('checked', false).prop('disabled', true);
    } else {
        $('#leave-type-item-radio-1-c_').prop('disabled', false);
        $('#leave-type-item-radio-2-c_').prop('disabled', false);
    }
}
function editUserModel(id) {
    $(`#smallModalTitle`).html('Edit User')
    $(`#smallModalBody`).html($("#spinner-large").html())
    smallModal.showModal();
    $.ajax(`${baseUrl}/users/edit/${id}`).then(function (res) {
        if(res.status===1){
            $(`#smallModalBody`).html(res.html)
        }else{
            smallModal.close();
            toastr.error(res.msg)
        }
    })
}



function liveValidateSingleData(dataContent, inputId, destinationId, length=1, except='') {

    let inputValue=$(`#${inputId}`).val();
    //let isAjax=false;
    $(`#${destinationId}`).html('');
    $(`#isValidate_${inputId}`).val(0)
    if(inputValue.length>=length) {
        $(`#${destinationId}`).html($('#spinner-small').html());
        $.ajax(`${baseUrl}/live-validate-single-data?a=${dataContent}&val=${inputValue}&except=${except}`).then(function (res) {
            if (res.status === 1) {
                $(`#isValidate_${inputId}`).val(1)
                $(`#${destinationId}`).html(`<span class="text-green-600"><i class="fa-regular fa-circle-check"></i> ${res.msg}</span>`);
            } else {
                $(`#isValidate_${inputId}`).val(0)
                $(`#${destinationId}`).html(`<i class="fa-regular fa-circle-xmark"></i> ${res.msg}`);
            }
        })
    }
}


function editModalAjax(content, modalType, id) {
    $(`#${modalType}Body`).html($("#spinner-large").html())

    let url=`${baseUrl}/`;
    if(content==='leave-type') {
        $(`#${modalType}Title`).html('Edit Leave Type')
        url += `leave/leave-type?a=edit&id=${id}`
    }else if(content==='edit-department'){
        $(`#${modalType}Title`).html('Edit Department')
        url += `departments/edit/${id}`
    }else if(content==='edit-designation'){
        $(`#${modalType}Title`).html('Edit Designation')
        url += `designations/edit/${id}`
    }else if(content==='duty-slot'){
        $(`#${modalType}Title`).html('Edit Duty Slot')
        url += `duty-slots/edit/${id}`
    }else if(content==='team-edit'){
        $(`#${modalType}Title`).html('Edit Team')
        url += `team/edit/${id}`
    }else if(content==='add-manual-leave-form'){
        $(`#${modalType}Title`).html('Add Manual Leave Balance')
        url += `employees/edit/${id}?a=${content}`
    }else if(content==='leave-manual-count-edit'){
        $(`#${modalType}Title`).html('Edit Manual Leave Balance')
        url += `employees/edit/${id}?a=${content}`
    }else if(content==='dsignature'){
        $(`#${modalType}Title`).html('Edit Digital Signature')
        url += `employees/edit/${id}?a=${content}`
    }else{

    }
    if(modalType==='smallModal') smallModal.showModal();
    if(modalType==='largeModal') largeModal.showModal();
    $.ajax(url).then(function (res) {
        if(res.status===1){
            $(`#${modalType}Body`).html(res.html)
        }else{
            if(modalType==='smallModal') smallModal.close();
            if(modalType==='largeModal') largeModal.close();
            toastr.error(res.msg)
        }
    })
}


function removeEmployeeModal(teamId, empId, empName) {
    $(`#deleteModalAjaxTitle`).html('Remove Employee')
    $(`#deleteModalAjaxBodyText`).html(`<div>Are You sure to remove this employee?</div> <p class="text-[#831b94]">${empName}</p>`)
    $(`#deleteModalAjaxDeleteButton`).html('Remove')
    $(`#deleteModalAjaxDeleteButton`).attr('disabled', false)
    $('#deleteModalAjaxDeleteButton').attr('onclick', `employeeToTeamAction(${teamId}, '${empId}', 'remove-employee')`);

    deleteModalAjax.showModal();
    //employeeToTeamAction(teamId, $empId, 'remove-employee')
}

function employeeToTeamAction(teamId, $empId, action='add-employee') {
    $(`#deleteModalAjaxDeleteButton`).html($('#spinner-small-white').html()+` Removing...`)
    let spinner=$('#spinner-large').html();
    $(`#teamMemberListWrap`).html(`<div class="flex items-center justify-center" style="min-height: 400px">${spinner}</div>`)
    $.ajax(`${baseUrl}/team/team-members/${teamId}?action=${action}&emp_id=${$empId}`).then(function (res) {
        deleteModalAjax.close()
        if(res.status===1){

            toastr.success(res.msg)
            $(`#teamMemberCount`).html(res.teamMemberCount)
            $(`#teamMemberListWrap`).html(res.html)
        }else{
            toastr.error(res.msg)
        }
    })
}



function editHolidayModal(title, url) {
    smallModal.showModal();
    $('#smallModalTitle').html(title);
    $(`#smallModalBody`).html($('#spinner-large').html())
    $.ajax(url).then(function (res) {
        if(res.status===1){
            $(`#smallModalBody`).html(res.html)
        }else{
            toastr.error(res.msg)
            $('#smallModalTitle').close()
        }
    })
}

function editWeekendModalX(title, url) {
    smallModal.showModal();
    $('#smallModalTitle').html(title);
    $(`#smallModalBody`).html($('#spinner-large').html())
    $.ajax(url).then(function (res) {
        if(res.status===1){
            $(`#smallModalBody`).html(res.html)
            console.log(res);
            /*$weekends = $weekend->pluck('id');
            $weekendsArray = $weekend->map(function($item) {
                return (string) $item;
            });
            initializeSelectize('e_weekend','', @json($weekendsArray), '','weekend');*/
        }else{
            toastr.error(res.msg)
            $('#smallModalTitle').close()
        }
    })
}

/* Export Options */
function fnExportReport(sectionId='${fileName}', format , fileName='Data') {
    var tab = document.querySelector(`#${sectionId} table`); // get the table within the div

    if (!tab) {
        toastr.warning("No table found!");
        return;
    }

    // Get today's date
    var today = new Date();
    var day = String(today.getDate()).padStart(2, '0');
    var month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0
    var year = today.getFullYear();
    var formattedDate = `${day}-${month}-${year}`;

    if (format === 'csv') {
        generateCSV(tab, formattedDate,fileName);
    } else if (format === 'xlsx') {
        generateXLSX(tab, formattedDate,fileName);
    } else if (format === 'pdf') {
        generatePDF(tab, formattedDate,fileName);
    } else {
        toastr.warning('Unsupported format!');
    }
}

function generateCSV(tab, formattedDate,fileName) {
    var tab_text = "";
    var rows = tab.querySelectorAll("tr");

    // Add header row with custom style
    var headerCells = rows[0].cells;
    for (var j = 0; j < headerCells.length; j++) {
        tab_text += headerCells[j].innerText;
        if (j < headerCells.length - 1) {
            tab_text += ",";
        }
    }
    tab_text += "\n";

    // Loop through table rows
    for (var i = 1; i < rows.length; i++) {
        var row = rows[i];
        var cells = row.cells;

        for (var j = 0; j < cells.length; j++) {
            tab_text += cells[j].innerText;
            if (j < cells.length - 1) {
                tab_text += ",";
            }
        }
        tab_text += "\n";
    }

    var blob = new Blob([tab_text], { type: 'text/csv' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = `${fileName}_${formattedDate}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    // toastr.success(`${fileName}_${formattedDate}.csv is successfully Downloaded`);
}

function generateXLSX(tab, formattedDate,fileName) {
    var wb = XLSX.utils.table_to_book(tab, {sheet: "Sheet JS"});
    var ws = wb.Sheets["Sheet JS"];

    // Add styling to header row
    var range = XLSX.utils.decode_range(ws['!ref']);
    for (var C = range.s.c; C <= range.e.c; ++C) {
        var cell_address = { c: C, r: 0 }; // First row
        var cell_ref = XLSX.utils.encode_cell(cell_address);
        if (!ws[cell_ref]) continue;
        ws[cell_ref].s = {
            font: { bold: true },
            fill: { fgColor: { rgb: "0000FF" } } // Blue background
        };
    }

    var wbout = XLSX.write(wb, {bookType: 'xlsx', type: 'binary'});

    function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i = 0; i < s.length; i++) {
            view[i] = s.charCodeAt(i) & 0xFF;
        }
        return buf;
    }

    var blob = new Blob([s2ab(wbout)], {type: "application/octet-stream"});
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = `${fileName}_${formattedDate}.xlsx`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    // toastr.success(`${fileName}_${formattedDate}.xlsx is successfully Downloaded`);
}

function generatePDF(tab, formattedDate,fileName) {
    const { jsPDF } = window.jspdf;
    var doc = new jsPDF();

    doc.autoTable({
        html: tab,
        headStyles: { fillColor: [0, 0, 255], textColor: [255, 255, 255], fontStyle: 'bold' }
    });
    doc.save(`${fileName}_${formattedDate}.pdf`);
    // toastr.success(`${fileName}_${formattedDate}.pdf is successfully Downloaded`);
}
$('#toggleButton').click(function() {
    var isCollapsed = $('#toggleButton').attr('data-value');
    console.log('isCollapsed', isCollapsed);
    if (isCollapsed === '0') {
        $('#sidebar').animate({ width:'70px'}, 300); // Include duration in animate
        setTimeout(function() {
            $('#toggleButton').attr('data-value', '1');  // Set to string '1'
            localStorage.setItem('sidebarState', '1');  // Save state to local storage
            console.log('isCollapsed after setting to 1:', $('#toggleButton').attr('data-value'));
            $('#sidebar').removeClass('toggle-full').addClass('toggle-collapsed');
            $('#main-container').removeClass('md:pl-64').addClass('toggle-pl-70');
            $('#topnav').removeClass('lg:ps-64').addClass('toggle-pl-70');
            $('#toggleButton i').removeClass('rotate-0').addClass('rotate-180'); // Rotate the icon
        }, 300);
    } else {
        $('#sidebar').animate({ width:'256px'}, 300);
        setTimeout(function() {
            $('#toggleButton').attr('data-value', '0');  // Set to string '0'
            localStorage.setItem('sidebarState', '0');  // Save state to local storage
            console.log('isCollapsed after setting to 0:', $('#toggleButton').attr('data-value'));
            $('#sidebar').removeClass('toggle-collapsed').addClass('toggle-full');
            $('#main-container').removeClass('toggle-pl-70').addClass('md:pl-64');
            $('#topnav').removeClass('toggle-pl-70').addClass('lg:ps-64');
            $('#toggleButton i').removeClass('rotate-180').addClass('rotate-0'); // Rotate the icon back
        }, 300);  // Wait for animation to complete before setting class back
    }
});
$('#navbarToggle').click(function() {
    console.log("found toggle button");
    $('#sidebar').css({ width:'256px'});
    $('#toggleButton').attr('data-value', '0');  // Set to string '0'
    console.log('isCollapsed after setting to 0:', $('#toggleButton').attr('data-value'));
    $('#sidebar').removeClass('toggle-collapsed').addClass('toggle-full');
    $('#main-container').removeClass('toggle-pl-70').addClass('md:pl-64');
    $('#topnav').removeClass('toggle-pl-70').addClass('lg:ps-64');
    $('#toggleButton i').removeClass('rotate-180').addClass('rotate-0'); // Rotate the icon back
})








/*function makeEmployeeCellData(row) {
    let html=`<a href="${baseUrl}/employees/view/${row.emp_id}" class="hover:text-[#831b94] duration-200">
                        <div class="flex items-center gap-3">
                            <div class="">
                                <figure class="w-8 aspect-square rounded-full overflow-hidden">
                                    <img class="w-full h-full object-cover" src="${row.profile_img}" onerror="this.onerror=null;this.src='${row.profile_img_default}';" alt="{{$employee->name ??''}}"/>
                                </figure>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-semibold text-sm">${row.name}</span>
                                <span>${row.email ? row.email : ''}</span>
                                <span class="text-xs">${row.phone ? row.phone : ''} ${row.organization!==null ? `<span class="font-medium">(${row.organization})</span>`:``}</span>
                            </div>
                        </div>
                    </a>`;
    return html;
}*/

function makeTableCellData(row, name='', data=[]) {
    let html=``;
    if(name==='employee'){
        html=`<a href="${baseUrl}/employees/view/${row.emp_id}" class="hover:text-[#831b94] duration-200 leading-none">
                        <div class="flex items-center gap-3">
                            <div class="">
                                <figure class="w-8 aspect-square rounded-full overflow-hidden">
                                    <img class="w-full h-full object-cover" src="${row.profile_img}" onerror="this.onerror=null;this.src='${row.profile_img_default}';" alt="{{$employee->name ??''}}"/>
                                </figure>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-semibold text-sm">${row.name}</span>
                                <span>${row.email ? row.email : ''}</span>
                                <span class="text-xs">${row.phone ? row.phone : ''} ${row.organization!==null ? `<span class="font-medium">(${row.organization})</span>`:``}</span>
                            </div>
                        </div>
                    </a>`;
    }
    if(name==='department-head'){
        html=`<div class="flex flex-col items-start gap-2">
                            <span class="truncate font-medium tracking-wide">Supervisor: <span class="italic font-medium">${row.supervisor ? row.supervisor : 'N/A'}</span></span>
                            <span class="truncate font-medium tracking-wide">Line Manager: <span class="italic font-medium">${row.line_manager ? row.line_manager : 'N/A'}</span></span>
                        </div>`;
    }

    if(name==='leave_type'){
        html=`
            <div class="">
                 <p class="font-medium">${row.leave_type}</p>
                 <div class="tooltip" data-tip="${row.leave_reason}">
                  <div class="w-32 truncate text-left"><span class="text-xs">${row.leave_reason ? `Reason: ${row.leave_reason}` : ``}</span></div>
                </div>
            </div>
        `
    }
    if(name==='request-approval-status'){
        let bgClass=`btn-pending-tr`
        if(row.status==='Approved'){
            bgClass=`btn-approved-tr`;
        }
        if(row.status==='Rejected'){
            bgClass=`btn-rejected-tr`
        }

        html=`<div class="flex flex-col justify-center items-center">
                <span><span class="${bgClass}">${row.status}</span></span>`

        if(row.status==='Pending'){
            if(!row.approvalInfo.is_line_manager_approved){
                html+=`<span class="text-xs text-center">Waiting for line manager approval</span>`
            }
            if(row.approvalInfo.is_line_manager_approved){
                html+=`<div class="tooltip" data-tip="Approved by ${row.approvalInfo.line_manager_approved_by} at ${row.approvalInfo.line_manager_approved_at}">
                            <span class="text-xs text-teal-600"><i class="fa-solid fa-circle-check"></i> ${row.approvalInfo.line_manager_approved_by}</span>
                        </div>`;
                if(row.approvalInfo.line_manager_approval_note!==null || row.approvalInfo.line_manager_approval_note!==''){
                    html+=`<div class="tooltip" data-tip="${row.approvalInfo.line_manager_approval_note}">
                              <div class="w-32 truncate"><span class="text-xs"><i class="ti ti-corner-down-right"></i>Note:${row.approvalInfo.line_manager_approval_note}</span></div>
                            </div>`
                }
            }

            if(row.approvalInfo.is_department_head_approved){
                html+=`<div class="tooltip" data-tip="Approved by ${row.approvalInfo.department_head_approved_by} at ${row.approvalInfo.department_head_approved_at}">
                            <span class="text-xs text-teal-600"><i class="fa-solid fa-circle-check"></i> ${row.approvalInfo.department_head_approved_by}</span>
                        </div>`;
                if(row.approvalInfo.department_head_approval_note!==null || row.approvalInfo.department_head_approval_note!==''){
                    html+=`<div class="tooltip" data-tip="${row.approvalInfo.department_head_approval_note}">
                              <div class="w-32 truncate"><span class="text-xs"><i class="ti ti-corner-down-right"></i>Note:${row.approvalInfo.department_head_approval_note}</span></div>
                            </div>`
                }
            }

        }
        if(row.status==='Rejected'){
            html+=`
                <div class="tooltip" data-tip="Rejected by ${row.approvalInfo.rejected_by} at ${row.approvalInfo.rejected_at}">
                    <span class="text-xs text-[#831b94]"><i class="fa-solid fa-circle-xmark"></i> ${row.approvalInfo.rejected_by}</span>
                </div>
                ${row.approvalInfo.rejected_note ?
                `<div class="tooltip" data-tip="${row.approvalInfo.rejected_note}">
                      <div class="w-32 truncate"><span class="text-xs"><i class="ti ti-corner-down-right"></i>Note:${row.approvalInfo.rejected_note}</span></div>
                    </div>`
                : ``}
            `;
        }
        html+=`</div>`;
        return html;

    }
    if(name==='dateTime'){
        html=`
            <div class="">
                <p>${row.date}</p>
                <p class="font-medium text-teal-600">${row.time}</p>
            </div>

        `
    }
    if(name==='request-reason'){
        html=`
            ${row.note!==null?
            `<p class="font-semibold">${row.reason}</p>
                <div class="tooltip" data-tip="${row.note}">
                  <div class="w-48 truncate text-left"><span class="text-xs">Note:${row.note}</span></div>
                </div>
            `
            :`<p class="font-semibold">${row.reason}</p>`}

        `
    }
    if(name==='request-approval-action'){

        if(row.approvalPermission===false && row.deletePermission===false) return '';

        let lastChild = false;
        let dropdownClasses = "dropdown";
        if (lastChild) {
            dropdownClasses += " dropdown-top";
        } else {
            dropdownClasses += " dropdown-left";
        }
        let html=`<div class="flex items-center justify-center font-medium">
            <div class="${dropdownClasses}">
                <button type="button" tabindex="0" role="button" class="flex items-center justify-center text-sm font-semibold text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg size-7 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                    <svg class="flex-none text-gray-600 size-3" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="1" />
                        <circle cx="12" cy="5" r="1" />
                        <circle cx="12" cy="19" r="1" />
                    </svg>
                </button>
                <ul tabindex="0" class="dropdown-content z-[1] menu px-0 shadow bg-base-100 rounded-md !w-44">
                    <div class=" px-2">
                        ${row.approvalPermission ? `<button type="button"
                            class="editUserBtn w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer"
                            onclick="requestApprovalPopup(${row.id}, '${data.title} Approval', 'approve')">
                            <i class="ti ti-checkbox"></i>
                            Approve
                        </button>
                        <button type="button"
                            class="editUserBtn w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer"
                            onclick="requestApprovalPopup(${row.id}, '${data.title} Rejection', 'reject')">
                            <i class="ti ti-mail-x"></i>
                            Reject
                        </button>`:''}

                        ${row.deletePermission ? `<button type="button"
                            onclick="deletePopup('Delete Emplyee', '${row.full_name}', '${baseUrl}/employ/delete/${row.id}')"
                            class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-lg text-xs text-[#831b94] hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                            <i class="fa-regular fa-trash-can "></i>
                            Delete
                        </button>`:''}
                    </div>
                </ul>
            </div>
        </div>`;
        return  html;
    }
    return html;
}


function requestApprovalPopup(id, title, approvalType='approve') {
    smallModal.showModal();
    $('#smallModalTitle').html(title);
    $(`#smallModalBody`).html($('#spinner-large').html())
    $.ajax({
        url:`${baseUrl}/request-approvals/approval/${id}`,
        data:{
            approvalType:approvalType
        }
    }).then(function (res) {
        if(res.status===1){
            $(`#smallModalBody`).html(res.html)
        }else{
            toastr.error(res.msg)
            smallModal.close()
        }
    })
}


function validateRequestApproval(section) {
    let submitPermission=true;
    if(section==='late-arrival'){
        let remarks=$('#remarks').val();
        if(remarks==='') {
            submitPermission=false;
        }
    }
    return submitPermission;
}


function getDashboardData(a='') {
    let spinner = $('#spinner-small').html();
    let loader = $('#loader-lg-dashboard-table').html();
    if(a==='my-report'){
        let month = $('#month').val();
        $('#leaveReportList').html(loader)
        $('#lateReportList').html(loader)
        $('#monthlyReportSummaryList').html(loader)
        $.ajax({
            url: `${baseUrl}`,
            data: {
                a: a,
                month: month,
            }
        }).then(function (res) {
            console.log(res)
            if (res.status === 1) {
                let data = res.data;
                $('#leaveReportList').html(data.leaveHtml);
                $('#lateReportList').html(data.lateListHtml);
                $('#monthlyReportSummaryList').html(data.monthlyReportSummaryHtml);

                $('#leaveReportCount').html(`(${data.leaveCount})`)
                $('#lateReportCount').html(`(${data.lateCount})`)

                var options = {
                    valueNames: ['name']
                };
                new List('lateReportList', options);
                new List('leaveReportList', options);
                new List('monthlyReportSummaryList', options);
            }
        })
    }else {
        let organization = $('#organization').val();
        let date = $('#date').val();
        $('#employeeCountCard .counting, #presentCountCard .counting, #absentCountCard .counting, #leaveCountCard .counting').html(spinner)
        $('#attendanceReportList').html(loader)
        $('#absentEmployeeList').html(loader)
        $('#lateEmployeeList').html(loader)
        $.ajax({
            url: `${baseUrl}`,
            data: {
                a: a,
                organization: organization,
                date: date,
            }
        }).then(function (res) {
            if (res.status === 1) {
                let data = res.data;
                $('#employeeCountCard .counting').html(data.employeeCount)
                $('#presentCountCard .counting').html(data.presentCount)
                $('#absentCountCard .counting').html(data.absentCount)
                $('#leaveCountCard .counting').html(data.leaveCount)


                $('#absentEmployeeList').html(data.absentEmployeeHtml)
                $('#absentEmployeeCount').html(`(${data.absentCount})`)
                var options = {
                    valueNames: ['name']
                };
                new List('absentEmployeeList', options);
                $('#lateEmployeeList').html(data.lateEmployeeHtml)
                $('#lateEmployeeCount').html(`(${data.lateCount})`)
                new List('lateEmployeeList', options);

                barchart('attendance-report', data.attendanceReport)

            }
        })
    }
}


function barchart(name, data) {
    if(name==='attendance-report'){
        if (window.attendanceChart) {
            window.attendanceChart.destroy();
        }
        $("#attendanceReportList").html('')
        let colors=["#059669", "#3b82f6", "#dc2626"]
        let regularCount=data.regularCount;
        let absentCount=data.absentCount;
        let lateCount=data.lateCount;
        var options = {
            series: [{
                data: [regularCount, lateCount, absentCount]
            }],
            chart: {
                height: 300,
                type: 'bar',
                events: {
                    click: function(chart, w, e) {
                        // console.log(chart, w, e)
                    }
                }
            },
            colors: colors,
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            xaxis: {
                categories: [
                    ['Regular', regularCount],
                    ['Late', lateCount],
                    ['Absent', absentCount],
                ],
                labels: {
                    style: {
                        colors: colors,
                        fontSize: '12px'
                    }
                }
            }
        };

        window.attendanceChart = new ApexCharts(document.querySelector("#attendanceReportList"), options);
        window.attendanceChart.render();
    }
}
