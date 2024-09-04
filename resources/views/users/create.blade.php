<x-modals.small-modal id="createUserModal" title="Create a new User">
    <form class="mb-0" action="{{route('users.store')}}" method="POST"  onsubmit="return validateFormRequest('c_','user')" enctype="multipart/form-data">
        @csrf
        <div class="p-4 overflow-y-scroll max-h-[55vh]">
            <div class="grid grid-cols-1 gap-3 mt-2">
                <div class="">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                    <input type="text" id="c_create_name" name="name" value="" class="inputField" >
                    <span class="text-sm text-[#831b94]" id="c_error_create_name"></span>
                </div>
                <div class="">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" id="c_create_email" name="email" value="" class="inputField" onkeyup="liveValidateSingleData('user-email', 'c_create_email', 'error_create_email', 3)">
                    <span class="text-sm text-[#831b94]" id="error_create_email"></span>
                    <span class="text-sm text-[#831b94]" id="c_error_create_email"></span>
                </div>
                <div class="flex justify-between items-end mb-4 gap-4">
                    <div class="grow">
                        <label for="role" class="block text-gray-700 font-bold mb-2">User Role</label>
                        <select  id="create_role" name="role" class="inputField" >
                            @foreach($roles as $key=>$role)
                                @if($role->slug=='super-admin')
                                    @if(getUserRole()=='super-admin')
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endif
                                @else
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-x-4">
                        <label for="hs-checkbox-group-1" class="text-base font-semibold text-gray-700 dark:text-neutral-400">Is Active</label>
                        <input type="checkbox" checked name="status" class="shrink-0 mt-0.5 border-gray-400 rounded text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-checkbox-group-1">
                    </div>
                </div>
                <div class="">
                    <div class="col-span-2">
                        <label for="" class="block text-gray-700 font-bold mb-2 mt-2">Upload User Image</label>
                        <div class="bg-white rounded w-full mx-auto">
                            <div class="w-full flex items-center gap-4 max-w-md rounded-lg">
                                <div class="grow bg-gray-100 p-2 pt-0 text-center rounded-lg border-dashed border-2 border-gray-300 hover:border-blue-500 transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-md" id="create_dropzone">
                                    <label for="create_userImage" class="cursor-pointer flex items-center space-y-2">
                                        <svg class="w-12 h-12 text-gray-400 pt-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <span class="flex flex-col w-full mt-0">
                                    <span class="text-gray-600">Drag and drop your image here</span>
                                    <span class="text-gray-500 text-sm">(or click to select)</span>
                                </span>
                                    </label>
                                    <input type="file" name="user_image" id="create_userImage" accept=".png, .svg" class="hidden">
                                </div>
                                <div id="create_uploadedImagePreview" class="w-16 aspect-square flex items-center justify-center bg-gray-100 rounded border border-gray-300 relative">
                                    <span class="text-center text-gray-300 font-medium text-[32px]">
                                        <i class="fa-solid fa-user"></i>
                                    </span>
                                    <div id="removeImageContainer" class="absolute top-0 right-0 mt-1 mr-1 hidden">
                                        <div class="inline-block tooltip tooltip-left" data-tip="Remove Image">
                                            <button id="removeImageButton" class="actionBtn red">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="mt-3 text-center " id="create_fileList"></div>
                            <div id="create_errorMessage" class="text-red-500 text-center font-medium text-sm"></div>
                        </div>
                        <span class="text-[#831b94] text-sm font-medium" id="create_error_"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100">
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="createUserModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    Cancel
                </button>
                <button type="submit" class="submit-button">
                    Submit
                </button>

            </div>
        </div>
    </form>
</x-modals.small-modal>

<script>
    ////////UPLOAD IMAGE
    var create_dropzone = document.getElementById('create_dropzone');
    var create_userImage = document.getElementById('create_userImage');
    var create_fileList = document.getElementById('create_fileList');
    var create_errorMessageElement = document.getElementById('create_errorMessage');
    var create_uploadedImagePreview = document.getElementById('create_uploadedImagePreview');
    var removeImageContainer = document.getElementById('removeImageContainer');
    var removeImageButton = document.getElementById('removeImageButton');

    create_errorMessageElement.textContent = '';

    create_dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        create_dropzone.classList.add('border-blue-500', 'border-2');
    });

    create_dropzone.addEventListener('dragleave', () => {
        create_dropzone.classList.remove('border-blue-500', 'border-2');
    });

    create_dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        create_dropzone.classList.remove('border-blue-500', 'border-2');

        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    create_userImage.addEventListener('change', (e) => {
        const files = e.target.files;
        handleFiles(files);
    });

    function handleFiles(files) {
        create_fileList.innerHTML = '';
        create_errorMessageElement.textContent = ''; // Clear previous error message
        create_uploadedImagePreview.innerHTML = ''; // Clear previous image preview
        removeImageContainer.classList.add('hidden'); // Hide remove button initially

        for (const file of files) {
            if (file.type === 'image/png' || file.type === 'image/svg+xml') {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let maxLength = 30;
                    let truncatedString = file.name.length > maxLength ? file.name.substring(0, maxLength) + "..." : file.name;

                    let op = `
                        <div class="w-full bg-slate-700 rounded-md text-white px-1">
                            <p class="text-left">${truncatedString} (${formatBytes(file.size)})</p>
                        </div>
                    `;
                    create_fileList.innerHTML = op;

                    create_uploadedImagePreview.innerHTML = `<img class="w-full h-full object-cover" src="${e.target.result}" alt="Uploaded Image">`;
                    removeImageContainer.classList.remove('hidden'); // Show remove button
                };
                reader.readAsDataURL(file);
            } else {
                // Invalid file type, show error message
                create_errorMessageElement.textContent = 'Invalid file type. Allowed types: .png, .svg.';
                create_userImage.value = ''; // Clear the file input
            }
        }
    }

    removeImageButton.addEventListener('click', () => {
        create_uploadedImagePreview.innerHTML = `
            <span class="text-center text-gray-300 font-medium text-[32px]">
                <i class="fa-solid fa-user"></i>
            </span>
        `;
        create_fileList.innerHTML = '';
        create_userImage.value = ''; // Clear file input
        removeImageContainer.classList.add('hidden'); // Hide remove button
    });

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
</script>


