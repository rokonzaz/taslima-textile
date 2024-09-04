@extends('layouts.app')
@section('title', 'Unauthorized')
@section('breadcumb')
@endsection
@section('additionalButton')

@endsection
@section('content')
    <main class="flex flex-col justify-center items-center pt-20">
        <h1 class="text-9xl font-bold text-neutral-600 tracking-widest">403</h1>
        <div class="bg-red-500 px-2 py-0.5 text-sm text-white rounded rotate-12 absolute">
            Employee Not Found
        </div>
        <button class="mt-5">
            <a href="{{route('dashboard')}}" class="border rounded-md px-4 py-2 font-medium hover:text-white hover:bg-red-500 hover:border-red-700 duration-200">
                Go to home
            </a>
        </button>
    </main>
@endsection
@section('scripts')

@endsection



