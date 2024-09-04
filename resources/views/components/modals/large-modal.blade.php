@props(['id'=>'largeModalExample', 'title'=>'Title', 'footer'=>''])
<dialog id="{{$id}}" class="modal">
    <div class="modal-box w-11/12 max-w-5xl p-0 relative border border-neutral-200 overflow-hidden">
        <div class="flex justify-between items-center border-b p-2 pl-4 sticky top-0 z-50 bg-gray-100 ">
            <div id="{{$id}}Title" class="font-bold text-lg">{{$title}}</div>
            <div class="">
                <div class="modal-action mt-0">
                    <form method="dialog" class="mb-0">
                        <button class="w-9 aspect-square rounded-full text-white bg-[#831b94] hover:bg-red-700 hover:shadow-lg hover:text-white duration-200"><i class="fa-solid fa-xmark"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <div id="{{$id}}Body" class="">
            {{$slot}}
        </div>
        <!-- Footer -->
        @if (!empty($footer))
            <div class="modal-footer p-2 border-t">
                {{ $footer }}
            </div>
        @endif
    </div>
</dialog>

