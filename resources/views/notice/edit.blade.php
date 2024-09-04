<form action="{{ route('notice.update', $notice->id) }}" method="POST" class="mb-0" enctype="multipart/form-data" onsubmit="return validateFormRequest('e_', 'noticeRequest')">
    @csrf
    <div class="p-4 overflow-y-scroll max-h-[50vh]">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
            <div>
                <label for="noticeName" class="inputLabel">
                    Notice Title <span class="text-red-700">*</span>
                </label>
                <input type="text" id="e_notice_type" name="notice_type" value="{{ $notice->notice_type }}" class="inputFieldBorder" placeholder="Notice Type">
                <span class="error_msg" id="e_error_notice_type"></span>
            </div>

            <div>
                <label for="noticeDate" class="inputLabel">
                    Notice Date <span class="text-red-700">*</span>
                </label>
                <input type="date" id="e_notice_date" name="notice_date" value="{{ $notice->notice_date }}" class="inputFieldBorder">
                <span class="error_msg" id="e_error_notice_date"></span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
            <div>
                <label for="noticeFile" class="inputLabel">
                    Notice Attachment
                </label>
                <input type="file" id="notice_file" name="notice_file" value="{{ $notice->notice_file ?? '' }}" class="inputFieldBorder !py-2 w-full text-sm text-gray-500 file:me-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:disabled:opacity-50 file:disabled:pointer-events-none dark:text-neutral-500 dark:file:bg-blue-500 dark:hover:file:bg-blue-400">
            </div>

            <div>
                <label for="noticeBy" class="inputLabel">
                    Notice By <span class="text-red-700">*</span>
                </label>
                <input type="text" id="e_notice_by" name="notice_by" value="{{ $notice->notice_by }}" class="inputFieldBorder" placeholder="Notice By">
                <span class="error_msg" id="e_error_notice_by"></span>
            </div>
        </div>

        {{-- <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mt-2">
            <div>
                <label for="noticeDescription" class="inputLabel">
                    Notice Description <span class="text-red-700">*</span>
                </label>
                <textarea id="notice_description" name="notice_description" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $notice->notice_description }}</textarea>

            </div>
        </div> --}}
        <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 lg:gap-6 mt-2">
            <div>
                <label for="noticeDescription" class="inputLabel">
                    Notice Description <span class="text-red-700">*</span>
                </label>
                <textarea id="e_notice_description" name="notice_description" rows="4" class="inputFieldBorder">{{ $notice->notice_description }}</textarea>
                <span class="error_msg" id="e_error_notice_description"></span>
            </div>
        </div>
    </div>
    <div class="sticky bottom-0 z-50 bg-gray-100">
        <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
            <button type="button" onclick="largeModal.close()" class="cancel-button" data-hs-overlay="#edit-new-notice-modal">
                Cancel
            </button>
            <button type="submit" onsubmit="return validateDutySlot()" class="submit-button">
                Save
            </button>
        </div>
    </div>
</form>


