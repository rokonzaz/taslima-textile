@props(['data'=>[]])
<div class="group flex flex-col rounded-xl border bg-white shadow-sm transition hover:shadow-md dark:border-neutral-600 dark:bg-neutral-700">
    <div class="p-4 md:p-3">
        <div class="flex items-center">
            {!! $data['icon'] ?? '' !!}
            <div class="ms-3 grow">
                <h3 class="font-semibold text-gray-800 group-hover:text-[#831b94] dark:text-neutral-200 dark:group-hover:text-[#831b94]">
                    {!! $data['title'] ?? '' !!}
                </h3>
                <p class="text-sm text-gray-500 dark:text-neutral-500">
                    {!! $data['subtitle'] ?? '' !!}
                </p>
            </div>
        </div>
    </div>
</div>
