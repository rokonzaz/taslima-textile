@props(['id'=>'smallModalExample', 'title'=>'Title'])
<dialog id="{{$id}}" class="modal">
    <div class="modal-box p-0 relative border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 overflow-hidden">
        <div class="flex justify-between items-center border-b sticky top-0 z-50 bg-gray-100 dark:border-neutral-700 p-2 pl-4">
            <div id="{{$id}}Title" class="font-bold text-lg dark:text-neutral-200">{{$title}}</div>
            <div class="">
                <div class="modal-action mt-0">
                    <form method="dialog" class="mb-0">
                        <button class="w-9 aspect-square rounded-full text-white bg-[#831b94] hover:bg-red-700 hover:shadow-lg hover:text-white duration-200"><i class="fa-solid fa-xmark"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <div id="{{$id}}Body">
            {{$slot}}
        </div>
    </div>
</dialog>
