@props(['data'])

    <div class="card bg-white flex justify-center h-[100px] p-4 rounded-lg shadow-lg border relative">
        <div class="flex gap-4 items-center">
            <div class="bg-red-100 rounded-md size-12 aspect-square flex items-center justify-center">
                <span class="icon text-[#831b94] text-2xl">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-redux"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.54 7c-.805 -2.365 -2.536 -4 -4.54 -4c-2.774 0 -5.023 2.632 -5.023 6.496c0 1.956 1.582 4.727 2.512 6" /><path d="M4.711 11.979c-1.656 1.877 -2.214 4.185 -1.211 5.911c1.387 2.39 5.138 2.831 8.501 .9c1.703 -.979 2.875 -3.362 3.516 -4.798" /><path d="M15.014 19.99c2.511 0 4.523 -.438 5.487 -2.1c1.387 -2.39 -.215 -5.893 -3.579 -7.824c-1.702 -.979 -4.357 -1.235 -5.927 -1.07" /><path d="M10.493 9.862c.48 .276 1.095 .112 1.372 -.366a1 1 0 0 0 -.367 -1.365a1.007 1.007 0 0 0 -1.373 .366a1 1 0 0 0 .368 1.365z" /><path d="M9.5 15.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M15.5 14m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                </span>
            </div>
            <div>
                <p class="name title text-md font-medium mb-1">
                    {{$data->name}}
                </p>
                @php
                    $employeesCount=$data->employees->count();
                @endphp
                <div class="counting mb-0 text-sm">
                    <div class="flex items-center gap-1 mt-1">
                        <div class="flex -space-x-1">
                            @if($employeesCount>0)
                            <img class="inline-block size-4 rounded-full ring-1 ring-white dark:ring-neutral-900" src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80" alt="Image Description">
                            @endif
                            @if($employeesCount>1)
                                <img class="inline-block size-4 rounded-full ring-1 ring-white dark:ring-neutral-900" src="https://images.unsplash.com/photo-1531927557220-a9e23c1e4794?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80" alt="Image Description">
                            @endif
                            @if($employeesCount>2)
                                <img class="inline-block size-4 rounded-full ring-1 ring-white dark:ring-neutral-900" src="https://images.unsplash.com/photo-1541101767792-f9b2b1c4f127?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&&auto=format&fit=facearea&facepad=3&w=300&h=300&q=80" alt="Image Description">
                            @endif
                        </div>
                        <a href="{{route('employees.index', ['organization'=>'','department'=>$data->id])}}" class="cursor-pointer hover:text-[#831b94]"><span class="count text-md text-[#831b94] font-bold">{{$data->employees->count()}}</span> employees</a>
                    </div>
                </div>
            </div>

        </div>
        <div class="hs-dropdown inline-flex absolute top-1 right-1">
            <button id="hs-dropdown-with-icons" type="button" class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg text-gray-600  hover:bg-neutral-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>
            <div class="hs-dropdown-menu transition-[opacity,margin] !min-w-48 duration hs-dropdown-open:opacity-100 opacity-0 z-[101] hidden bg-white shadow-md border border-neutral-200 rounded-lg p-2 mt-2 divide-y divide-gray-200 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700" aria-labelledby="hs-dropdown-with-icons">
                <div class="py-2 first:pt-0 last:pb-0">
                    <a href="{{route('employees.index', ['organization'=>'','department'=>$data->id])}}" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                        <i class="ti ti-eye"></i>
                        View Employee List
                    </a>
                    <a href="javascript:(0)" onclick="editModalAjax('edit-department', 'smallModal', {{$data->id}})" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                        <i class="ti ti-edit"></i>
                        Edit
                    </a>
                    <a href="javascript:(0)" onclick="deletePopup('Delete Department?', '{{$data->name}}', '{{route('department.delete', ['id'=>$data->id])}}')" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                        <i class="fa-regular fa-trash-can"></i>
                        Delete
                    </a>
                </div>
            </div>
        </div>
    </div>


