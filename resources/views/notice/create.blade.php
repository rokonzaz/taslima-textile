<x-modals.large-modal id="createNoticeModal" title="Create Notice">
    <form action="{{ route('notice.store') }}" method="POST" class="mb-0" enctype="multipart/form-data" onsubmit="return validateFormRequest('c_', 'noticeRequest')">
        @csrf
        <div class="p-4 overflow-y-scroll max-h-[60vh]">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="noticeName" class="inputLabel">
                        Notice Title <span class="text-red-700">*</span>
                    </label>
                    <input type="text" id="c_notice_type" name="notice_type" class="inputFieldBorder" placeholder="Notice Type">
                    <span class="error_msg" id="c_error_notice_type"></span>
                </div>

                <div>
                    <label for="noticeDate" class="inputLabel">
                        Notice Date <span class="text-red-700">*</span>
                    </label>
                    <input type="date" id="c_notice_date" name="notice_date" value="{{date('Y-m-d')}}" class="inputFieldBorder">
                    <span class="error_msg" id="c_error_notice_date"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="noticeFile" class="inputLabel">
                        Notice Attachment
                    </label>
                    {{-- <input type="file" id="c_notice_file" name="notice_file" class="inputFieldBorder"> --}}
                    <input type="file" id="c_notice_file" name="notice_file" class="inputFieldBorder !py-1.5 w-full text-sm text-gray-500
                                file:me-4 file:py-1 file:px-3
                                file:rounded-lg file:border-0[]
                                file:text-sm file:font-semibold
                                file:bg-blue-600 file:text-white
                                hover:file:bg-blue-700
                                file:disabled:opacity-50 file:disabled:pointer-events-none
                                dark:text-neutral-500
                                dark:file:bg-blue-500
                                dark:hover:file:bg-blue-400
                              ">
                </div>

                <div>
                    <label for="noticeBy" class="inputLabel">
                        Notice By <span class="text-red-700">*</span>
                    </label>
                    <input type="text" id="c_notice_by" name="notice_by" class="inputFieldBorder" placeholder="Notice By">
                    <span class="error_msg" id="c_error_notice_by"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="noticeDescription" class="inputLabel">
                        Notice Description <span class="text-red-700">*</span>
                    </label>
                    <textarea id="c_notice_description" name="notice_description" rows="4" class="inputFieldBorder"></textarea>
                    <span class="error_msg" id="c_error_notice_description"></span>
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100">
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="createNoticeModal.close()" class="cancel-button">
                    Cancel
                </button>
                <button type="submit" onsubmit="return validateNoticeData()" class="submit-button">
                    Save
                </button>
            </div>
        </div>
    </form>
</x-modals.large-modal>


