@extends('layouts.app')

@section('title', 'Reset Password - TARPOR | Secure User Authentication')
@section('meta_title', 'Reset Password - TARPOR | Secure User Authentication')
@section('description', 'Reset your password securely on TARPOR.')

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
    </style>
@endpush

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#0C1220] via-[#1E3A5F] to-[#101624] px-4 sm:px-6 lg:px-8">
        <!-- Raindrop Particle Canvas -->
        <div id="raindrop-canvas" class="absolute inset-0 z-0"></div>

        <!-- Main Content -->
        <div class="max-w-md w-full bg-[#1E1E2E]/80 backdrop-blur-xl border border-gray-700 p-10 rounded-2xl shadow-[0_0_5px_rgba(93,188,252,0.6)] transition-all duration-300 hover:shadow-[0_0_15px_rgba(93,188,252,0.9)] hover:-translate-y-1 relative z-10">

            <!-- Header with Icon -->
            <div class="text-center space-y-3 mb-8">
                <div class="flex justify-center">
                    <div class="p-3 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-full shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="animated-gradient text-3xl font-bold text-center bg-clip-text text-transparent">
                    Reset Password
                </h2>
                <p class="text-gray-400 text-sm">OTP sent to <span class="font-semibold text-gray-300">{{ session('password_reset_email') }}</span></p>
            </div>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="mt-4 mb-6 p-3 bg-red-700/30 border border-red-600 rounded-lg text-red-400 text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="email" value="{{ session('password_reset_email') }}">

                <!-- OTP Input -->
                <div class="mb-6">
                    <label class="text-gray-300 text-sm font-medium">OTP Code</label>
                    <input type="text" name="otp" required maxlength="6"
                           placeholder="★ ★ ★ ★ ★ ★"
                           pattern="\d{6}"
                           class="w-full px-4 py-3 mt-1 bg-gray-700/50 border border-gray-600 rounded-lg text-center text-2xl space-x-2 placeholder:tracking-widest tracking-[2rem] text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                           oninput="this.value = this.value.replace(/[^0-9]/g, ''); this.setCustomValidity(this.validity.patternMismatch ? 'Please enter exactly 6 digits.' : '')" />
                    @error('otp')
                    <span class="text-red-400 text-sm mt-2">{{ $errors->first('otp') }}</span>
                    @enderror
                </div>

                <!-- Resend OTP Button with Countdown -->
                <div class="flex justify-center text-center mb-6">
                    <button type="submit" id="resend-otp-button" disabled
                            class="text-purple-400 hover:text-purple-300 transition cursor-not-allowed font-medium flex items-center justify-center space-x-1">
                        <i class="fa-sharp fa-solid fa-repeat"></i> &nbsp;
                        <span id="resend-text">Resend OTP (<span id="countdown">60</span>s)</span>
                    </button>
                </div>

                <!-- New Password -->
                <div class="mb-6">
                    <label class="text-gray-300 text-sm font-medium">New Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-3 mt-1 bg-gray-700/50 border border-gray-600 rounded-lg text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                               placeholder="Enter your password">
                        <span id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <x-icon name="icon-eye" class="h-7 w-7" />
                        </span>
                    </div>
                    <div id="password-requirements" class="mt-2 text-sm text-gray-400 hidden">
                        <ul>
                            <li id="uppercase" class="text-red-400">At least one uppercase letter</li>
                            <li id="lowercase" class="text-red-400">At least one lowercase letter</li>
                            <li id="number" class="text-red-400">At least one number</li>
                            <li id="special" class="text-red-400">At least one special character</li>
                            <li id="length" class="text-red-400">At least 8 characters</li>
                        </ul>
                    </div>
                    @error('password')
                    <span id="password-error" class="text-red-400 text-sm mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label class="text-gray-300 text-sm font-medium">Confirm Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-4 py-3 mt-1 bg-gray-700/50 border border-gray-600 rounded-lg text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                               placeholder="Confirm your password">
                        <span id="toggle-confirm-password" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <x-icon name="icon-eye" class="h-7 w-7" />
                        </span>
                    </div>
                    @error('password_confirmation')
                    <span id="confirm-password-error" class="text-red-400 text-sm mt-2">{{ $message }}</span>
                    @enderror
                </div>


                <!-- Submit Button with Icon -->
                <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg transition-all transform hover:-translate-y-0.5 shadow-lg hover:shadow-purple-900/30 flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-rotate-right"></i>
                    <span>Reset Password</span>
                </button>
            </form>

            <form id="resend-otp-form" method="POST" action="{{ route('password.resend') }}">
                @csrf
                <input type="hidden" name="email" value="{{ session('password_reset_email') }}">
            </form>
        </div>
    </div>
