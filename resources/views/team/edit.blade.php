<form class="mb-0" action="{{route('team.update', ['id'=>$team->id])}}" method="POST" class="mb-0" enctype="multipart/form-data" onsubmit="">
    @csrf
    <div class="p-4 overflow-y-scroll max-h-[50vh]">
        <div class="grid grid-cols-1 gap-1 mt-2">
            <div>
                <label for="" class="inputLabel">Team Name <span class="text-[#831b94]">*</span></label>
                <input id="c_team_name" name="name" value="{{$team->name}}" class="inputField"/>
                <span class="error_msg" id="c_error_team_name"></span>
            </div>
            <div>
                <label for="" class="inputLabel">Organization</label>
                <select id="" name="organization" class="inputField">
                    @if(isset($organizations))
                        @foreach ($organizations as $item)
                            <option value="{{ $item->id }}" {{$team->organization==$item->id ? 'selected' : ''}}>{{ $item->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label for="" class="inputLabel">
                    Department
                </label>
                <select id="" name="department" class="inputField">
                    @if(isset($department))
                        @foreach ($department as $item)
                            <option value="{{ $item->id }}" {{$team->department==$item->id ? 'selected' : ''}}>{{ $item->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-span-1 relative">
                <label for="select_employeeX" class="inputLabel">
                    Select A Line Manager <span class="text-[#831b94]">*</span>
                </label>
                <div id="department-head-spinner" class="absolute right-10 bottom-4 z-10">
                    <div class="animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-gray-400 rounded-full dark:text-blue-500" role="status" aria-label="loading">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <select id="e_supervisor" name="supervisor[]" class="" placeholder="Select a Supervisor..."></select>
                <span class="error_msg" id="c_error_supervisor"></span>
            </div>
        </div>
    </div>
    <div class="sticky bottom-0 z-50 bg-gray-100">
        <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
            <button type="button" onclick="editTeamModal.close()" class="cancel-button" data-hs-overlay="#create-new-employee-modal">
                Cancel
            </button>
            <button type="submit" class="submit-button">
                Update
            </button>

        </div>
    </div>
</form>
@php
    $departmentHeads = $team->getDepartmentHead->pluck('supervisor_id');
    $supArray = $departmentHeads->map(function($item) {
        return (string) $item;
    });
@endphp
<script>
    initializeSelectize('e_supervisor','get-supervisor-list', @json($supArray), 'supervisor-spinner');
</script>
