<x-modals.small-modal id="timeTrackerRequestModal" title="Time Tracker Form">
    <form class="mb-0" action="{{route('my-requests.store', ['a'=>'time-tracker'])}}" method="POST" enctype="multipart/form-data">
        <div class="p-4 overflow-y-scroll max-h-[60vh]">
            <div class="grid auto-cols-max grid-flow-col gap-3 text-center mb-5">
                <div class="bg-neutral min-w-16 text-white flex flex-col items-center p-1.5 countdown-box rounded">
                  <span class="countdown font-mono" id="hours">00</span>
                  hours
                </div>
                <div class="bg-neutral min-w-16 text-white flex flex-col items-center p-1.5 countdown-box rounded">
                  <span class="countdown font-mono" id="minutes">00</span>
                  min
                </div>
                <div class="bg-neutral min-w-16 text-white flex flex-col items-center p-1.5 countdown-box rounded">
                  <span class="countdown font-mono" id="seconds">00</span>
                  sec
                </div>
                <button type="button" class="btn rounded-full text-lg tooltip tooltip-right play-btn" id="toggleTrackingBtn" data-tip="start tracker"><i class="fa-solid fa-play"></i></button>
            </div>
            <div class="collapse collapse-plus rounded-[5px] shadow-md bg-slate-50 shadow-slate-100 border border-1 border-t-[3px] border-t-rose-600">
                <input id="timeEntriesToggleX" type="checkbox" class="p-2 pe-12 min-h-2"/>
                <label for="timeEntriesToggleX" class="collapse-title p-2 pe-12 min-h-2">
                    <div class="flex justify-between items-center">
                    <h3 class="font-semibold">Time Entries</h3>
                    <span class="text-sm font-medium" id="totalTime">Total Time: 0h 0m 0s</span>
                    </div>
                </label>
                <div class="collapse-content py-0 !pb-0">
                    <hr class="mt-0">
                    <div id="timeEntries" class="flex flex-col gap-2 py-3 min-h-12">
                    <span id="noEntriesMessage" class="text-center">No Entries Found</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 z-50 bg-gray-100">
            <div class="flex justify-end items-center gap-x-2 py-2 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="timeTrackerRequestModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    Close
                </button>
            </div>
        </div>
    </form>
</x-modals.small-modal>


