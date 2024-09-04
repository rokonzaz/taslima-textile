@props(['data'=>[]])
@php $employee=$data; @endphp
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mt-2">
    <div class="flex items-center gap-3">
        <div class="">
            @php
                $defaultProfileImage=employeeDefaultProfileImage($employee->gender);
                $profile_img=employeeProfileImage($employee->emp_id, $employee->profile_photo);
            @endphp
            <figure class="w-12 aspect-square rounded-full overflow-hidden">
                <img class="w-full h-full object-cover" src="{{$profile_img}}" onerror="this.onerror=null;this.src='{{$defaultProfileImage}}';" alt="{{$employee->name ??''}}"/>
            </figure>
        </div>
        <div class="flex flex-col gap-0.5 leading-none">
            <span class="font-bold">{{$employee->full_name ?? ''}}</span>
            <span>{{$employee->email ?? ''}}</span>
            <span>{{$employee->phone ?? ''}}</span>
        </div>
    </div>
</div>
