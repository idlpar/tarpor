@extends('layouts.app')

@section('title', 'Verify OTP - TARPOR | Secure User Authentication')
@section('meta_title', 'Verify OTP - TARPOR | Secure User Authentication')
@section('description', 'Verify your OTP securely on TARPOR.')

@push('styles')
    <style>
        /* Hide the default password toggle icon in Edge and other browsers */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-webkit-reveal {
            display: none;
        }

        /* Raindrop Particle Canvas */
        #raindrop-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            pointer-events: none;
        }

        /* Ensure content is above the particle canvas */
        .content {
            position: relative;
            z-index: 10;
        }

        /* Gradient animation */
        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animated-gradient {
            background-size: 300% 300%;
            background-image: linear-gradient(45deg,
            #6C5CE7, /* Purple */
            #0984E3, /* Blue */
            #00B894, /* Green */
            #FDCB6E, /* Yellow */
            #E17055, /* Orange */
            #D63031  /* Red */
            );
            animation: gradientAnimation 6s infinite linear;
        }

        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 4s ease-in-out infinite; }

        /* OTP Input Boxes */
        .otp-input {
            width: 3.5rem;
            height: 4.5rem;
            font-size: 2rem;
            text-align: center;
            border-radius: 0.75rem;
            border: 2px solid #4B5563;
            background-color: rgba(55, 65, 81, 0.5);
            color: #F3F4F6;
            transition: all 0.2s ease;
        }

        .otp-input:focus {
            border-color: #8B5CF6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.3);
            outline: none;
            transform: translateY(-2px);
        }

        .otp-input.filled {
            border-color: #8B5CF6;
            background-color: rgba(139, 92, 246, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-[#0C1220] via-[#1E3A5F] to-[#101624] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Raindrop Particle Canvas -->
        <div id="raindrop-canvas" class="absolute inset-0 z-0"></div>

        <!-- Main Content -->
        <div class="max-w-md w-full space-y-8 bg-[#1E1E2E]/80 backdrop-blur-xl border border-gray-700 p-10 rounded-3xl
                shadow-[0_0_5px_rgba(93,188,252,0.6)] transition-all duration-300 hover:shadow-[0_0_15px_rgba(93,188,252,0.9)]
                hover:-translate-y-1 relative z-10">
            <!-- Header Section -->
            <div class="text-center">
                <div class="mx-auto mb-4 flex justify-center animate-float">
                    <div class="p-3 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-lg shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="animated-gradient text-3xl font-bold bg-clip-text text-transparent">
                    Verify OTP
                </h2>
                <p class="mt-2 text-sm text-gray-400">
                    Check your email for the 6-digit code
                </p>
            </div>

            <!-- Verification Form -->
            <form class="mt-8 space-y-6" method="POST" action="{{ route('verify.otp') }}" id="otp-form">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') }}">
                <input type="hidden" id="otp_code" name="otp_code" value="">

                <div class="space-y-4">
                    <label class="block text-md font-medium text-gray-300 mb-2">6-Digit OTP Code</label>
                    <div class="flex justify-center space-x-3">
                        @for($i = 0; $i < 6; $i++)
                            <input
                                type="text"
                                id="otp-digit-{{ $i }}"
                                maxlength="1"
                                class="otp-input"
                                oninput="handleOtpInput(this, {{ $i }})"
                                onkeydown="handleOtpKeyDown(this, {{ $i }})"
                                onpaste="handleOtpPaste(event)"
                                data-index="{{ $i }}"
                            >
                        @endfor
                    </div>
                    @error('otp_code')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-purple-900/30 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Verify Code</span>
                </button>
            </form>

            <!-- Resend OTP Section -->
            <div class="text-center pt-4 border-t border-gray-700/50">
                <p class="text-sm text-gray-400 mb-2">Didn't receive the code?</p>
                <button type="button" id="resend-button" class="group relative inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600/30 to-indigo-600/30 border border-purple-500/50 rounded-xl font-medium text-purple-300 hover:text-white transition-all duration-300
                    disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none hover:border-purple-400 hover:from-purple-600/50 hover:to-indigo-600/50
                    hover:shadow-lg hover:shadow-purple-900/20" disabled>
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2 group-hover:animate-spin-fast" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Resend OTP </span>
                        <span id="countdown" class="ml-1">(60s)</span>
                    </span>
                    <span
                        class="absolute inset-0 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl opacity-0 group-hover:opacity-10 transition-opacity duration-300"></span>
                </button>
            </div>

            <form id="resend-otp-form" method="POST" action="{{ route('resend.otp') }}">
                @csrf
                <input type="hidden" name="email" value="{{ session('otp_email') }}">
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Raindrop Particle Script -->
    <script>
        (function () {
            // Raindrop class
            class Raindrop {
                constructor(canvas, ctx, colors) {
                    this.canvas = canvas;
                    this.ctx = ctx;
                    this.colors = colors;
                    this.reset();
                }

                reset() {
                    this.x = Math.random() * this.canvas.width;
                    this.y = Math.random() * -this.canvas.height;
                    this.speed = Math.random() * 3 + 2; // Falling speed
                    this.size = Math.random() * 2 + 1; // Raindrop size
                    this.color = this.colors[Math.floor(Math.random() * this.colors.length)];
                }

                update() {
                    this.y += this.speed;
                    if (this.y > this.canvas.height) {
                        this.reset();
                    }
                }

                draw() {
                    this.ctx.beginPath();
                    this.ctx.moveTo(this.x, this.y);
                    this.ctx.lineTo(this.x + this.size / 2, this.y + this.size * 2);
                    this.ctx.strokeStyle = this.color;
                    this.ctx.lineWidth = this.size;
                    this.ctx.stroke();
                }
            }

            // Raindrop Animation
            class RaindropAnimation {
                constructor(container, options) {
                    this.container = container;
                    this.options = {
                        colors: options.colors || ['#6C5CE7', '#0984E3', '#00B894', '#FDCB6E', '#E17055', '#D63031'],
                        density: options.density || 100
                    };
                    this.init();
                }

                init() {
                    this.canvas = document.createElement('canvas');
                    this.container.appendChild(this.canvas);
                    this.ctx = this.canvas.getContext('2d');

                    this.setCanvasSize();

                    window.addEventListener('resize', () => {
                        this.setCanvasSize();
                    });

                    this.raindrops = [];
                    this.createRaindrops();

                    this.animate();
                }

                setCanvasSize() {
                    this.canvas.width = window.innerWidth;
                    this.canvas.height = window.innerHeight;
                }

                createRaindrops() {
                    const raindropCount = (this.canvas.width * this.canvas.height) / this.options.density;
                    for (let i = 0; i < raindropCount; i++) {
                        this.raindrops.push(new Raindrop(this.canvas, this.ctx, this.options.colors));
                    }
                }

                animate() {
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

                    this.raindrops.forEach(raindrop => {
                        raindrop.update();
                        raindrop.draw();
                    });

                    requestAnimationFrame(this.animate.bind(this));
                }
            }

            // Initialize Raindrop Animation
            const raindropDiv = document.getElementById('raindrop-canvas');
            const options = {
                colors: ['#6C5CE7', '#0984E3', '#00B894', '#FDCB6E', '#E17055', '#D63031'],
                density: 2000
            };
            new RaindropAnimation(raindropDiv, options);
        })();
    </script>

    <!-- OTP Input Handling Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Auto-focus first OTP input on page load
            document.getElementById('otp-digit-0').focus();

            // Resend OTP button functionality
            const resendButton = document.getElementById('resend-button');
            let countdown = 60;
            let timer = null;

            function updateButtonState(disabled) {
                resendButton.disabled = disabled;
                if(disabled) {
                    resendButton.classList.add('opacity-50', 'cursor-not-allowed');
                    resendButton.classList.remove('hover:shadow-lg', 'hover:border-purple-400');
                } else {
                    resendButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    resendButton.classList.add('hover:shadow-lg', 'hover:border-purple-400');
                }
            }

            function startCountdown() {
                if(timer) clearInterval(timer);
                countdown = 60;
                updateButtonState(true);

                timer = setInterval(() => {
                    countdown--;
                    document.getElementById('countdown').textContent = `(${countdown}s)`;

                    if(countdown <= 0) {
                        clearInterval(timer);
                        document.getElementById('countdown').textContent = '';
                        updateButtonState(false);
                    }
                }, 1000);
            }

            @if(session('otp_email')) startCountdown(); @endif

            resendButton.addEventListener('click', () => {
                startCountdown();
                document.getElementById('resend-otp-form').submit();
            });
        });

        // Handle OTP input
        function handleOtpInput(input, index) {
            // Only allow numbers
            input.value = input.value.replace(/[^0-9]/g, '');

            // Update the input styling
            if (input.value.length > 0) {
                input.classList.add('filled');
            } else {
                input.classList.remove('filled');
            }

            // Update the combined OTP value
            updateCombinedOtp();

            // Auto-focus next input if current has value
            if (input.value.length === 1 && index < 5) {
                document.getElementById(`otp-digit-${index + 1}`).focus();
            }
        }

        // Handle OTP keydown (for backspace)
        function handleOtpKeyDown(input, index) {
            if (event.key === 'Backspace' && input.value.length === 0 && index > 0) {
                document.getElementById(`otp-digit-${index - 1}`).focus();
            }
        }

        // Handle OTP paste
        function handleOtpPaste(event) {
            event.preventDefault();
            const pasteData = event.clipboardData.getData('text/plain').replace(/[^0-9]/g, '');

            // Fill the OTP boxes with pasted data
            for (let i = 0; i < Math.min(pasteData.length, 6); i++) {
                const input = document.getElementById(`otp-digit-${i}`);
                input.value = pasteData[i];
                input.classList.add('filled');
            }

            // Focus the last filled input
            const lastIndex = Math.min(pasteData.length - 1, 5);
            document.getElementById(`otp-digit-${lastIndex}`).focus();

            updateCombinedOtp();
        }

        // Update the hidden OTP field with combined digits
        function updateCombinedOtp() {
            let otp = '';
            for (let i = 0; i < 6; i++) {
                const input = document.getElementById(`otp-digit-${i}`);
                otp += input.value || '';
            }
            document.getElementById('otp_code').value = otp;

            // Auto-submit if all digits are filled
            if (otp.length === 6) {
                document.getElementById('otp-form').submit();
            }
        }
    </script>
@endpush
