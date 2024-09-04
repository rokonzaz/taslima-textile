@extends('layouts.app')
@section('title', 'Settings')
@section('pageTitle', 'Settings')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Settings', 'url'=>route('settings.index')]]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection
@section('content')
    <x-containers.container-box data="!p-0 overflow-hidden print:border print:border-gray-300 print:text-xs print:align-middle">
        <div class="grid grid-cols-5 gap-6">
            <div class="p-4 print:p-2 print:my-5">

            </div>
        </div>
    </x-containers.container-box>

@endsection