@endsection

@push('footer-scripts')
    <!-- Raindrop Particle Script -->
    <script>
        (function () {
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

            const raindropDiv = document.getElementById('raindrop-canvas');
            const options = {
                colors: ['#6C5CE7', '#0984E3', '#00B894', '#FDCB6E', '#E17055', '#D63031'],
                density: 2000
            };
            new RaindropAnimation(raindropDiv, options);
        })();
    </script>

    <!-- Resend OTP Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const resendButton = document.getElementById('resend-otp-button');
            let countdown = 60;
            const countdownElement = document.getElementById('countdown');

            const interval = setInterval(() => {
                countdown--;
                countdownElement.textContent = countdown;
                if (countdown <= 0) {
                    clearInterval(interval);
                    resendButton.disabled = false;
                    resendButton.classList.remove('cursor-not-allowed');
                    document.getElementById('resend-text').textContent = 'Resend OTP';
                }
            }, 1000);

            // Form Submission
            const resendForm = document.getElementById('resend-otp-form');
            resendButton.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default action for button
                resendForm.submit(); // Submit the form manually
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Toggle Password Visibility
            const togglePassword = (inputId, toggleId) => {
                const input = document.getElementById(inputId);
                const toggle = document.getElementById(toggleId);
                const icon = toggle.querySelector('svg use'); // Target the <use> element inside the <svg>

                // Initially hide the eye icon
                toggle.style.display = 'none';

                // Show the eye icon when the user starts typing
                input.addEventListener('input', () => {
                    if (input.value.length > 0) {
                        toggle.style.display = 'flex';
                    } else {
                        toggle.style.display = 'none';
                    }
                });

                // Toggle password visibility and icon
                toggle.addEventListener('click', () => {
                    if (input.type === "password") {
                        input.type = "text";
                        icon.setAttribute('xlink:href', "{{ asset('svg/sprite.svg#icon-eye-slash') }}"); // Change to eye-slash icon

                        // Revert back after 3 seconds
                        setTimeout(() => {
                            input.type = "password";
                            icon.setAttribute('xlink:href', "{{ asset('svg/sprite.svg#icon-eye') }}"); // Change back to eye icon
                        }, 3000);
                    } else {
                        input.type = "password";
                        icon.setAttribute('xlink:href', "{{ asset('svg/sprite.svg#icon-eye') }}"); // Ensure icon is eye when hiding password
                    }
                });
            };

            // Initialize password toggles
            togglePassword('password', 'toggle-password');
            togglePassword('password_confirmation', 'toggle-confirm-password');

            // Password Validation Logic
            const passwordInput = document.querySelector("input[name='password']");
            const passwordRequirements = document.getElementById('password-requirements');
            const requirements = {
                uppercase: /[A-Z]/,
                lowercase: /[a-z]/,
                number: /[0-9]/,
                special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/,
                length: /.{8,}/
            };

            // Function to validate password
            const validatePassword = () => {
                const password = passwordInput.value;
                let allConditionsMet = true;

                Object.keys(requirements).forEach(key => {
                    const requirementMet = requirements[key].test(password);
                    document.getElementById(key).style.color = requirementMet ? 'green' : 'red';
                    if (!requirementMet) allConditionsMet = false;
                });

                // Show/hide password requirements based on validation
                if (allConditionsMet) {
                    passwordRequirements.classList.add('hidden');
                } else {
                    passwordRequirements.classList.remove('hidden');
                }
            };

            // Attach validation to password input
            if (passwordInput) {
                passwordInput.addEventListener('input', validatePassword);
            }

            // Function to clear error messages when the user starts typing
            const clearErrorOnInput = (inputId, errorId) => {
                const input = document.getElementById(inputId);
                const error = document.getElementById(errorId);

                if (input && error) {
                    input.addEventListener('input', () => {
                        error.classList.add('hidden'); // Hide the error message
                    });
                }
            };

            // Clear password error message
            clearErrorOnInput('password', 'password-error');
            clearErrorOnInput('password_confirmation', 'confirm-password-error');
        });
    </script>
@endpush

