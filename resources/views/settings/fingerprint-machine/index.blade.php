@extends('layouts.app')
@section('title', 'Fingerprint Machine')
@section('pageTitle', 'Fingerprint Machine')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Fingerprint Machine', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection

@section('content')
    <x-containers.container-box>
        <div class="grid grid-cols-4 divide-x">
            <div class="p-10 flex justify-center">
                <figure class="">
                    <img src="{{asset('assets/img/zkteco-fingerprint.png')}}" class="h-80" alt="">
                </figure>
            </div>
            <div class="col-span-3">
                <form action="{{route('settings.fingerprintMachine.update')}}" method="post" class=" h-full">
                    @csrf
                    <div class="grid grid-cols-2 gap-x-6 px-8 py-4 h-full divide-x">
                        <div class="">
                            <div class="">
                                <label for="" class="inputLabel">Device IP</label>
                                <input type="text" name="device_ip" id="device_ip" value="{{$deviceIp ?? ''}}" oninput="deviceIPValue()" class="inputField">
                            </div>
                            <div class="mt-2">
                                <button type="submit" id="updateButton" class="submit-button" {{ $deviceIp == '' ? 'disabled' : '' }}>Update</button>
                            </div>
                        </div>
                        <div class="px-6">
                            <p>Connection Status:
                                @if($isConnected)
                                    <span class="text-teal-600 font-medium">Connected <span class="font-semibold text-[#831b94]">({{$deviceIp}})</span></span>
                                @else
                                    <span class="text-[#831b94] font-medium">Not Connected</span>
                                @endif
                            </p>
                            <div class="mt-4">
                                @if($isConnected)
                                    <p><i class="fa-regular fa-clock"></i> Device Time: {{date('d M Y, h:i a', strtotime($deviceDate['Date'].' '.$deviceDate['Time']))}}</p>
                                    <h1>Status:
                                        <span class="font-semibold {{ $status == 1 ? 'text-teal-600' : 'text-[#831b94]' }}">
                                            {{ $status == 1 ? 'Online' : 'Offline' }}
                                        </span>
                                    </h1>
                                    <p>Serial Number: {{$serialNumber ?? ''}}</p>
                                    <p>Produce Date: {{$produceDate ?? ''}}</p>
                                    <p>User capacity: 	5000/174</p>
                                    <p>Transaction capacity:	100000/99846</p>
                                    <p>Finger capacity:	3000/206	</p>
                                    <p>Lock:	Enable</p>
                                    <p>RF Card	Enable</p>
                                @else

                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </x-containers.container-box>

@endsection
@section('scripts')
<script>
    function deviceIPValue() {
        const deviceIp = document.getElementById('device_ip').value;
        const updateButton = document.getElementById('updateButton');
        if (deviceIp.trim() === '') {
            updateButton.disabled = true;
        } else {
            updateButton.disabled = false;
        }
    }



    // Call deviceIPValue() on page load to ensure correct initial button state
    document.addEventListener('DOMContentLoaded', deviceIPValue);
</script>
@endsection



