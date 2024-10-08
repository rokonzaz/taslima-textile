@extends('layouts.app')
@section('title', 'Import Summary')
@section('pageTitle', 'Import Summary')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[
        ['title'=>'Employees List', 'url'=>route('employees.index')],
        ['title'=>'Import Employees', 'url'=>route('employees.import')],
        ['title'=>'Import Summary', 'url'=>'']
    ]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection

@section('content')
    <x-containers.container-box data="!p-0 overflow-hidden">
        <div class=" group transition-all duration-500 hover:border-gray-400 ">
            <h2 class="bg-[#831b94] text-white font-bold text-lg leading-10 text-black pb-1 border-b border-gray-200 text-center">
                Import Summary
            </h2>
            <div class="px-4">
                <div class="data py-2 border-b border-gray-200">
                    <div class="flex items-center justify-between gap-4 mb-1 text-teal-600">
                        <p class="font-normal leading-8 transition-all duration-500">Success</p>
                        <p class="font-medium leading-8">{{$importLog->success_count}}</p>
                    </div>
                    <div class="flex items-center justify-between gap-4 mb-1 text-[#831b94]">
                        <p class="font-normal leading-8 transition-all duration-500">Errors</p>
                        <p class="font-medium leading-8">{{$importLog->error_count}}</p>
                    </div>
                </div>
                <div class="total flex items-center justify-between pt-1 text-teal-600">
                    <p class="font-bold text-lg leading-8">Total</p>
                    <h5 class="font-bold text-lg leading-9 ">{{$importLog->row_count}}</h5>
                </div>
            </div>

        </div>
    </x-containers.container-box>
    <x-containers.container-box>
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                            <thead>
                            <tr>
                                <th scope="col" class="px-2 text-left font-medium truncate">Status</th>
                                @php $colDefs=["Sl No.","ID Number","Name","NID Number","Organization","Department","Designation","Joining Date","Cell Number","Emergency Cell Number","Blood Group","Personal Mail Address","Official Mail Address","Gender","Resign Date"]; @endphp
                                @foreach($colDefs as $item)
                                    <th scope="col" class="px-6 text-left font-medium truncate pb-1">
                                        {{$item}}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            @if(isset($errorRow))
                                @foreach($errorRow as $data)
                                    <tr>
                                        <td class="px-1 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200 text-left">
                                            <span class="{{$data['status']==0 ? 'bg-red-100 text-red-700' : 'text-teal-700 bg-teal-100'}} font-semibold px-2 py-1 rounded-full text-xs">{{$data['msg']}}</span>
                                        </td>
                                        @foreach($data['data'] as $item)
                                             <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">{{$item}}</td>
                                        @endforeach
                                    </tr>

                                @endforeach

                            @endif



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-containers.container-box>
@endsection
@include('employee.create')
@section('scripts')
    <script>

    </script>
@endsection



