@extends('layouts.no-layout')
@section('title', 'Forbidden Page')
@section('breadcumb')
@endsection
@section('additionalButton')

@endsection
@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Creepster&family=Epilogue:ital,wght@0,100..900;1,100..900&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&display=swap');

        html,
        body {
            background-color: #000121 !important;

        }

        .maincontainer {
            position: relative;
            top: -50px;
            transform: scale(0.8);
            background: url("https://aimieclouse.com/Media/Portfolio/Error403Forbidden/HauntedHouseBackground.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 700px 600px;
            width: 800px;
            height: 600px;
            margin: 0px auto;
            display: grid;
        }

        .foregroundimg {
            position: absolute;
            width: 95%;
            top: 4rem;
            z-index: 5;
        }

        .errorcode {
            position: absolute;
            margin-top: -10.5rem;
            left: 30%;
            font-family: 'Creepster', cursive;
            color: white;
            text-align: center;
            font-style: italic;
            font-size: 6em;
            letter-spacing: 0.1em;
        }

        .errortext {
            margin-top: -2rem;
            color: #FBD130;
            text-align: center;
            text-transform: uppercase;
            font-size: 1.8em;
        }

        .bat {
            opacity: 0;
            position: relative;
            transform-origin: center;
            z-index: 3;
        }

        .bat:nth-child(1) {
            top: 120px;
            left: 20px;
            transform: scale(0.5);
            animation: 13s 1s flyBat1 infinite linear;
        }

        .bat:nth-child(2) {
            top: 80px;
            left: 80px;
            transform: scale(0.3);
            animation: 8s 4s flyBat2 infinite linear;
        }

        .bat:nth-child(3) {
            top: 50px;
            left: 320px;
            transform: scale(0.4);
            animation: 12s 2s flyBat3 infinite linear;
        }

        .body {
            position: relative;
            width: 50px;
            top: 12px;
        }

        .wing {
            width: 150px;
            position: relative;
            transform-origin: right center;
        }

        .leftwing {
            top: 5rem;
            left: -8rem;
            animation: 0.8s flapLeft infinite ease-in-out;
        }

        .rightwing {
            top: -5rem;
            left: -8rem;
            transform: scaleX(-1);
            animation: 0.8s flapRight infinite ease-in-out;
        }

        @keyframes flapLeft {
            0% {
                transform: rotateZ(0);
            }

            50% {
                transform: rotateZ(10deg) rotateY(40deg);
            }

            100% {
                transform: rotateZ(0);
            }
        }

        @keyframes flapRight {
            0% {
                transform: scaleX(-1) rotateZ(0);
            }

            50% {
                transform: scaleX(-1) rotateZ(10deg) rotateY(40deg);
            }

            100% {
                transform: scaleX(-1) rotateZ(0);
            }
        }

        @keyframes flyBat1 {
            0% {
                opacity: 1;
                transform: scale(0.5)
            }

            25% {
                opacity: 1;
                transform: scale(0.5) translate(-400px, -330px)
            }

            50% {
                opacity: 1;
                transform: scale(0.5) translate(400px, -800px)
            }

            75% {
                opacity: 1;
                transform: scale(0.5) translate(600px, 100px)
            }

            100% {
                opacity: 1;
                transform: scale(0.5) translate(100px, 300px)
            }
        }

        @keyframes flyBat2 {
            0% {
                opacity: 1;
                transform: scale(0.3)
            }

            25% {
                opacity: 1;
                transform: scale(0.3) translate(200px, -330px)
            }

            50% {
                opacity: 1;
                transform: scale(0.3) translate(-300px, -800px)
            }

            75% {
                opacity: 1;
                transform: scale(0.3) translate(-400px, 100px)
            }

            100% {
                opacity: 1;
                transform: scale(0.3) translate(100px, 300px)
            }
        }

        @keyframes flyBat3 {
            0% {
                opacity: 1;
                transform: scale(0.4)
            }

            25% {
                opacity: 1;
                transform: scale(0.4) translate(-350px, -330px)
            }

            50% {
                opacity: 1;
                transform: scale(0.4) translate(400px, -800px)
            }

            75% {
                opacity: 1;
                transform: scale(0.4) translate(-600px, 100px)
            }

            100% {
                opacity: 1;
                transform: scale(0.4) translate(100px, 300px)
            }
        }
    </style>
    <main class="">
        <div class="maincontainer">
            <div class="bat">
                <img class="wing leftwing" src="https://aimieclouse.com/Media/Portfolio/Error403Forbidden/bat-wing.png">
                <img class="body" src="https://aimieclouse.com/Media/Portfolio/Error403Forbidden/bat-body.png"
                    alt="bat">
                <img class="wing rightwing" src="https://aimieclouse.com/Media/Portfolio/Error403Forbidden/bat-wing.png">
            </div>
            <div class="bat">
                <img class="wing leftwing" src="https://aimieclouse.com/Media/Portfolio/Error403Forbidden/bat-wing.png">
                <img class="body" src="https://aimieclouse.com/Media/Portfolio/Error403Forbidden/bat-body.png"
                    alt="bat">
                <img class="wing rightwing" src="https://aimieclouse.com/Media/Portfolio/Error403Forbidden/bat-wing.png">
            </div>
            <div class="bat">
                <img class="wing leftwing" src="https://aimieclouse.com/Media/Portfolio/Error403Forbidden/bat-wing.png">
                <img class="body" src="https://aimieclouse.com/Media/Portfolio/Error403Forbidden/bat-body.png"
                    alt="bat">
                <img class="wing rightwing" src="https://aimieclouse.com/Media/Portfolio/Error403Forbidden/bat-wing.png">
            </div>
            <img class="foregroundimg"
                src="https://aimieclouse.com/Media/Portfolio/Error403Forbidden/HauntedHouseForeground.png"
                alt="haunted house">

        </div>
        <h1 class="errorcode">FORBIDDEN 403</h1>
        <div class="errortext">This area is forbidden. Go back now!</div>
        <button class="mt-5 mx-auto w-full">
            <a href="{{ route('dashboard') }}"
                class="border border-white rounded-md px-4 py-2 font-medium text-white hover:text-white hover:bg-red-500 hover:border-red-700 duration-200">
                Go to home
            </a>
        </button>
    </main>
@endsection
@section('scripts')

@endsection
