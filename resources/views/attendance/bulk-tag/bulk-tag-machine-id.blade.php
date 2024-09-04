@extends('layouts.app')
@section('title', 'Employees ID Bulk Tag')
@section('pageTitle', 'Employees ID Bulk Tag')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Attendance', 'url'=>route('attendance.index')],['title'=>'Employees ID Bulk Tag', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection

@section('content')
    <form action="{{route('attendance.bulk-tag-machine-id-submit')}}" method="POST" enctype="multipart/form-data">
        @csrf
            <x-containers.container-box>
            @if($employee->count()>0)
                <table class="min-w-full  divide-y divide-gray-200 dark:divide-neutral-700 ">
                    <thead class="sticky top-0">
                    <tr class="bg-gray-200 dark:bg-neutral-900">
                        <th class="text-left px-6 py-3 font-semibold uppercase text-[14px] dark:text-teal-500">
                            Name
                        </th>
                        <th class="text-left px-6 py-3 font-semibold uppercase text-[14px] dark:text-teal-500">
                            EMP ID
                        </th>
                        <th class="text-left px-6 py-3 font-semibold uppercase text-[14px] dark:text-teal-500">
                            Biometric Machine Id</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($employee as $item)
                        <tr
                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                            <td class="px-6 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                                {{$item->full_name}}
                            </td>
                            <td class="px-6 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                                ({{$item->emp_id}})
                            </td>
                            <td class="flex justify-end px-0 pt-2 text-sm text-gray-800 whitespace-nowrap dark:text-neutral-200 font-semibold">
                                {{--{{$item->biometric_id}}--}}
                                <div class="w-1/2">
                                    <input type="text" name="id[{{$item->emp_id}}]" class="inputField !bg-white" value="{{$item->biometric_id}}">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="flex justify-end mt-4">
                    <button class="submit-button">Submit</button>
                </div>
            @endif
        </x-containers.container-box>
    </form>

@endsection
@section('scripts')

@endsection



