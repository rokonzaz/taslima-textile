@props(['data' => []])

@if(empty($data))
    <div class="card flex justify-center h-100 p-4 rounded-lg bg-white shadow-lg border">
        <div class="w-full flex gap-2 items-center justify-between animate-pulse">
            <div class="w-full space-y-3">
                <p class="h-4 bg-gray-200 rounded-full dark:bg-neutral-700"></p>
                <h3 class="h-4 bg-gray-200 rounded-full dark:bg-neutral-700 w-2/4"></h3>
            </div>
            <div class="shrink-0 rounded-md size-12 aspect-square self-center bg-gray-200 dark:bg-neutral-700"></div>
        </div>
    </div>
@else
    <a href="{{ $data['url'] ?? '#' }}" id="{{ $data['id'] ?? '' }}">
        <div class="card flex justify-center h-100 p-2 rounded-lg bg-white shadow-lg border">
            <div class="flex gap-2 items-center justify-between">
                <div class="grow">
                    <p class="title text-md font-medium mb-1">
                        {{ $data['title'] ?? 'N/A' }}
                    </p>
                    <h3 class="counting mb-0 font-bold">
                        @if(isset($data['loader']) && $data['loader'])
                            <div class="animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-gray-400 rounded-full dark:text-blue-500" role="status" aria-label="loading">
                                <span class="sr-only">Loading...</span>
                            </div>
                        @else
                            {{ $data['counting'] ?? '0' }}
                        @endif
                    </h3>
                </div>
                <div class="bg-red-100 rounded-md size-10 aspect-square flex items-center justify-center">
                    <span class="icon text-[#831b94] text-2xl">{!! $data['icon'] ?? '' !!}</span>
                </div>
            </div>
        </div>
    </a>
@endif
