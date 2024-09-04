<form action="{{ route('notice.list', $notice->id) }}" method="POST" class="mb-0" enctype="multipart/form-data"
    onsubmit="return true">
    @csrf
    <div class="p-4 overflow-y-scroll max-h-[70vh]">
        <div id="selectedCountSection" class="">
            <p class="text-base text-gray-500 ms-3 dark:text-neutral-400 font-medium">Selected: (<span class="font-bold"
                    id="selectedCount">0</span>) employees</p>
            <div class="grid sm:grid-cols-4 gap-2" id="selectedItems"></div>
        </div>
        <hr class="my-2" />
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
            <div>
                <input type="text" class="inputFieldCompact" id="searchKeyX" placeholder="Type to search...">
            </div>
            <div>
                <select id="organization" name="select-filter" class="inputFieldCompact select2">
                    <option value="">Organization (All)</option>
                    @foreach ($organizations as $item)
                        <option value="{{ $item->id }}"
                            {{ request()->has('organization') ? request('organization') : '' }}>{{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select id="department" class="inputFieldCompact select2">
                    <option value="">Department (All)</option>
                    @foreach ($departments as $item)
                        <option value="{{ $item->id }}" @if (request()->has('department') && request('department') == $item->id) selected @endif>
                            {{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 lg:gap-6 mt-2">
            <div>
                <div class="flex justify-between">
                    <label for="selectAllCheckboxes"
                        class="flex items-center p-2.5 w-72 mb-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                        <input type="checkbox" id="selectAllCheckboxes"
                            class="shrink-0 mt-0.5 border-gray-200 rounded text-[#831b94] focus:ring-red-600 checked:bg-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800">
                        <span class="text-base text-gray-500 ms-3 dark:text-neutral-400 font-bold">Select All Employees
                            (<span id="totalCount" class="text-[#831b94]">{{ $employees->count() }}</span>)</span>
                    </label>
                </div>
                <div class="grid sm:grid-cols-3 gap-2" id="UserList">
                    @foreach ($employees as $item)
                        <label for="notice_{{ $item->id }}"
                            class="flex items-center gap-4 p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                            <input type="checkbox" id="notice_{{ $item->id }}" name="emp_id[]"
                                value="{{ $item->id }}"
                                class="userCheckbox shrink-0 mt-0.5 border-gray-200 rounded text-[#831b94] focus:ring-red-600 checked:bg-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800">
                            <div class="flex flex-col items-center gap-1 justify-center grow">
                                <span
                                    class="text-sm text-gray-500 ms-3 dark:text-neutral-400">{{ $item->emp_id ?? '' }}</span>
                                <span
                                    class="text-sm text-gray-500 ms-3 dark:text-neutral-400">{{ $item->full_name ?? 'N/A' }}</span>
                                <span
                                    class="text-sm text-gray-500 ms-3 dark:text-neutral-400">{{ $item->email ? $item->email : ($item->personal_email ? $item->personal_email : 'N/A') }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div
        class="sticky bottom-0 z-50 bg-gray-100 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
        <button type="button" onclick="largeModal.close()" class="cancel-button">
            Cancel
        </button>
        <button type="submit" class="submit-button">
            Send Mail
        </button>
    </div>
</form>
<script>
    $(document).ready(function() {
        var $searchKey = $('#searchKeyX');
        var $department = $('#department');
        var $organization = $('#organization');
        var $userList = $('#UserList');
        var $totalCount = $('#totalCount');
        var $selectAllCheckboxes = $('#selectAllCheckboxes');
        var $selectedItems = $('#selectedItems');
        var $selectedCount = $('#selectedCount');

        function showLoader() {
            $userList.html(
                '<div class="col-span-4 flex items-center justify-center p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-600 checked:bg-[#831b94] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"><span class="loading loading-dots loading-lg text-[#831b94]"></span></div>'
                );
        }

        function fetchEmployees(searchText = '', organization = '', department = '') {
            showLoader();

            let url = "{{ route('employees.data') }}";
            let params = {
                search: {
                    value: searchText
                },
                organization: organization,
                department: department
            };

            $.ajax({
                url: url,
                method: 'GET',
                data: params,
                success: function(res) {
                    let userListHtml = '';
                    if (res.length === 0) {
                        $totalCount.html(`${res.length}`);
                        userListHtml = `<label class="col-span-4 flex items-center justify-center p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-600 checked:bg-[#831b94] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                    <span class="text-sm text-[#831b94] text-center font-semibold dark:text-neutral-400">No employee available in this department.</span>
                                </label>`;
                    } else {
                        $totalCount.html(`${res.length}`);
                        userListHtml = res.map(user => {
                            const selectedItem = $selectedItems.find(`.selected-item[data-id="${user.id}"]`);
                            const isSelected = selectedItem.length && selectedItem.data('id') === user.id;

                            console.log(isSelected.length); // Logging the matching selected item
                            return `
                                <label for="notice_${user.id}" class="flex items-center p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                                    <input type="checkbox" id="notice_${user.id}" name="emp_id[]" value="${user.id}" class="userCheckbox shrink-0 mt-0.5 border-gray-200 rounded text-[#831b94] focus:ring-red-600 checked:bg-[#831b94] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800" ${isSelected? 'checked' : ''}>
                                    <div class="flex flex-col items-center justify-center gap-1 grow">
                                        <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">${user.emp_id ?? ''}</span>
                                        <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">${user.full_name ?? ''}</span>
                                        <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">${user.email ? user.email : (user.personal_email ? user.personal_email : 'N/A')}</span>
                                    </div>
                                </label>
                            `;
                        }).join('');
                    }

                    $userList.html(userListHtml);

                    $selectAllCheckboxes.prop('checked', false);
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                }
            });
        }

        $('#searchKeyX').on('keyup', debounce(function() {
            fetchEmployees($searchKey.val().trim(), $organization.val(), $department.val());
        }, 500));

        $('#department, #organization').change(function() {
            fetchEmployees($searchKey.val().trim(), $organization.val(), $department.val());
        });



        $('#selectAllCheckboxes').change(function() {
    const isChecked = $(this).is(':checked');
    $userList.find('.userCheckbox').each(function() {
        const userId = $(this).val();
        const selectedItem = $selectedItems.find(`.selected-item[data-id="${userId}"]`);

        if (isChecked && selectedItem.length === 0) {
            // If checked and not already in selectedItems, add it
            $(this).prop('checked', true).trigger('change');
        } else if (!isChecked) {
            // If unchecked, remove it
            $(this).prop('checked', false).trigger('change');
        }
    });
});

$userList.on('change', '.userCheckbox', function() {
    const userId = $(this).val();
    const isChecked = $(this).is(':checked');
    const userLabel = $(this).closest('label');
    const userHtml = `
        <div class="selected-item relative flex items-center justify-between p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" data-id="${userId}">
            ${userLabel.html().replace(/<input[^>]*>/, '<input type="hidden" name="emp_id[]" value="' + userId + '">')} <!-- Removes the checkbox -->
            <div class="inline-block tooltip tooltip-left absolute right-2 top-2"  data-tip="Remove">
                <button class="actionBtn red removeItem">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </div>
        </div>
    `;

    if (isChecked) {
        if ($selectedItems.find(`.selected-item[data-id="${userId}"]`).length === 0) {
            $selectedItems.append(userHtml);
        }
    } else {
        $selectedItems.find(`.selected-item[data-id="${userId}"]`).remove();
    }

    updateSelectedCount();
});

$selectedItems.on('click', '.removeItem', function() {
    const $item = $(this).closest('.selected-item');
    const userId = $item.data('id');

    $userList.find(`.userCheckbox[value="${userId}"]`).prop('checked', false).trigger('change');
    $item.remove();

    updateSelectedCount();
});

        function updateSelectedCount() {
            const count = $selectedItems.find('.selected-item').length;
            $selectedCount.text(count);
        }
    });


    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
</script>
