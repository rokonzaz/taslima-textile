
    <form class="mb-0" action="{{route('users.update', ['id'=>$user->id])}}" method="post"  onsubmit="return validateFormRequest('u_','user')" enctype="multipart/form-data">
        @csrf
        <div class="p-4 overflow-y-scroll max-h-[40vh]">
            @if(!$user->employee)
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                    <input type="text" id="u_create_name" name="name" value="{{$user->name}}" class="inputField" >
                    <span class="text-sm text-[#831b94]" id="u_error_create_name"></span>
                </div>
                @if(getUserRole()=='super-admin' || $user->id!=auth()->user()->id)
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" id="u_create_email" name="email" value="{{$user->email}}" class="inputField" >
                        <span class="text-sm text-[#831b94]"></span>
                        <span class="text-sm text-[#831b94]" id="u_error_create_email"></span>
                    </div>
                @endif
            @endif
            @if(getUserRole()=='super-admin' || $user->id!=auth()->user()->id)
                <div class="mb-4">
                    <label for="role" class="block text-gray-700 font-bold mb-2">User Role</label>
                    <select  id="role" name="role" class="inputField" >
                        @foreach($roles as $key=>$role)
                            @if($role->slug=='super-admin')
                                @if(getUserRole()=='super-admin')
                                    <option value="{{$role->id}}" {{$role->id==$user->role_id ? 'selected' : ''}}>{{$role->name}}</option>
                                @endif
                            @else
                                <option value="{{$role->id}}" {{$role->id==$user->role_id ? 'selected' : ''}}>{{$role->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            @else

            @endif

            @if(!$user->employee)
            <div class="">
                <div class="col-span-2">
                    <label for="" class="block text-gray-700 font-bold mb-2 mt-2">Upload User Image</label>
                    <div class="bg-white rounded w-full mx-auto">
                        <div class="w-full flex items-center gap-4 max-w-md rounded-lg">
                            <div class="grow bg-gray-100 p-2 pt-0 text-center rounded-lg border-dashed border-2 border-gray-300 hover:border-blue-500 transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-md" id="dropzone">
                                <label for="userImage" class="cursor-pointer flex items-center space-y-2">
                                    <svg class="w-12 h-12 text-gray-400 pt-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span class="flex flex-col w-full mt-0">
                                        <span class="text-gray-600">Drag and drop your image here</span>
                                        <span class="text-gray-500 text-sm">(or click to select)</span>
                                    </span>
                                </label>
                                <input type="file" name="user_image" id="userImage" accept=".png, .svg" class="hidden">
                            </div>
                            <div id="uploadedImagePreview" class="w-16 aspect-square flex items-center justify-center bg-gray-100 rounded border border-gray-300">
                                            <span class="text-center text-gray-300 font-medium text-[32px]">
                                                <i class="fa-solid fa-user"></i>
                                            </span>
                            </div>
                        </div>
                        <div class="mt-3 text-center " id="fileList"></div>
                        <div id="errorMessage" class="text-red-500 text-center font-medium text-sm"></div>
                    </div>
                    <span class="text-[#831b94] text-sm font-medium" id="error_"></span>
                </div>
            </div>
            @endif
            <div class="flex items-center gap-x-4">
                <label for="hs-checkbox-group-1" class="text-base font-semibold text-gray-700 dark:text-neutral-400">Is Active</label>
                <input type="checkbox" {{$user->is_active == 1 ? 'checked' : ''}} name="status" class="shrink-0 mt-0.5 border-gray-400 rounded text-[#831b94] focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-checkbox-group-1">
            </div>
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100">
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="smallModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    Cancel
                </button>
                <button type="submit" class="submit-button">
                    Update
                </button>
            </div>
        </div>
    </form>


<script>

    ////////UPLOAD IMAGE
    var dropzone = document.getElementById('dropzone');
    var userImage = document.getElementById('userImage');
    var fileList = document.getElementById('fileList');
    var errorMessageElement = document.getElementById('errorMessage');
    errorMessageElement.textContent='';

    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('border-blue-500', 'border-2');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-blue-500', 'border-2');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-blue-500', 'border-2');

        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    userImage.addEventListener('change', (e) => {
        const files = e.target.files;
        handleFiles(files);
    });

    function handleFiles(files) {
        fileList.innerHTML = '';
        errorMessageElement.textContent = ''; // Clear previous error message

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
                    $('#fileList').html(op);

                    $('#uploadedImagePreview').html(`<img class="w-full h-full object-cover" src="${e.target.result}" alt="Uploaded Image">`);
                };
                reader.readAsDataURL(file);
            } else {
                // Invalid file type, show error message
                errorMessageElement.textContent = 'Invalid file type. Allowed types: .png, .svg.';
                userImage.value = ''; // Clear the file input
            }
        }
    }
</script>
