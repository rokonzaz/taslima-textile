@props(['data' => []])

@if(!empty($data))
    <div id="{{$data['id']??''}}Card" class="bg-white border shadow-md rounded-lg h-50 dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
        <div class="flex items-center justify-between px-2 @if(isset($data['export']) && $data['export']) pr-1 py-1 @else pr-1.5 py-1.5 @endif border-b rounded-t-lg dark:border-neutral-700">
            <h3 class="text-base font-bold text-gray-800 dark:text-white">
                {{$data['title']??''}} <span id="{{$data['id']??''}}Count" class="text-[#831b94]"></span>
            </h3>
            @if(isset($data['export']) && $data['export'])
                <div class="flex items-center justify-center font-medium">
                    <div class="dropdown dropdown-left">
                        <button type="button" tabindex="0" role="button" class="flex items-center justify-center text-sm font-semibold text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg size-7 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                            <svg class="flex-none text-gray-600 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                 height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="1" />
                                <circle cx="12" cy="5" r="1" />
                                <circle cx="12" cy="19" r="1" />
                            </svg>
                        </button>
                        <ul tabindex="0" class="dropdown-content z-[1] menu px-0 shadow-lg bg-base-100 border rounded-md !w-44">
                            <div class=" px-2">
                                <button type="button"
                                        onclick="fnExportReport('{{$data['id']??''}}List','pdf' ,'{{$data['id']??''}}List {{request('date')}}');"
                                        class="editUserBtn w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer"
                                >
                                    <i class="ti ti-file-type-pdf"></i>
                                    Download PDF
                                </button>

                                <button type="button"
                                        onclick="fnExportReport('{{$data['id']??''}}List','csv' ,'{{$data['id']??''}}List {{request('date')}}');"
                                        class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                    <i class="ti ti-file-type-csv"></i>
                                    Download CSV
                                </button>
                                <button type="button"
                                        onclick="fnExportReport('{{$data['id']??''}}List','xlsx' ,'{{$data['id']??''}}List {{request('date')}}');"
                                        class="w-full flex items-center gap-x-2.5 py-2 px-3 rounded-md text-xs text-gray-800 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white cursor-pointer">
                                    <i class="ti ti-file-type-xls"></i>
                                    Download EXCEL
                                </button>
                            </div>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
        <div id="{{$data['id']??''}}List">
            <div class="py-20 flex items-center justify-center">
                <span class="loading loading-dots loading-lg"></span>
            </div>
        </div>
    </div>
@endif
