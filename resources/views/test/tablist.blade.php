@extends('layouts.app')


@section('content')
<style>
    /* Additional custom styles for your table */
    .permission-table th, .permission-table td {
        text-align: center;
        padding: 10px;
    }
    .permission-table .icon {
        cursor: pointer;
    }
</style>

    {{-- <div class="flex flex-wrap">
        <div class="border-r-2 border-gray-200 dark:border-gray-700">
            <nav class="flex flex-col space-y-2" aria-label="Tabs" role="tablist" data-hs-tabs-vertical="true">
                <button type="button" class="tabButton active" id="vertical-tab-item-1" data-hs-tab="vertical-tab-1" aria-controls="vertical-tab-1" role="tab">
                  Tab 1
                </button>
                <button type="button" class="tabButton" id="vertical-tab-item-2" data-hs-tab="vertical-tab-2" aria-controls="vertical-tab-2" role="tab">
                  Tab 2
                </button>
                <button type="button" class="tabButton" id="vertical-tab-item-3" data-hs-tab="vertical-tab-3" aria-controls="vertical-tab-3" role="tab">
                  Tab 3
                </button>
                <button type="button" class="tabButton" id="vertical-tab-item-4" data-hs-tab="vertical-tab-4" aria-controls="vertical-tab-4" role="tab">
                    Tab 4
                </button>
                <button type="button" class="tabButton" id="select-all-tabs">
                    Select All Tabs
                </button>
            </nav>
        </div>
        <div class="ml-3 w-full">
            <div id="vertical-tab-1" role="tabpanel" aria-labelledby="vertical-tab-item-1" class="tab-content">
                <div class="permissions">
                    <button class="select-all-btn mb-2" data-hs-tab="tab-1">Select All</button>
                    <div class="permission-group" id="tab-1">
                        <label class="block"><input type="checkbox" class="mr-2"> View</label>
                        <label class="block"><input type="checkbox" class="mr-2"> Edit</label>
                        <label class="block"><input type="checkbox" class="mr-2"> Create</label>
                        <label class="block"><input type="checkbox" class="mr-2"> Delete</label>
                    </div>
                </div>
            </div>
            <div id="vertical-tab-2" role="tabpanel" aria-labelledby="vertical-tab-item-2" class="tab-content hidden">
                <div class="permissions">
                    <button class="select-all-btn mb-2" data-hs-tab="tab-2">Select All</button>
                    <div class="permission-group" id="tab-2">
                        <label class="block"><input type="checkbox" class="mr-2"> View</label>
                        <label class="block"><input type="checkbox" class="mr-2"> Edit</label>
                        <label class="block"><input type="checkbox" class="mr-2"> Create</label>
                        <label class="block"><input type="checkbox" class="mr-2"> Delete</label>
                    </div>
                </div>
            </div>
            <div id="vertical-tab-3" role="tabpanel" aria-labelledby="vertical-tab-item-3" class="tab-content hidden">
                <div class="permissions">
                    <button class="select-all-btn mb-2" data-hs-tab="tab-3">Select All</button>
                    <div class="permission-group" id="tab-3">
                        <label class="block"><input type="checkbox" class="mr-2"> View</label>
                        <label class="block"><input type="checkbox" class="mr-2"> Edit</label>
                        <label class="block"><input type="checkbox" class="mr-2"> Create</label>
                        <label class="block"><input type="checkbox" class="mr-2"> Delete</label>
                    </div>
                </div>
            </div>
            <div id="vertical-tab-4" role="tabpanel" aria-labelledby="vertical-tab-item-4" class="tab-content hidden">
                <div class="permissions">
                    <button class="select-all-btn mb-2" data-hs-tab="tab-4">Select All</button>
                    <div class="permission-group" id="tab-4">
                        <label class="block"><input type="checkbox" class="mr-2"> View</label>
                        <label class="block"><input type="checkbox" class="mr-2"> Edit</label>
                        <label class="block"><input type="checkbox" class="mr-2"> Create</label>
                        <label class="block"><input type="checkbox" class="mr-2"> Delete</label>
                    </div>
                </div>
            </div>
            <button class="save-btn mt-4 bg-green-500 text-white py-2 px-4 rounded" id="save-btn">Save</button>
        </div>
    </div> --}}
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
    </div>
    <button class="btn rounded-full text-lg mb-5 play-btn" id="toggleBtn"><i class="fa-solid fa-play"></i></button>
    <div class="collapse collapse-plus rounded-[5px] shadow-md bg-slate-50 shadow-slate-100 border border-1 border-t-[3px] border-t-[#029397] mb-8">
        <input id="timeEntriesToggleX" type="checkbox" class="p-3 pe-12 min-h-2"/>
        <label for="timeEntriesToggleX" class="collapse-title p-3 pe-12 min-h-2">
            <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold">Time Entries</h3>
            <span class="font-medium" id="totalTime">Total Time: 0h 0m 0s</span>
            </div>
        </label>
        <div class="collapse-content p-2 !pb-0">
            <hr class="mt-0">
            <div id="timeEntries" class="flex flex-col gap-2 py-3 min-h-12">
            <span id="noEntriesMessage" class="text-center">No Entries Found</span>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
{{-- <script>
    let counterInterval;
    let hours = 0, minutes = 0, seconds = 0;
    let startTime, endTime;
    let totalSeconds = 0;

    function updateCounter() {
      seconds++;
      if (seconds >= 60) {
        seconds = 0;
        minutes++;
        if (minutes >= 60) {
          minutes = 0;
          hours++;
        }
      }

      $('#hours').text(hours.toString().padStart(2, '0'));
      $('#minutes').text(minutes.toString().padStart(2, '0'));
      $('#seconds').text(seconds.toString().padStart(2, '0'));
    }

    function updateTotalTime() {
      let totalH = Math.floor(totalSeconds / 3600);
      let totalM = Math.floor((totalSeconds % 3600) / 60);
      let totalS = totalSeconds % 60;
      $('#totalTime').text(`Total Time: ${totalH}h ${totalM}m ${totalS}s`);
    }

    $('#startBtn').click(function() {
      if (counterInterval) clearInterval(counterInterval);
      hours = 0;
      minutes = 0;
      seconds = 0;
      startTime = new Date();
      counterInterval = setInterval(updateCounter, 1000);
    });

    $('#endBtn').click(function() {
      clearInterval(counterInterval);
      endTime = new Date();
      let duration = ((endTime - startTime) / 1000); // in seconds
      totalSeconds += duration;
      let h = Math.floor(duration / 3600);
      let m = Math.floor((duration % 3600) / 60);
      let s = Math.floor(duration % 60);

      let entryId = `entry-${Date.now()}`;
      let entryHtml = `
        <div class="p-2 text-white flex justify-between items-center bg-neutral rounded-md entry" id="${entryId}">
          <span>${startTime.toLocaleString()} - ${endTime.toLocaleString()}</span>
          <span>${h}h ${m}m ${s}s</span>
          <button class="dlt-icon" onclick="deleteEntry('${entryId}', ${duration})"><i class="fa-regular fa-trash-can"></i></button>
        </div>
      `;
      $('#timeEntries').append(entryHtml);
      $('#noEntriesMessage').hide();
      updateTotalTime();
    });

    function deleteEntry(entryId, duration) {
      $(`#${entryId}`).remove();
      totalSeconds -= duration;
      updateTotalTime();
      if ($('#timeEntries').children().length === 0) {
        $('#noEntriesMessage').show();
      }
    }
</script> --}}
<script>
    let counterInterval;
    let hours = 0, minutes = 0, seconds = 0;
    let startTime, endTime;
    let totalSeconds = 0;
    let isRunning = false;

    function updateCounter() {
      seconds++;
      if (seconds >= 60) {
        seconds = 0;
        minutes++;
        if (minutes >= 60) {
          minutes = 0;
          hours++;
        }
      }

      $('#hours').text(hours.toString().padStart(2, '0'));
      $('#minutes').text(minutes.toString().padStart(2, '0'));
      $('#seconds').text(seconds.toString().padStart(2, '0'));
    }

    function updateTotalTime() {
      let totalH = Math.floor(totalSeconds / 3600);
      let totalM = Math.floor((totalSeconds % 3600) / 60);
      let totalS = Math.floor(totalSeconds % 60);
      $('#totalTime').text(`Total Time: ${totalH}h ${totalM}m ${totalS}s`);
    }

    $('#toggleBtn').click(function() {
      if (isRunning) {
        clearInterval(counterInterval);
        endTime = new Date();
        let duration = ((endTime - startTime) / 1000); // in seconds
        totalSeconds += duration;
        let h = Math.floor(duration / 3600);
        let m = Math.floor((duration % 3600) / 60);
        let s = Math.floor(duration % 60);

        let entryId = `entry-${Date.now()}`;
        let entryHtml = `
          <div class="p-2 text-white flex justify-between items-center bg-neutral rounded-md entry" id="${entryId}">
            <span>${startTime.toLocaleString()} - ${endTime.toLocaleString()}</span>
            <span>${h}h ${m}m ${s}s</span>
            <button class="dlt-icon" onclick="deleteEntry('${entryId}', ${duration})"><i class="fa-regular fa-trash-can"></i></button>
          </div>
        `;
        $('#timeEntries').append(entryHtml);
        $('#noEntriesMessage').hide();
        updateTotalTime();
        $(this).html('<i class="fa-solid fa-play"></i>').removeClass('stop-btn').addClass('play-btn');
        isRunning = false;
      } else {
        if (counterInterval) clearInterval(counterInterval);
        hours = 0;
        minutes = 0;
        seconds = 0;
        startTime = new Date();
        counterInterval = setInterval(updateCounter, 1000);
        $(this).html('<i class="fa-solid fa-stop"></i>').removeClass('play-btn').addClass('stop-btn');
        isRunning = true;
      }
    });

    function deleteEntry(entryId, duration) {
      $(`#${entryId}`).remove();
      totalSeconds -= duration;
      updateTotalTime();
      if ($('#timeEntries').children().length === 0) {
        $('#noEntriesMessage').show();
      }
    }
</script>

@endsection
