@extends('layouts.app')
@section('title', 'Designation')
@section('pageTitle', 'Designations')
@section('breadcumb')
    <ol class="flex items-center mt-2 mr-2 whitespace-nowrap">
        <li class="inline-flex items-center">
            <a class="flex items-center text-sm text-gray-500 hover:text-[#831b94] dark:hover:text-[#831b94] focus:outline-none focus:text-neutral-600 dark:focus:text-neutral-600"
               href="#">
                <svg class="flex-shrink-0 me-3 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Home
            </a>
            <svg class="flex-shrink-0 mx-2 overflow-visible text-gray-400 size-4 dark:text-neutral-600 dark:hover:text-[#831b94]"
                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6"></path>
            </svg>
        </li>

        <li class="inline-flex items-center">
            <a class="flex items-center text-sm text-gray-500 hover:text-[#831b94] dark:hover:text-[#831b94] focus:outline-none focus:text-neutral-600 dark:focus:text-neutral-600"
               href="#">
                Settings
            </a>
            <svg class="flex-shrink-0 mx-2 overflow-visible text-gray-400 size-4 dark:text-neutral-600 dark:hover:text-[#831b94]"
                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6"></path>
            </svg>
        </li>

        <li class="inline-flex items-center text-sm font-semibold text-[#831b94] truncate dark:hover:text-[#831b94] dark:text-[#831b94]"
            aria-current="page">
            Designation
        </li>
    </ol>
@endsection
@section('content')
    <div class="mt-4 ">
        <div class="flex flex-col p-2 border rounded-lg dark:bg-neutral-800 border-neutral-200 dark:border-neutral-700">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 w-full align-middle">
                    <div class="overflow-x-auto">
                        <div>
                            <h3 class="m-2 text-xl font-semibold text-gray-800 dark:text-white">Create New Designation</h3>
                        </div>
                        <form
                            class="items-center w-full"
                            action="{{ route('designation.store') }}" method="POST" enctype="multipart/form-data"
                        >
                            @csrf
                            <div class="m-2">

                                <div class="max-w-full mt-3">
                                    <label for="designation_name" class="block mb-2 text-sm font-medium text-gray-800 dark:text-white">Designation Name</label>
                                    <input class="block w-full px-4 py-3 text-sm text-gray-800 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                        id="designation_name"
                                        name="name"
                                        type="text"
                                        autocomplete="off"
                                        placeholder="Designation Name">
                                </div>

                                <div class="max-w-full mt-3">
                                    <label for="is_active" class="block mb-2 text-sm font-medium text-gray-800 dark:text-white">Is Active</label>
                                    <div class="relative" id="is_active">
                                        <div class="relative hs-select" >
                                            <select  name="is_active" class="block w-full px-4 py-3 text-sm text-gray-800 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 -800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                                <option value="1">
                                                    Yes
                                                </option>
                                                <option value="0" >
                                                    No
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end py-4 border-t gap-x-2 dark:border-neutral-700">

                                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-[#831b94] dark:hover:bg-red-700 dark:focus:ring-red-800">
                                        Create Desgination
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection






