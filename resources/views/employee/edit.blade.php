@if($a=='personal')
    {{-- Personal Information --}}
    <div class="px-5 bg-white dark:bg-neutral-800 " id="personal-details-edit">
        <form action="{{route('employees.update', ['id'=>$employee->id, 'a'=>$a])}}" onsubmit="return validateEmployeeEditData('personal')" method="post">
            @csrf
            <div class="mt-3">
                <div class="flex flex-col gap-y-2">
                    <div class=" grid grid-cols-1 gap-x-6 gap-y-3 sm:grid-cols-3">
                        <div>
                            <label for="employeeFullName" class="inputLabel">
                                Employee Name
                            </label>
                            <input id="employeeFullName" name="full_name" type="text" class="inputField" value="{{ $employee->full_name ?? '' }}"  />
                            <span class="error_msg" id="error_employeeFullName"></span>
                        </div>
                        <div>
                            <label for="employeeId" class="inputLabel">
                                Employee Id
                            </label>
                            <input disabled id="employeeId" name="emp_id" type="text" class="inputField" value="{{ $employee->emp_id ?? '' }}"  />
                            <span class="error_msg" id="error_employeeId"></span>
                            <input type="hidden" id="isValidate_employeeId" value="1">
                        </div>
                        <div>
                            <label for="employeeEmail" class="inputLabel">
                                Email
                            </label>
                            <input id="employee_email" type="email" name="email" class="inputField" value="{{ $employee->email ?? '' }}"  />
                            <span class="error_msg" id="error_employee_email"></span>
                            <input type="hidden" id="isValidate_employee_email" value="1">
                        </div>
                        <div>
                            <label for="employeeEmail" class="inputLabel">
                                Personal Email
                            </label>
                            <input id="personal_email" type="email" name="personal_email" class="inputField" value="{{ $employee->personal_email ?? '' }}"  />
                        </div>

                        <div>
                            <label for="employeeBloodGroup" class="inline-block font-medium text-sm text-gray-800 dark:text-neutral-200">
                                Blood Group
                            </label>
                            @php $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']; @endphp
                            <select id="employeeBloodGroup" name="blood_group" class="inputField" >
                                <option value="" disabled>Select Blood Group</option>
                                @foreach($bloodGroups as $item)
                                    <option value="{{$item}}" {{ $employee->blood_group == $item ? 'selected' : '' }}> {{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="employeeJoiningDate" class="inputLabel">
                                Joining Date
                            </label>
                            <input id="employeeJoiningDate" name="joining_date" type="date" class="inputField" value="{{ $employee->joining_date }}"  />
                        </div>
                        <div>
                            <label for="employeeOrganization" class="inline-block font-medium text-sm text-gray-800 dark:text-neutral-200">
                                Select organization
                            </label>
                            <select id="employeeOrganization" name="organization_name" class="inputField" >
                                <option value="" disabled {{$employee->organization_name == '' ? 'selected' : ''}}>Select Organization</option>
                                @if(isset($organizations))
                                    @foreach ($organizations as $item)
                                        <option value="{{ $item->id }}" {{$employee->organization==$item->id ? 'selected' : ''}}>{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label for="employeeDepartment" class="inline-block font-medium text-sm text-gray-800 dark:text-neutral-200">
                                Select Department
                            </label>
                            <select id="employeeDepartment" name="department" class="inputField">
                                <option value="" disabled {{$employee->department == '' ? 'selected' : ''}}>Select Department</option>
                                @if(isset($departments))
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" {{ $department->id == $employee->department ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>

                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label for="employeeDesignation" class="inline-block font-medium text-sm text-gray-800 dark:text-neutral-200">
                                Select Designation
                            </label>
                            <select id="employeeDesignation" name="designation" class="inputField">
                                @foreach ($designations as $designation)
                                    {{-- <option value="{{ $department->id }}"> --}}
                                    <option value="{{ $designation->id }}" {{ $designation->id == $employee->designation ? 'selected' : '' }}>
                                        {{ $designation->name }}
                                    </option>

                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="employeePhone" class="inputLabel"> Phone </label>
                            <input id="employeePhone" name="phone" type="text" class="inputField" value="{{ $employee->phone }}"  />
                            <span class="error_msg" id="error_employeePhone"></span>
                            <input type="hidden" id="isValidate_employeePhone" value="1">
                        </div>
                        <div>
                            <label for="employeeAlternatePhone" class="inputLabel"> Alternate Phone </label>
                            <span class="text-sm text-gray-400 dark:text-neutral-600"> (Optional) </span>
                            <input id="employeeAlternatePhone" name="alternative_phone" type="text" class="inputField" value="{{ $employee->alternative_phone }}"  />
                            <span class="error_msg" id="error_employeeAlternatePhone"></span>
                            <input type="hidden" id="isValidate_employeeAlternatePhone" value="1">
                        </div>
                        <div>
                            <label for="employeeEmmergencyPhone" class="inputLabel">Emergency Contact </label>
                            <span class="text-sm text-gray-400 dark:text-neutral-600"> (Optional) </span>
                            <input id="employeeEmmergencyPhone" name="emergency_contact" type="text" class="inputField" value="{{ $employee->emergency_contact }}"  />
                        </div>
                        <div>
                            <label for="employeeBirthday" class="inputLabel">
                                Birthday
                            </label>
                            <div class="sm:flex">
                                <input id="employeeBirthday" name="birth_year" type="date" class="inputField" value="{{ $employee->birth_year }}"  />
                            </div>
                        </div>
                        <div>
                            <label for="employeeDesignation" class="inputLabel">
                                Duty Slot
                            </label>
                            <select id="" name="duty_slot" class="inputField">
                                @if(isset($dutySlots))
                                    @foreach ($dutySlots as $item)
                                        <option value="{{ $item->id }}" {{ $item->id==$employee->duty_slot ? 'selected' : '' }}>{{ $item->slot_name }} ({{ date('h:ia', strtotime($item->start_time)) }}-{{ date('h:ia', strtotime($item->end_time)) }})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label for="af-account-gender-checkbox" class="inputLabel">
                                Gender
                            </label>
                            <ul class="flex flex-col sm:flex-row">
                                <li class="-mt-px inline-flex items-center gap-x-2.5 border bg-neutral-100 px-4 py-3 text-sm font-medium text-gray-800 first:mt-0 first:rounded-t-lg last:rounded-b-lg dark:border-neutral-700 dark:bg-neutral-700 dark:text-white sm:-ms-px sm:mt-0 sm:first:rounded-es-lg sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-se-lg">
                                    <div class="relative flex w-full items-start">
                                        <div class="flex h-5 items-center">
                                            <input id="hs-horizontal-list-group-item-radio-1" value="Male" name="gender" {{strtolower($employee->gender)=='male' ? 'checked' : ''}} type="radio" class="rounded-full border-gray-300 text-[#831b94] focus:ring-red-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-500 dark:bg-neutral-800 dark:checked:border-red-500 dark:checked:bg-red-500 dark:focus:ring-offset-gray-800"/>
                                        </div>
                                        <label for="hs-horizontal-list-group-item-radio-1" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-300">
                                            Male
                                        </label>
                                    </div>
                                </li>

                                <li class="-mt-px inline-flex items-center gap-x-2.5 border bg-neutral-100 px-4 py-3 text-sm font-medium text-gray-800 first:mt-0 first:rounded-t-lg last:rounded-b-lg dark:border-neutral-700 dark:bg-neutral-700 dark:text-white sm:-ms-px sm:mt-0 sm:first:rounded-es-lg sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-se-lg">
                                    <div class="relative flex w-full items-start">
                                        <div class="flex h-5 items-center">
                                            <input id="hs-horizontal-list-group-item-radio-2" value="Female" name="gender" {{strtolower($employee->gender)=='female' ? 'checked' : ''}} type="radio" class="rounded-full border-gray-300 text-[#831b94] focus:ring-red-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-500 dark:bg-neutral-800 dark:checked:border-red-500 dark:checked:bg-red-500 dark:focus:ring-offset-gray-800"  />
                                        </div>
                                        <label for="hs-horizontal-list-group-item-radio-2" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-300">
                                            Female
                                        </label>
                                    </div>
                                </li>

                                <li class="-mt-px inline-flex items-center gap-x-2.5 border bg-neutral-100 px-4 py-3 text-sm font-medium text-gray-800 first:mt-0 first:rounded-t-lg last:rounded-b-lg dark:border-neutral-700 dark:bg-neutral-700 dark:text-white sm:-ms-px sm:mt-0 sm:first:rounded-es-lg sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-se-lg">
                                    <div class="relative flex w-full items-start">
                                        <div class="flex h-5 items-center">
                                            <input id="hs-horizontal-list-group-item-radio-3" value="Other" name="gender" {{strtolower($employee->gender)=='other' ? 'checked' : ''}} type="radio" class="rounded-full border-gray-300 text-[#831b94] focus:ring-red-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-500 dark:bg-neutral-800 dark:checked:border-red-500 dark:checked:bg-red-500 dark:focus:ring-offset-gray-800"  />
                                        </div>
                                        <label for="hs-horizontal-list-group-item-radio-3" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-300">
                                            Other
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>

                    </div>

                </div>
                <div class="mt-5 flex items-center justify-end gap-x-2 border-t px-4 py-3 dark:border-neutral-700">
                    <button type="button" onclick="cancelEmployeeEdit('personal')" class="inline-flex items-center rounded-lg bg-black px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800" id="close-form-btn">
                        Close
                    </button>

                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
    {{-- End Personal Information --}}
@endif
@if($a=='address')
    {{-- Personal Information --}}
    <div class="px-5 bg-white dark:bg-neutral-800 " id="personal-details-edit">
        <form action="{{route('employees.update', ['id'=>$employee->id, 'a'=>$a])}}" onsubmit="return validateEmployeeEditData('address')" method="post">
            @csrf
            <div class="mt-3">
                <div class=" grid grid-cols-1 gap-4 sm:grid-cols-2 lg:gap-6">
                    <div>
                        <label for="employeePresentAddress" class="inputLabel">
                            Present Address
                        </label>
                        <textarea id="employeePresentAddress" name="present_address" class="inputField" rows="4">{{ $employee->present_address ?? '' }}</textarea>
                    </div>
                    <div>
                        <label for="employeePermanentAddress" class="inputLabel">
                            Permanent Address
                        </label>
                        <textarea id="employeePermanentAddress" name="permanent_address" class="inputField" rows="4">{{ $employee->permanent_address ?? '' }}</textarea>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-end gap-x-2 border-t px-4 py-3 dark:border-neutral-700">
                    <button type="button" onclick="cancelEmployeeEdit('address')" class="inline-flex items-center rounded-lg bg-black px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800" id="close-form-btn-for-address">
                        Close
                    </button>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
    {{-- End Personal Information --}}
@endif
@if($a=='education')
    {{-- Address Information --}}
    <div class="bg-white dark:bg-neutral-800" id="address-details-edit">
        <form
            action="{{ route("employees.address.update", $employee->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf


        </form>
    </div>
    {{-- End Address Information --}}

    {{-- Education Information --}}
    <div class="bg-white dark:bg-neutral-800" id="education-details-edit">

        <form
            action="{{ route("employees.education.update", $employee->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            {{-- {{$employee->empEducation}} --}}
            {{-- {{ route("employees.store") }} --}}
            @csrf
            @foreach ($employee->empEducation as $education)
                <div class="mt-3">
                    <!-- Education -->
                    <h3 class="text-sm capitalize text-gray-800 dark:text-white">
                        Education - One
                    </h3>
                    <div class=" grid grid-cols-1 gap-4 sm:grid-cols-3 lg:gap-6">
                        <div>
                            <label for="institution_name_one" class="inputLabel">
                                Instituation Name
                            </label>
                            <span class="text-sm text-gray-400 dark:text-neutral-600">
                            (Optional)
                        </span>
                            <input id="institution_name_one" name="institution_name[]" type="text" class="inputField" value="{{ $education->institution_name }}" disabled/>
                        </div>
                        <div>
                            <div>
                                <label for="degree_one" class="inputLabel">
                                    Select Degree Type
                                </label>
                                <span class="text-sm text-gray-400 dark:text-neutral-600">
                                (Optional)
                            </span>

                                <select id="degree_one" name="degree[]" class="inputField">
                                    <option value="SSC" {{ $education->degree == 'SSC' ? 'selected' : '' }} disabled>
                                        SSC
                                    </option>
                                    <option value="HSC" {{ $education->degree == 'HSC' ? 'selected' : '' }}>
                                        HSC
                                    </option>
                                    <option value="BSC" {{ $education->degree == 'BSC' ? 'selected' : '' }}>
                                        BSC
                                    </option>
                                    <option value="MSC" {{ $education->degree == 'MSC' ? 'selected' : '' }}>
                                        MSC
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div>
                                <label for="department_one" class="inputLabel">
                                    Department Name
                                </label>
                                <span class="text-sm text-gray-400 dark:text-neutral-600">
                                (Optional)
                            </span>

                                <input id="department_one" name="department[]" type="text" class="inputField" value="{{ $education->department }}" disabled />
                            </div>
                        </div>
                    </div>
                    <div class=" grid grid-cols-1 gap-4 sm:grid-cols-3 lg:gap-6">
                        <div>
                            <label for="passing_year_one" class="inputLabel">
                                Passing Year
                            </label>
                            <span class="text-sm text-gray-400 dark:text-neutral-600">
                            (Optional)
                        </span>
                            <input id="passing_year_one" name="passing_year[]" type="text" class="inputField" value="{{ $education->passing_year }}" disabled />
                        </div>
                        <div>
                            <div>
                                <label for="result_one" class="inputLabel">
                                    Result
                                </label>
                                <span class="text-sm text-gray-400 dark:text-neutral-600">
                                (Optional)
                            </span>

                                <input id="result_one" name="result[]" type="text" class="inputField" value="{{ $education->result }}" disabled/>
                            </div>
                        </div>
                    </div>




                </div>
                <div id="education-fields">
                    <!-- Dynamic education fields will be added here -->
                </div>

                <hr class="my-1">
            @endforeach
            <div class="mt-4">
                <button id="add-education-field" type="button" class="inline-flex items-center gap-x-2 rounded-lg border border-neutral-300 px-2 py-2 text-sm font-semibold text-gray-500 hover:border-red-600 hover:text-[#831b94] disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:text-gray-400 dark:hover:border-red-600 dark:hover:text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-dotted" viewBox="0 0 16 16">
                        <path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                    </svg>
                    Add Education
                </button>
            </div>
            <div class="mt-5 flex items-center justify-end gap-x-2 border-t px-4 py-3 dark:border-neutral-700">
                <button type="button" class="inline-flex items-center rounded-lg bg-black px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800" id="close-form-btn-for-education">
                    Close
                </button>

                <button type="submit" class="inline-flex items-center rounded-lg bg-red-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                    Update
                </button>
            </div>
        </form>

    </div>
    {{-- End Education Information --}}

    {{-- Documents Information --}}
    <div class="bg-white dark:bg-neutral-800" id="documents-details-edit">
        <form
            action="{{ route("employees.documents.update", $employee->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="mt-3">
                <div class=" grid grid-cols-1 gap-4 sm:grid-cols-2 lg:gap-6">
                    <div>
                        <p><strong class="text-red-700">Previous Documents: </strong>{{$employee->employee_resume}}</p>
                        <label for="employeeResume" class="inputLabel">
                            Upload Resume
                        </label>
                        <input id="employeeResume" name="employee_resume" type="file" class="inputField" />
                    </div>
                </div>
                <div class="mt-5 flex items-center justify-end gap-x-2 border-t px-4 py-3 dark:border-neutral-700">
                    <button type="button" class="inline-flex items-center rounded-lg bg-black px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800" id="close-form-btn-for-documets-four">
                        Close
                    </button>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
    {{-- End Documents Information --}}

@endif
@if($a=='add-manual-leave-form')
    <form action="{{route('employees.update', ['id'=>$employee->id, 'a'=>$a])}}" method="POST" class="mb-0" enctype="multipart/form-data">
        @csrf
        <div class="p-4">
            <div class="grid grid-cols-4 gap-y-2 gap-x-10">
                <div>
                    <label for="employeeOrganization" class="inputLabel">
                        Select Year
                    </label>
                    <select name="year" id="" class="inputField">
                        @for($year=(date('Y')+1); $year>=2010 ; $year--)
                            <option value="{{$year}}" {{$year==date('Y') ? 'selected' : ''}}>{{$year}}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-span-3"></div>
                @php $total=0; @endphp
                @foreach($leaveType as $item)
                    @if(in_array($item->id, [1,2,3]))
                        @php $total+=$item->days; @endphp
                        <div class="">
                            <label for="" class="inputLabel">
                                {{$item->name}} Leave
                            </label>
                            <input type="number" name="leaveCount[{{$item->id}}]" value="{{$item->days}}" onkeyup="countLeaves()" class="inputField appearance-none">
                        </div>
                    @endif
                @endforeach
                <div class="">
                    <div class="">
                        <label for="" class="inputLabel">
                            Total
                        </label>
                        <input type="number" value="{{$total}}" id="totalCount" class="inputField appearance-none" readonly>
                    </div>

                </div>
                <div class="col-span-4">
                    <div class="">
                        <label for="" class="inputLabel">
                            Remarks
                        </label>
                        <textarea name="remarks" class="inputField" id="" cols="30" rows="3"></textarea>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">

            </div>
        </div>
        <div class="">
            <div class="mt-5 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="largeModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    Cancel
                </button>
                <button type="submit" onsubmit="" class="submit-button">
                    Save
                </button>
            </div>
        </div>
    </form>
    <script>
        function countLeaves() {
            let totalLeaveCount = 0;

            $('input[name^="leaveCount"]').each(function() {
                let value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    totalLeaveCount += value;
                }
            });
            $('#totalCount').val(totalLeaveCount);
        }
    </script>
@endif
@if($a=='leave-manual-count-edit')
    <form action="{{route('employees.update', ['id'=>$manualLeaveCount->id, 'a'=>$a])}}" method="POST" class="mb-0" enctype="multipart/form-data">
        @csrf
        <div class="p-4">
            <div class="grid grid-cols-4 gap-y-2 gap-x-10">
                <div>
                    <label for="" class="inputLabel">
                        Select Year
                    </label>
                    <select name="year" id="" class="inputField">
                        @for($year=(date('Y')+1); $year>=2010 ; $year--)
                            <option value="{{$year}}" {{$year==$manualLeaveCount->year ? 'selected' : ''}}>{{$year}}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-span-3"></div>
                @php $total=0; @endphp
                @foreach($leaveType as $item)
                    @if(in_array($item->id, [1, 2, 3]))
                        @php
                            $val = 0;
                            if ($item->id == 1) {
                                $val = $manualLeaveCount->casual_leave;
                            } elseif ($item->id == 2) {
                                $val = $manualLeaveCount->sick_leave;
                            } elseif ($item->id == 3) {
                                $val = $manualLeaveCount->annual_leave;
                            }
                        @endphp
                        <div class="">
                            <label for="" class="inputLabel">
                                {{$item->name}} Leave
                            </label>
                            <input type="number" name="leaveCount[{{$item->id}}]" value="{{$val}}" onkeyup="countLeaves()" class="inputField appearance-none">
                        </div>
                    @endif
                @endforeach
                <div class="">
                    <div class="">
                        <label for="" class="inputLabel">
                            Total
                        </label>
                        <input type="number" value="{{$manualLeaveCount->total}}" id="totalCountEdit" class="inputField appearance-none" readonly>
                    </div>

                </div>
                <div class="col-span-4">
                    <div class="">
                        <label for="" class="inputLabel">
                            Remarks
                        </label>
                        <textarea name="remarks" class="inputField" id="" cols="30" rows="3">{{$manualLeaveCount->remarks}}</textarea>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">

            </div>
        </div>
        <div class="">
            <div class="mt-5 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="largeModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    Cancel
                </button>
                <button type="submit" onsubmit="" class="submit-button">
                    Save
                </button>
            </div>
        </div>
    </form>
    <script>
        function countLeaves() {
            let totalLeaveCount = 0;

            $('input[name^="leaveCount"]').each(function() {
                let value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    totalLeaveCount += value;
                }
            });
            $('#totalCountEdit').val    (totalLeaveCount);
        }
    </script>
@endif
@if($a=='dsignature')
    {{-- Start Digital-signature Information --}}
    <form class="mb-0 bg-white dark:bg-neutral-800" action="{{route('employees.update', ['id'=>$employee->id, 'a'=>$a])}}" onsubmit="return validateEmployeeEditData('dsignature')" method="post" enctype="multipart/form-data">
        @csrf
        <div class="p-3 overflow-y-scroll max-h-[50vh]">
            <div>
                <label for="title" class="inputLabel">
                   Employee Full Name
                </label>
                <h1 class="font-bold italic text-base">{{$employee->full_name?? 'Employee Name Not Found'}}</h1>
            </div>
            <div>
                <label for="title" class="inputLabel">
                    Digital Signature
                </label>
                <input id="digital_signature" name="digital_signature" accept="image/*" value="{{$employee->digital_signature ?? ''}}" type="file" class="inputFieldBorder !py-2 w-full text-sm text-gray-500 file:me-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:focus:ring-red-600 file:focus:outline-none file:text-white hover:file:bg-blue-700 file:disabled:opacity-50 file:disabled:pointer-events-none dark:text-neutral-500 dark:file:bg-blue-500 dark:hover:file:bg-blue-400">
                <span class="error_msg" id="error_digital_signature"></span>
            </div>
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100 flex items-center justify-end gap-x-2 border-t px-4 py-3 dark:border-neutral-700">
            <button type="button" onclick="smallModal.close()" class="inline-flex items-center rounded-lg bg-black px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800" id="close-form-btn-for-dsignature">
                Close
            </button>
            <button type="submit" class="inline-flex items-center rounded-lg bg-red-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                Update
            </button>
        </div>
    </form>
    {{-- End Security Information --}}
@endif
@if($a=='security')
    {{-- Start Security Information --}}
    <div class="px-5 bg-white dark:bg-neutral-800 " id="security-details-edit">
        <form action="{{route('employees.update', ['id'=>$employee->id, 'a'=>$a])}}" onsubmit="return validateEmployeeEditData('security')" method="post">
            @csrf
            <div class="mt-3">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white text-center mb-2">Change Password</h2>
                <div class="z-10 flex items-center self-center justify-center px-4 md:px-0">
                    <div class="p-8 mx-auto rounded-lg shadow-xl shadow-red-600/30 sm:p-10 bg-slate-50 w-96">
                        <div class="space-y-3">
                            <div>
                                <label for="employeeCurrentPass" class="inputLabel">
                                    Current Password
                                </label>
                                <div class="relative" x-data="{ show: true }">
                                    <input id="employeeCurrentPass" name="current_pass" value="" autocomplete="current_pass"
                                        placeholder="Current Password" :type="show ? 'password' : 'text'"
                                        class=" w-full text-sm  px-4 py-3 bg-gray-100 focus:bg-gray-50 border rounded-lg focus:outline-none border-gray-300 focus:ring focus:ring-[1px] focus:ring-red-400 focus:border-transparent">
                                    <div class="absolute inset-y-0 right-0 flex items-center mr-3 text-sm leading-5">

                                        <svg @click="show = !show" :class="{ 'hidden': !show, 'block': show }"
                                            class="h-4 text-red-700" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            viewbox="0 0 576 512">
                                            <path fill="currentColor"
                                                d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                            </path>
                                        </svg>

                                        <svg @click="show = !show" :class="{ 'block': !show, 'hidden': show }"
                                            class="h-4 text-red-700" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            viewbox="0 0 640 512">
                                            <path fill="currentColor"
                                                d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z">
                                            </path>
                                        </svg>

                                    </div>
                                </div>
                                <span class="error_msg" id="error_employeeCurrentPass"></span>
                            </div>
                            <div>
                                <label for="employeeNewPass" class="inputLabel">
                                    New Password
                                </label>
                                <div class="relative" x-data="{ show: true }">
                                    <input id="employeeNewPass" name="new_pass" value="" autocomplete="new_pass"
                                        placeholder="New Password" :type="show ? 'password' : 'text'"
                                        class=" w-full text-sm  px-4 py-3 bg-gray-100 focus:bg-gray-50 border rounded-lg focus:outline-none border-gray-300 focus:ring focus:ring-[1px] focus:ring-red-400 focus:border-transparent">
                                    <div class="absolute inset-y-0 right-0 flex items-center mr-3 text-sm leading-5">

                                        <svg @click="show = !show" :class="{ 'hidden': !show, 'block': show }"
                                            class="h-4 text-red-700" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            viewbox="0 0 576 512">
                                            <path fill="currentColor"
                                                d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                            </path>
                                        </svg>

                                        <svg @click="show = !show" :class="{ 'block': !show, 'hidden': show }"
                                            class="h-4 text-red-700" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            viewbox="0 0 640 512">
                                            <path fill="currentColor"
                                                d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z">
                                            </path>
                                        </svg>

                                    </div>
                                </div>
                                <span class="error_msg" id="error_employeeNewPass"></span>
                            </div>
                            <div>
                                <label for="employeeRetypeNewPass" class="inputLabel">
                                    Retype New Password
                                </label>
                                <div class="relative" x-data="{ show: true }">
                                    <input id="employeeRetypeNewPass" name="retype_new_pass" value="" autocomplete="retype_new_pass"
                                        placeholder="Retype New Password" :type="show ? 'password' : 'text'"
                                        class=" w-full text-sm  px-4 py-3 bg-gray-100 focus:bg-gray-50 border rounded-lg focus:outline-none border-gray-300 focus:ring focus:ring-[1px] focus:ring-red-400 focus:border-transparent">
                                    <div class="absolute inset-y-0 right-0 flex items-center mr-3 text-sm leading-5">

                                        <svg @click="show = !show" :class="{ 'hidden': !show, 'block': show }"
                                            class="h-4 text-red-700" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            viewbox="0 0 576 512">
                                            <path fill="currentColor"
                                                d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                            </path>
                                        </svg>

                                        <svg @click="show = !show" :class="{ 'block': !show, 'hidden': show }"
                                            class="h-4 text-red-700" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            viewbox="0 0 640 512">
                                            <path fill="currentColor"
                                                d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z">
                                            </path>
                                        </svg>

                                    </div>
                                </div>
                                <span class="error_msg" id="error_employeeRetypeNewPass"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-end gap-x-2 border-t px-4 py-3 dark:border-neutral-700">
                    <button type="button" onclick="cancelEmployeeEdit('security')" class="inline-flex items-center rounded-lg bg-black px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800" id="close-form-btn-for-security">
                        Close
                    </button>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
    {{-- End Security Information --}}
@endif

@if($a=='select-department-head-form')
    @if(isset($allEmployees))
        <div class="flex items-center gap-2">
            <select name="" id="department-headList" class="">
                <option value="">Select Department Head</option>
                @foreach($allEmployees as $item)
                    <option value="{{$item->emp_id}}" {{$item->emp_id==$employee->department_head ? 'selected' : ''}}>{{$item->full_name}} - {{$item->emp_id}}</option>
                @endforeach
            </select>
            <button id="department-headSubmitButton" onclick="submitDepartmentHeadLineMangerSelection('department-head', '{{$employee->id}}')" class="size-6 aspect-square flex-grow-0 text-teal-600 hover:text-teal-700 bg-transparent hover:bg-teal-100 rounded-full duration-200">
                <i class="ti ti-check"></i>
            </button>
            <button id="department-headCancelButton" onclick="cancelDepartmentHeadLineMangerSelection('department-head')" class="size-6 aspect-square flex-grow-0 text-[#831b94] hover:text-red-700 bg-transparent hover:bg-red-100 rounded-full duration-200">
                <i class="ti ti-x"></i>
            </button>
        </div>
    @endif
@endif
@if($a=='select-line-manager-form')
    @if(isset($allEmployees))
        <div class="flex items-center gap-2">
            <select name="" id="line-managerList" class="">
                <option value="">Select Line Manager</option>
                @foreach($allEmployees as $item)
                    <option value="{{$item->emp_id}}" {{$item->emp_id==$employee->line_manager ? 'selected' : ''}}>{{$item->full_name}} - {{$item->emp_id}}</option>
                @endforeach
            </select>
            <button id="line-managerSubmitButton" onclick="submitDepartmentHeadLineMangerSelection('line-manager', '{{$employee->id}}')" class="size-6 aspect-square flex-grow-0 text-teal-600 hover:text-teal-700 bg-transparent hover:bg-teal-100 rounded-full duration-200">
                <i class="ti ti-check"></i>
            </button>
            <button id="line-managerCancelButton" onclick="cancelDepartmentHeadLineMangerSelection('line-manager')" class="size-6 aspect-square flex-grow-0 text-[#831b94] hover:text-red-700 bg-transparent hover:bg-red-100 rounded-full duration-200">
                <i class="ti ti-x"></i>
            </button>
        </div>
    @endif
@endif

