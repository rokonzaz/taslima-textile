@php
    $lastSyncDate=date('d M Y, h:i a', strtotime($lastSync)) ?? '';
@endphp
<x-modals.large-modal id="attendanceSyncModal" title="Sync Attendance Data ">
    <form action="{{ route('attendance.sync') }}" method="POST">
        @csrf
        <div class="p-4">
            <p class="mb-4">Last Sync: <span class="text-[#831b94] text-sm">{{$lastSyncDate}}</span></p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
                <div>
                    <label for="employeeOrganization" class="inputLabel">
                        Start Date
                    </label>
                    <input type="date" id="start_date" name="start_date" value="{{date('Y-m-d', strtotime($lastSync))}}" class="inputField">
                    <span class="error_msg" id="error_start_date"></span>
                </div>
                <div>
                    <label for="employeeOrganization" class="inputLabel">
                        End Date
                    </label>
                    <input type="date" id="end_date" name="end_date" value="{{date('Y-m-d')}}" class="inputField">
                    <span class="error_msg" id="error_end_date"></span>
                </div>
            </div>
        </div>
        <div class="">
            <div class="mt-5 flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" onclick="attendanceSyncModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                    Cancel
                </button>
                <button type="submit" id="syncBtn" class="submit-button">
                    Sync
                </button>
            </div>
        </div>
    </form>
</x-modals.large-modal>

<script>

</script>
