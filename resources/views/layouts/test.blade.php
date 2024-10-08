{{--05 Create Employee Modal--}}
<div id="create-new-employee-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]' data-hs-overlay-keyboard="false">
<div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all md:max-w-4xl md:w-full m-3 md:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
    <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
        <!-- Header -->
        <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
            <h3 class="font-bold text-gray-800 dark:text-white">
                Create Employee
            </h3>
            <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:text-white hover:bg-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-[#831b94]" data-hs-overlay="#create-new-employee-modal">
                <span class="sr-only">Close</span>
                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>
        <!-- End Header -->
        <!-- Body -->
        <div class="p-4 overflow-y-auto">
            <!-- Card Section -->
            <div class="mx-auto">
                <!-- Card -->
                <div class="bg-white px-4 dark:bg-neutral-800">

                    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <nav class="relative z-0 flex border rounded-xl overflow-hidden dark:border-neutral-700" aria-label="Tabs" role="tablist">
                            <button type="button" class="hs-tab-active:border-b-red-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white relative dark:hs-tab-active:border-b-red-600 min-w-0 flex-1 bg-white first:border-s-0 border-s border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 focus:outline-none focus:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-l-neutral-700 dark:border-b-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-neutral-400 active" id="bar-with-underline-item-1" data-hs-tab="#bar-with-underline-1" aria-controls="bar-with-underline-1" role="tab">
                                Basic Information
                            </button>
                            <button type="button" class="hs-tab-active:border-b-red-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white relative dark:hs-tab-active:border-b-red-600 min-w-0 flex-1 bg-white first:border-s-0 border-s border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 focus:outline-none focus:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-l-neutral-700 dark:border-b-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-neutral-400" id="bar-with-underline-item-2" data-hs-tab="#bar-with-underline-2" aria-controls="bar-with-underline-2" role="tab">
                                Additional Information
                            </button>
                            <button type="button" class="hs-tab-active:border-b-red-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white relative dark:hs-tab-active:border-b-red-600 min-w-0 flex-1 bg-white first:border-s-0 border-s border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 focus:outline-none focus:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-l-neutral-700 dark:border-b-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-neutral-400" id="bar-with-underline-item-3" data-hs-tab="#bar-with-underline-3" aria-controls="bar-with-underline-3" role="tab">
                                Academic Information
                            </button>
                        </nav>

                        <div class="mt-3">
                            <div id="bar-with-underline-1" role="tabpanel" aria-labelledby="bar-with-underline-item-1">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                                    <div>
                                        <label for="employeeFullName" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">Employee Name</label>
                                        <input id="employeeFullName" name="full_name" type="text" class="inputField" placeholder="Maria">
                                    </div>
                                    <div>
                                        <label for="employeeId" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Employee Id
                                        </label>
                                        <input id="employeeId" name="employee_id" type="text" class="inputField" placeholder="#EMP0000001">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">

                                    <div>
                                        <label for="employeeEmail" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Email
                                        </label>
                                        <input id="employee_email" type="email" name="email" class="inputField" placeholder="maria@site.com">
                                    </div>
                                    <div>
                                        <label for="employeePassword" class="inputLabel">
                                            Password
                                        </label>
                                        <input id="employeePassword" name="password" type="password" class="inputField" placeholder="Enter password">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">

                                    <div>
                                        <label for="employeeJoiningDate" class="inputLabel">
                                            Joining Date
                                        </label>
                                        <input id="employeeJoiningDate" name="joining_date" type="date" class="inputField" placeholder="">
                                    </div>
                                    <div>
                                        <label for="" class="inputLabel">
                                            Select Organization
                                        </label>
                                        <select id="employeeOrganization" name="organization_name" class="inputField">
                                            <option selected="">Select Organization</option>
                                            <option>Nexdecade Technology</option>
                                            <option>M2M Communication</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">

                                    <div>
                                        <label for="employeeDesignation" class="inputLabel">
                                            Select Designation
                                        </label>
                                        <select id="employeeDesignation" name="designation" class="inputField">
                                            <option selected="">Open this select menu</option>
                                            <option>Web Developer</option>
                                            <option>Software Developer</option>
                                            <option>Lead Software Developer</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="employeePhoto" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Select Organization
                                        </label>
                                        <input type="file" name="profile_photo" id="employee_photo" class="inputField"/>
                                    </div>
                                </div>

                            </div>
                            <div id="bar-with-underline-2" class="hidden" role="tabpanel" aria-labelledby="bar-with-underline-item-2">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                                    <div>
                                        <label for="employeePhone" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Phone
                                        </label>
                                        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                        <input id="employeePhone" name="phone" type="text" class="inputField" placeholder="+x(xxx)xxx-xx-xx">
                                    </div>
                                    <div>
                                        <div>
                                            <label for="employeeEmmergencyPhone" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                                Emergency Contact Number
                                            </label>
                                            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                            <input id="employeeEmmergencyPhone" name="emergency_contact" type="text" class="inputField" placeholder="+x(xxx)xxx-xx-xx">
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">

                                    <div>
                                        <label for="emergencyContactRelation" class="inputLabel">
                                            Emergency Contact Relation
                                        </label>
                                        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                        <input id="emergencyContactRelation" name="emergency_contact_relation" type="text" class="inputField" placeholder="Enter Name">
                                    </div>
                                    <div>
                                        <label for="employeeBirthday" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Birthday
                                        </label>
                                        <div class="sm:flex">
                                            <input id="employeeBirthday" name="birth_year" type="date" class="inputField" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">

                                    <div>
                                        <label for="af-account-gender-checkbox" class="inputLabel">
                                            Gender
                                        </label>
                                        <ul class="flex flex-col sm:flex-row">
                                            <li class="inline-flex items-center gap-x-2.5 py-3 px-4 text-sm font-medium bg-neutral-100 dark:bg-neutral-700 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:border-neutral-700 dark:text-white">
                                                <div class="relative flex items-start w-full">
                                                    <div class="flex items-center h-5">
                                                        <input id="hs-horizontal-list-group-item-radio-1" value="Male" name="gender" type="radio" class="border-gray-300 rounded-full text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-500 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800" checked="">
                                                    </div>
                                                    <label for="hs-horizontal-list-group-item-radio-1" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-300">
                                                        Male
                                                    </label>
                                                </div>
                                            </li>

                                            <li class="inline-flex items-center gap-x-2.5 py-3 px-4 text-sm font-medium bg-neutral-100 dark:bg-neutral-700 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:border-neutral-700 dark:text-white">
                                                <div class="relative flex items-start w-full">
                                                    <div class="flex items-center h-5">
                                                        <input id="hs-horizontal-list-group-item-radio-2" value="Female" name="gender" type="radio" class="border-gray-300 rounded-full text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-500 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800">
                                                    </div>
                                                    <label for="hs-horizontal-list-group-item-radio-2" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-300">
                                                        Female
                                                    </label>
                                                </div>
                                            </li>

                                            <li class="inline-flex items-center gap-x-2.5 py-3 px-4 text-sm font-medium bg-neutral-100 dark:bg-neutral-700 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:border-neutral-700 dark:text-white">
                                                <div class="relative flex items-start w-full">
                                                    <div class="flex items-center h-5">
                                                        <input id="hs-horizontal-list-group-item-radio-3" value="Other" name="gender" type="radio" class="border-gray-300 rounded-full text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-500 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800">
                                                    </div>
                                                    <label for="hs-horizontal-list-group-item-radio-3" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-300">
                                                        Other
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div>
                                        <label for="employeeResume" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200 font-medium">
                                            Upload Resume
                                        </label>
                                        <input id="employeeResume" name="employee_resume" type="file" class="inputField" >
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">

                                    <div>
                                        <label for="employeePresentAddress" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Present Address
                                        </label>
                                        <textarea id="employeePresentAddress" name="present_address" class="inputField" rows="4" placeholder="Type your address..."></textarea>
                                    </div>
                                    <div>
                                        <label for="employeePermanentAddress" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Permanent Address
                                        </label>
                                        <textarea id="employeePermanentAddress" name="permanent_address" class="inputField" rows="4" placeholder="Type your address..."></textarea>
                                    </div>
                                </div>

                            </div>
                            <div id="bar-with-underline-3" class="hidden" role="tabpanel" aria-labelledby="bar-with-underline-item-3">

                                <h3 class="text-sm text-gray-800 dark:text-white capitalize">Education - One</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
                                    <div>
                                        <label for="institution_name_one" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Instituation Name
                                        </label>
                                        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                        <input id="institution_name_one" name="institution_name_one" type="text" class="inputField" placeholder="enter institution name">
                                    </div>
                                    <div>
                                        <div>
                                            <label for="degree_one" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                                Select Degree Type
                                            </label>
                                            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

                                            <select id="degree_one" name="degree_one" class="inputField">
                                                <option selected="">Select Degree</option>
                                                <option value="SSC">SSC</option>
                                                <option value="HSC">HSC</option>
                                                <option value="BSC">BSC</option>
                                                <option value="MSC">MSC</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <label for="department_one" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                                Department Name
                                            </label>
                                            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

                                            <input id="department_one" name="department_one" type="text" class="inputField" placeholder="enter department">
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
                                    <div>
                                        <label for="passing_year_one" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Passing Year
                                        </label>
                                        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                        <input id="passing_year_one" name="passing_year_one" type="text" class="inputField" placeholder="enter passing year">
                                    </div>
                                    <div>
                                        <div>
                                            <label for="result_one" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                                Result
                                            </label>
                                            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

                                            <input id="result_one" name="result_one" type="text" class="inputField" placeholder="enter passing year">
                                        </div>
                                    </div>

                                </div>

                                <div id="education-fields">
                                    <!-- Dynamic education fields will be added here -->

                                </div>

                                <div class="mt-4">
                                    <button id="add-education-field" class="inline-flex items-center px-2 py-2 text-sm font-semibold text-gray-500 border rounded-lg border-neutral-300 gap-x-2 hover:border-red-600 hover:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-gray-400 dark:hover:text-red-500 dark:hover:border-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                             class="bi bi-plus-circle-dotted" viewBox="0 0 16 16">
                                            <path
                                                d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                        </svg>Add Education</button>
                                </div>

                            </div>
                        </div>

                        <div class="mt-5 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                            <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-500 border rounded-lg border-neutral-300 gap-x-2 hover:border-red-600 hover:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-overlay="#create-new-employee-modal">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
                <!-- End Card -->
            </div>
            <!-- End Card Section -->
        </div>
        <!-- End Body -->
    </div>
</div>
</div>
{{--04 Create Employee Modal--}}



<h3 class="text-sm text-gray-800 dark:text-white">Education - 1</h3>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
    <div>
        <label for="instituationOne" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
            Instituation Name
        </label>
        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
        <input id="institution_name_one" name="institution_name_one" type="text" class="inputField" placeholder="enter institution name">
    </div>
    <div>
        <div>
            <label for="degreeType" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                Select Degree Type
            </label>
            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

            <select id="degree_one" name="degree_one" class="inputField">
                <option selected="">Select Degree</option>
                <option value="SSC">SSC</option>
                <option value="HSC">HSC</option>
                <option value="BSC">BSC</option>
                <option value="MSC">MSC</option>
            </select>
        </div>
    </div>
    <div>
        <div>
            <label for="department_one" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                Department Name
            </label>
            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

            <input id="department_one" name="department_one" type="text" class="inputField" placeholder="enter department">
        </div>
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
    <div>
        <label for="passing_year_one" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
            Passing Year
        </label>
        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
        <input id="passing_year_one" name="passing_year_one" type="text" class="inputField" placeholder="enter passing year">
    </div>
    <div>
        <div>
            <label for="result_one" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                Result
            </label>
            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

            <input id="result_one" name="result_one" type="text" class="inputField" placeholder="enter passing year">
        </div>
    </div>

</div>

<div class="mt-4"></div>
<h3 class="text-sm text-gray-800 dark:text-white">Education - 2</h3>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
    <div>
        <label for="instituationTwo" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
            Instituation Name
        </label>
        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
        <input id="instituationTwo" name="institution_name_two" type="text" class="inputField" placeholder="enter institution name">
    </div>
    <div>
        <div>
            <label for="degreeType2" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                Select Degree Type
            </label>
            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

            <select id="degreeType2" name="degree_two" class="inputField">
                <option selected="">Select Degree</option>
                <option value="SSC">SSC</option>
                <option value="HSC">HSC</option>
                <option value="BSC">BSC</option>
                <option value="MSC">MSC</option>
            </select>
        </div>
    </div>
    <div>
        <div>
            <label for="department_two" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                Department Name
            </label>
            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

            <input id="department_two" name="department_two" type="text" class="inputField" placeholder="enter department">
        </div>
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
    <div>
        <label for="passing_year_two" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
            Passing Year
        </label>
        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
        <input id="passing_year_two" name="passing_year_two" type="text" class="inputField" placeholder="enter passing year">
    </div>
    <div>
        <div>
            <label for="result_two" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                Result
            </label>
            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

            <input id="result_two" name="result_two" type="text" class="inputField" placeholder="enter passing year">
        </div>
    </div>

</div>


<div class="mt-4"></div>
<h3 class="text-sm text-gray-800 dark:text-white">Education - 3</h3>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
    <div>
        <label for="instituationthree" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
            Instituation Name
        </label>
        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
        <input id="instituationthree" name="instituationthree" type="text" class="inputField" placeholder="enter institution name">
    </div>
    <div>
        <div>
            <label for="degree_three" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                Select Degree Type
            </label>
            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

            <select id="degree_three" name="degree_three" class="inputField">
                <option selected="">Select Degree</option>
                <option value="SSC">SSC</option>
                <option value="HSC">HSC</option>
                <option value="BSC">BSC</option>
                <option value="MSC">MSC</option>
            </select>
        </div>
    </div>
    <div>
        <div>
            <label for="department_three" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                Department Name
            </label>
            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

            <input id="department_three" name="department_three" type="text" class="inputField" placeholder="enter department">
        </div>
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
    <div>
        <label for="passing_year_three" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
            Passing Year
        </label>
        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
        <input id="passing_year_three" name="passing_year_three" type="text" class="inputField" placeholder="enter passing year">
    </div>
    <div>
        <div>
            <label for="result_three" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                Result
            </label>
            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

            <input id="result_three" name="result_three" type="text" class="inputField" placeholder="enter passing year">
        </div>
    </div>

</div>

<div class="mt-4"></div>
<h3 class="text-sm text-gray-800 dark:text-white">Education - 4</h3>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
    <div>
        <label for="institution_name-four" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
            Instituation Name
        </label>
        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
        <input id="institution_name-four" name="institution_name_four" type="text" class="inputField" placeholder="enter institution name">
    </div>
    <div>
        <div>
            <label for="degree_four" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                Select Degree Type
            </label>
            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

            <select id="degree_four" name="degree_four" class="inputField">
                <option selected="">Select Degree</option>
                <option value="SSC">SSC</option>
                <option value="HSC">HSC</option>
                <option value="BSC">BSC</option>
                <option value="MSC">MSC</option>
            </select>
        </div>
    </div>
    <div>
        <div>
            <label for="department_four" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                Department Name
            </label>
            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

            <input id="department_four" name="department_four" type="text" class="inputField" placeholder="enter department">
        </div>
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
    <div>
        <label for="passing_year_four" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
            Passing Year
        </label>
        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
        <input id="passing_year_four" name="passing_year_four" type="text" class="inputField" placeholder="enter passing year">
    </div>
    <div>
        <div>
            <label for="result_four" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                Result
            </label>
            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

            <input id="result_four" name="result_four" type="text" class="inputField" placeholder="enter passing year">
        </div>
    </div>

</div>












{{--05 Create Employee Modal--}}
<div id="create-new-employee-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]' data-hs-overlay-keyboard="false">
<div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all md:max-w-4xl md:w-full m-3 md:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
    <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
        <!-- Header -->
        <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
            <h3 class="font-bold text-gray-800 dark:text-white">
                Create Employee
            </h3>
            <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:text-white hover:bg-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-[#831b94]" data-hs-overlay="#create-new-employee-modal">
                <span class="sr-only">Close</span>
                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>
        <!-- End Header -->
        <!-- Body -->
        <div class="p-4 overflow-y-auto">
            <!-- Card Section -->
            <div class="mx-auto">
                <!-- Card -->
                <div class="bg-white px-4 dark:bg-neutral-800">
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-neutral-200">
                            Profile
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-neutral-400">
                            Manage your name, password and account settings.
                        </p>
                    </div>
                    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Grid -->
                        <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">
                            <div class="sm:col-span-3">
                                <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Profile photo
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="flex items-center gap-5">
                                    <img class="inline-block size-16 rounded-full ring-2 ring-white dark:ring-neutral-900" src="https://preline.co/assets/img/160x160/img1.jpg" alt="Image Description">
                                    <div class="flex gap-x-2">
                                        <div>
                                            <input type="file" name="profile_photo" id="employee_photo" class="file-input file-input-bordered w-full max-w-xs"/>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-3">
                                <label for="employeeFullName" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Full name
                                </label>
                                <div class="hs-tooltip inline-block">
                                    <button type="button" class="hs-tooltip-toggle ms-1">
                                        <svg class="inline-block size-3 text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                        </svg>
                                    </button>
                                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible w-40 text-center z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded shadow-sm dark:bg-neutral-700" role="tooltip">
                                    Enter Full Name
                                    </span>
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="sm:flex">
                                    <input id="employeeFullName" name="full_name" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Maria">
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-3">
                                <label for="employeeEmail" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Email
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <input id="employee_email" type="email" name="email" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="maria@site.com">
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-3">
                                <label for="employeePassword" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Password
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="employeePassword" name="password" type="password" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Enter current password">
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-3">
                                <label for="employeeId" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Employee Id
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="employeeId" name="employee_id" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="#EMP0000001">
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-3">
                                <div class="inline-block">
                                    <label for="employeeJoiningDate" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Joining Date
                                    </label>
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="sm:flex">
                                    <input id="employeeJoiningDate" name="joining_date" type="date" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="">
                                </div>
                            </div>

                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="employeeDesignation" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Select Organization
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <select id="employeeDesignation" name="company_name" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                        <option selected="">Select Company</option>
                                        <option>Nexdecade Technology</option>
                                        <option>M2M Communication</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-3">
                                <label for="employeeDesignation" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Select Designation
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <select id="employeeDesignation" name="designation" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                        <option selected="">Open this select menu</option>
                                        <option>Web Developer</option>
                                        <option>Software Developer</option>
                                        <option>Lead Software Developer</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-3">
                                <div class="inline-block">
                                    <label for="employeePhone" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Phone
                                    </label>
                                    <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="sm:flex">
                                    <input id="employeePhone" name="phone" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="+x(xxx)xxx-xx-xx">
                                </div>
                            </div>
                            <!-- End Col -->


                            <!-- End Col -->
                            <div class="sm:col-span-3">
                                <div class="inline-block">
                                    <label for="employeeEmmergencyPhone" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Emergency Contact Number
                                    </label>
                                    <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="sm:flex">
                                    <input id="employeeEmergencyPhone" name="emergency_contact" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="+x(xxx)xxx-xx-xx">
                                </div>
                            </div>
                            <!-- End Col -->


                            <!-- End Col -->
                            <div class="sm:col-span-3">
                                <div class="inline-block">
                                    <label for="emergencyContactRelation" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Emergency Contact Relation
                                    </label>
                                    <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="sm:flex">
                                    <input id="emergencyContactRelation" name="emergency_contact_relation" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Enter name">
                                </div>
                            </div>
                            <!-- End Col -->


                            <div class="sm:col-span-3">
                                <div class="inline-block">
                                    <label for="employeeBirthday" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Birthday
                                    </label>
                                    <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="sm:flex">
                                    <input id="employeeBirthday" name="birth_year" type="date" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="">
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-3">
                                <label for="af-account-gender-checkbox" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Gender
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="sm:flex">
                                    <label for="af-account-gender-checkbox" class="flex py-2 px-3 w-full border border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                        <input type="radio" name="gender" value="Male" class="shrink-0 mt-0.5 border-gray-300 rounded-full text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-500 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800" id="af-account-gender-checkbox" checked>
                                        <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Male</span>
                                    </label>
                                    <label for="af-account-gender-checkbox-female" class="flex py-2 px-3 w-full border border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                        <input type="radio" name="gender" value="Female" class="shrink-0 mt-0.5 border-gray-300 rounded-full text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-500 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800" id="af-account-gender-checkbox-female">
                                        <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Female</span>
                                    </label>
                                    <label for="af-account-gender-checkbox-other" class="flex py-2 px-3 w-full border border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                        <input type="radio" name="gender" value="Other" class="shrink-0 mt-0.5 border-gray-300 rounded-full text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-500 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800" id="af-account-gender-checkbox-other">
                                        <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Other</span>
                                    </label>
                                </div>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-3">
                                <label for="employeeAddress" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Present Address
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <textarea id="employeeAddress" name="present_address" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" rows="6" placeholder="Type your address..."></textarea>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="permanentAddress" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Permanent Address
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <textarea id="employeeAddress" name="permanent_address" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" rows="6" placeholder="Type your address..."></textarea>
                            </div>
                            <!-- End Col -->

                            {{-- first Education Record --}}
                            <div class="sm:col-span-3">
                                <label for="instituationOne" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Instituation Name
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="institution_name_one" name="institution_name_one" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="degreeType" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Select Degree Type
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <select id="degree_one" name="degree_one" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                        <option selected="">Select Degree</option>
                                        <option value="SSC">SSC</option>
                                        <option value="HSC">HSC</option>
                                        <option value="BSC">BSC</option>
                                        <option value="MSC">MSC</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="department_one" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Department Name
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="department_one" name="department_one" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="passing_year_one" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Passing Year
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="passing_year_one" name="passing_year_one" type="date" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="result_one" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Result
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="result_one" name="result_one" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->


                            {{-- 2nd education recored --}}
                            <div class="sm:col-span-3">
                                <label for="instituationOne" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Instituation Name
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="institution_name_two" name="institution_name_two" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="employeeId" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Select Degree Type
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <select id="degree_two" name="degree_two" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                        <option selected="">Select Degree</option>
                                        <option value="SSC">SSC</option>
                                        <option value="HSC">HSC</option>
                                        <option value="BSC">BSC</option>
                                        <option value="MSC">MSC</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="department_one" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Department Name
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="department_two" name="department_two" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="passing_year_two" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Passing Year
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="passing_year_two" name="passing_year_two" type="date" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="result_two" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Result
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="result_two" name="result_two" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->



                            {{-- 3rd education recored --}}
                            <div class="sm:col-span-3">
                                <label for="instituationthree" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Instituation Name
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="institution_name_three" name="institution_name_three" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="selectDegreeType" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Select Degree Type
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <select id="degree_three" name="degree_three" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                        <option selected="">Select Degree</option>
                                        <option value="SSC">SSC</option>
                                        <option value="HSC">HSC</option>
                                        <option value="BSC">BSC</option>
                                        <option value="MSC">MSC</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="department_three" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Department Name
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="department_three" name="department_three" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="passing_year_three" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Passing Year
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="passing_year_three" name="passing_year_three" type="date" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="result_three" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Result
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="result_three" name="result_three" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->


                            {{-- 4th education recored --}}
                            <div class="sm:col-span-3">
                                <label for="instituationFour" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Instituation Name
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="institution_name-four" name="institution_name_four" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="selectDegreeType" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Select Degree Type
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <select id="degree_four" name="degree_four" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                        <option selected="">Select Degree</option>
                                        <option value="SSC">SSC</option>
                                        <option value="HSC">HSC</option>
                                        <option value="BSC">BSC</option>
                                        <option value="MSC">MSC</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="department_four" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Department Name
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="department_four" name="department_four" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="passing_year_three" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Passing Year
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="passing_year_four" name="passing_year_four" type="date" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->

                            <div class="sm:col-span-3">
                                <label for="result_four" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                    Result
                                </label>
                            </div>
                            <!-- End Col -->
                            <div class="sm:col-span-9">
                                <div class="space-y-2">
                                    <input id="result_four" name="result_four" type="text" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>
                            <!-- End Col -->




                        </div>
                        <!-- End Grid -->
                        <div class="mt-5 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                            <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-500 border rounded-lg border-neutral-300 gap-x-2 hover:border-red-600 hover:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-overlay="#create-new-employee-modal">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Create a new employee
                            </button>
                        </div>
                    </form>
                </div>
                <!-- End Card -->
            </div>
            <!-- End Card Section -->
        </div>
        <!-- End Body -->
    </div>
</div>
</div>
{{--04 Create Employee Modal--}}





{{--06 update Employee Modal--}}
@isset($employee)
<div id="edit-employee-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]' data-hs-overlay-keyboard="false">
<div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all md:max-w-4xl md:w-full m-3 md:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
    <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
        <!-- Header -->
        <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
            <h3 class="font-bold text-gray-800 dark:text-white">
                Create Employee
            </h3>
            <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:text-white hover:bg-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-[#831b94]" data-hs-overlay="#edit-employee-modal">
                <span class="sr-only">Close</span>
                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>
        <!-- End Header -->
        <!-- Body -->
        <div class="p-4 overflow-y-auto">
            <!-- Card Section -->
            <div class="mx-auto">
                <!-- Card -->
                <div class="bg-white px-4 dark:bg-neutral-800">

                    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <nav class="relative z-0 flex border rounded-xl overflow-hidden dark:border-neutral-700" aria-label="Tabs" role="tablist">
                            <button type="button" class="hs-tab-active:border-b-red-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white relative dark:hs-tab-active:border-b-red-600 min-w-0 flex-1 bg-white first:border-s-0 border-s border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 focus:outline-none focus:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-l-neutral-700 dark:border-b-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-neutral-400 active" id="bar-with-underline-item-1" data-hs-tab="#bar-with-underline-1" aria-controls="bar-with-underline-1" role="tab">
                                Basic Information
                            </button>
                            <button type="button" class="hs-tab-active:border-b-red-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white relative dark:hs-tab-active:border-b-red-600 min-w-0 flex-1 bg-white first:border-s-0 border-s border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 focus:outline-none focus:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-l-neutral-700 dark:border-b-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-neutral-400" id="bar-with-underline-item-2" data-hs-tab="#bar-with-underline-2" aria-controls="bar-with-underline-2" role="tab">
                                Additional Information
                            </button>
                            <button type="button" class="hs-tab-active:border-b-red-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white relative dark:hs-tab-active:border-b-red-600 min-w-0 flex-1 bg-white first:border-s-0 border-s border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 focus:outline-none focus:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-l-neutral-700 dark:border-b-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-neutral-400" id="bar-with-underline-item-3" data-hs-tab="#bar-with-underline-3" aria-controls="bar-with-underline-3" role="tab">
                                Academic Information
                            </button>
                        </nav>

                        <div class="mt-3">
                            <div id="bar-with-underline-1" role="tabpanel" aria-labelledby="bar-with-underline-item-1">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                                    <div>
                                        <label for="employeeFullName" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">Employee Name</label>
                                        <input id="employeeFullName" name="full_name" type="text" class="inputField" value="{{ $employee->full_name }}">
                                    </div>
                                    <div>
                                        <label for="employeeId" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Employee Id
                                        </label>
                                        <input id="employeeId" name="employee_id" type="text" class="inputField" value="{{ $employee->employee_id }}">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">

                                    <div>
                                        <label for="employeeEmail" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Email
                                        </label>
                                        <input id="employee_email" type="email" name="email" class="inputField" value="{{ $employee->email }}">
                                    </div>
                                    <div>
                                        <label for="employeePassword" class="inputLabel">
                                            Password
                                        </label>
                                        <input id="employeePassword" name="password" type="password" class="inputField" value="{{ $employee->password }}">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">

                                    <div>
                                        <label for="employeeJoiningDate" class="inputLabel">
                                            Joining Date
                                        </label>
                                        <input id="employeeJoiningDate" name="joining_date" type="date" class="inputField" value="{{ $employee->joining_date }}">
                                    </div>
                                    <div>
                                        <label for="" class="inputLabel">
                                            Select Company
                                        </label>
                                        <select id="employeeCompany" name="company_name" class="inputField">
                                            <option value="" disabled>Select Company</option>
                                            <option value="Nexdecade Technology" @if($employee->organization_name == "Nexdecade Technology") selected @endif>Nexdecade Technology</option>
                                            <option value="M2M Communication" @if($employee->organization_name == "M2M Communication") selected @endif>M2M Communication</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">

                                    <div>
                                        <label for="employeeDesignation" class="inputLabel">
                                            Select Designation
                                        </label>
                                        <select id="employeeDesignation" name="designation" class="inputField">
                                            <option selected="">Open this select menu</option>
                                            <option value="Web Developer" @if($employee->designation == "Web Developer") selected @endif>Web Developer</option>
                                            <option value="Software Developer" @if($employee->designation == "Software Developer") selected @endif>Software Developer</option>
                                            <option value="Lead Software Developer" @if($employee->designation == "Lead Software Developer") selected @endif>Lead Software Developer</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="employeePhoto" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Upload Photo
                                        </label>
                                        <input type="file" name="profile_photo" id="employee_photo" class="inputField" value="{{ $employee->profile_photo }}"/>
                                    </div>
                                </div>

                            </div>
                            <div id="bar-with-underline-2" class="hidden" role="tabpanel" aria-labelledby="bar-with-underline-item-2">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                                    <div>
                                        <label for="employeePhone" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Phone
                                        </label>
                                        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                        <input id="employeePhone" name="phone" type="text" class="inputField" value="{{ $employee->phone }}">
                                    </div>
                                    <div>
                                        <div>
                                            <label for="employeeEmmergencyPhone" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                                Emergency Contact Number
                                            </label>
                                            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                            <input id="employeeEmmergencyPhone" name="emergency_contact" type="text" class="inputField" value="{{ $employee->emergency_contact }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">

                                    <div>
                                        <label for="emergencyContactRelation" class="inputLabel">
                                            Emergency Contact Relation
                                        </label>
                                        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                        <input id="emergencyContactRelation" name="emergency_contact_relation" type="text" class="inputField" value="{{ $employee->emergency_contact_relation }}">
                                    </div>
                                    <div>
                                        <label for="employeeBirthday" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Birthday
                                        </label>
                                        <div class="sm:flex">
                                            <input id="employeeBirthday" name="birth_year" type="date" class="inputField" value="{{ $employee->birth_year }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">

                                    <div>
                                        <label for="af-account-gender-checkbox" class="inputLabel">
                                            Gender
                                        </label>
                                        <ul class="flex flex-col sm:flex-row">
                                            <li class="inline-flex items-center gap-x-2.5 py-3 px-4 text-sm font-medium bg-neutral-100 dark:bg-neutral-700 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:border-neutral-700 dark:text-white">
                                                <div class="relative flex items-start w-full">
                                                    <div class="flex items-center h-5">
                                                        <input id="hs-horizontal-list-group-item-radio-1" value="Male" name="gender" type="radio" class="border-gray-300 rounded-full text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-500 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800" checked="">
                                                    </div>
                                                    <label for="hs-horizontal-list-group-item-radio-1" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-300">
                                                        Male
                                                    </label>
                                                </div>
                                            </li>

                                            <li class="inline-flex items-center gap-x-2.5 py-3 px-4 text-sm font-medium bg-neutral-100 dark:bg-neutral-700 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:border-neutral-700 dark:text-white">
                                                <div class="relative flex items-start w-full">
                                                    <div class="flex items-center h-5">
                                                        <input id="hs-horizontal-list-group-item-radio-2" value="Female" name="gender" type="radio" class="border-gray-300 rounded-full text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-500 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800">
                                                    </div>
                                                    <label for="hs-horizontal-list-group-item-radio-2" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-300">
                                                        Female
                                                    </label>
                                                </div>
                                            </li>

                                            <li class="inline-flex items-center gap-x-2.5 py-3 px-4 text-sm font-medium bg-neutral-100 dark:bg-neutral-700 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg sm:-ms-px sm:mt-0 sm:first:rounded-se-none sm:first:rounded-es-lg sm:last:rounded-es-none sm:last:rounded-se-lg dark:border-neutral-700 dark:text-white">
                                                <div class="relative flex items-start w-full">
                                                    <div class="flex items-center h-5">
                                                        <input id="hs-horizontal-list-group-item-radio-3" value="Other" name="gender" type="radio" class="border-gray-300 rounded-full text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-500 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800">
                                                    </div>
                                                    <label for="hs-horizontal-list-group-item-radio-3" class="ms-3 block w-full text-sm text-gray-600 dark:text-neutral-300">
                                                        Other
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div>
                                        <label for="employeeResume" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200 font-medium">
                                            Upload Resume
                                        </label>
                                        <input id="employeeResume" name="employee_resume" type="file" class="inputField" >
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">

                                    <div>
                                        <label for="employeePresentAddress" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Present Address
                                        </label>
                                        <textarea id="employeePresentAddress" name="present_address" class="inputField" rows="4" placeholder="Type your address...">{{ $employee->present_address }}</textarea>
                                    </div>
                                    <div>
                                        <label for="employeePermanentAddress" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Permanent Address
                                        </label>
                                        <textarea id="employeePermanentAddress" name="permanent_address" class="inputField" rows="4" placeholder="Type your address...">{{ $employee->permanent_address }}</textarea>
                                    </div>
                                </div>

                            </div>
                            <div id="bar-with-underline-3" class="hidden" role="tabpanel" aria-labelledby="bar-with-underline-item-3">

                                <h3 class="text-sm text-gray-800 dark:text-white capitalize">Education - One</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
                                    <div>
                                        <label for="institution_name_one" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Instituation Name
                                        </label>
                                        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                        <input id="institution_name_one" name="institution_name_one" type="text" class="inputField" value="{{ $employee->institution_name_one }}">
                                    </div>
                                    <div>
                                        <div>
                                            <label for="degree_one" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                                Select Degree Type
                                            </label>
                                            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

                                            <select id="degree_one" name="degree_one" class="inputField">
                                                <option selected="">Select Degree</option>
                                                <option value="SSC" @if($employee->degree_one == "SSC") selected @endif>SSC</option>
                                                <option value="HSC" @if($employee->degree_one == "HSC") selected @endif>HSC</option>
                                                <option value="BSC" @if($employee->degree_one == "BSC") selected @endif>BSC</option>
                                                <option value="MSC" @if($employee->degree_one == "MSC") selected @endif>MSC</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <label for="department_one" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                                Department Name
                                            </label>
                                            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

                                            <input id="department_one" name="department_one" type="text" class="inputField" value="{{ $employee->department_one }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
                                    <div>
                                        <label for="passing_year_one" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                            Passing Year
                                        </label>
                                        <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>
                                        <input id="passing_year_one" name="passing_year_one" type="date" class="inputField" value="{{ $employee->passing_year_one }}">
                                    </div>
                                    <div>
                                        <div>
                                            <label for="result_one" class="inline-block text-sm  font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                                                Result
                                            </label>
                                            <span class="text-sm text-gray-400 dark:text-neutral-600">(Optional)</span>

                                            <input id="result_one" name="result_one" type="text" class="inputField" value="{{ $employee->result_one }}">
                                        </div>
                                    </div>

                                </div>

                                <div id="education-fields">
                                    <!-- Dynamic education fields will be added here -->

                                </div>

                                <div class="mt-4">
                                    <button id="add-education-field" class="inline-flex items-center px-2 py-2 text-sm font-semibold text-gray-500 border rounded-lg border-neutral-300 gap-x-2 hover:border-red-600 hover:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-gray-400 dark:hover:text-red-500 dark:hover:border-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                             class="bi bi-plus-circle-dotted" viewBox="0 0 16 16">
                                            <path
                                                d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                        </svg>Add Education</button>
                                </div>

                            </div>
                        </div>

                        <div class="mt-5 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                            <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-500 border rounded-lg border-neutral-300 gap-x-2 hover:border-red-600 hover:text-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-overlay="#edit-employee-modal">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
                <!-- End Card -->
            </div>
            <!-- End Card Section -->
        </div>
        <!-- End Body -->
    </div>
</div>
</div>
@endisset
{{--06 update Employee Modal--}}
