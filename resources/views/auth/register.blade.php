@extends('layouts.app')

@section('title', 'Register - TARPOR | Secure User Authentication')
@section('meta_title', 'Register - TARPOR | Secure User Authentication')
@section('description', 'Register to TARPOR securely and explore our features.')

@push('styles')
    <style>
        /* Hide the default password toggle icon in Edge and other browsers */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-webkit-reveal {
            display: none;
        }

        /* Particle canvas styling */
        #particle-canvas {
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
            #FF6B6B, /* Red */
            #FFD93D, /* Yellow */
            #6BCB77, /* Green */
            #4D96FF, /* Blue */
            #A66DD4, /* Purple */
            #E84A5F  /* Pink */
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
    <div class="min-h-screen bg-gradient-to-br from-[#0C1220] via-[#264773] to-[#101624] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Particle canvas -->
        <div id="particle-canvas" class="absolute inset-0 z-0"></div>

        <!-- Main content -->
        <div class="max-w-md w-full space-y-8 bg-[#1E1E2E]/80 backdrop-blur-xl border border-gray-700 p-10 rounded-3xl
                shadow-[0_0_5px_rgba(93,188,252,0.6)] transition-all duration-300 hover:shadow-[0_0_15px_rgba(93,188,252,0.9)]
                hover:-translate-y-1 relative z-10">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto mb-4 flex justify-center animate-float">
                    <div class="p-3 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-lg shadow-lg">
                        <i class="fa-solid fa-user-plus text-white text-2xl"></i>
                    </div>
                </div>
                <h2 class="animated-gradient text-3xl font-bold bg-clip-text text-transparent">
                    Create Account
                </h2>
                <p class="mt-2 text-sm text-gray-400">Register to access our secure platform</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="rounded-lg bg-red-900/30 p-4 border border-red-800/50">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-triangle-exclamation text-red-400"></i>
                        <span class="text-sm font-medium text-red-300">Please fix the following errors</span>
                    </div>
                </div>
            @endif

            <!-- Registration Form -->
            <form class="mt-8 space-y-6" method="POST" action="{{ route('register.submit') }}">
                @csrf
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 text-sm bg-gray-700/50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400 text-gray-100 transition-all duration-200"
                               placeholder="Mehedi Hasan">
                        @error('name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 text-sm bg-gray-700/50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400 text-gray-100 transition-all duration-200"
                               placeholder="example@mail.com">
                        @error('email')
                        <p id="email-error" class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mobile Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Mobile Number</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                               class="w-full px-4 py-3 text-sm bg-gray-700/50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400 text-gray-100 transition-all duration-200"
                               placeholder="017XXXXXXXX" maxlength="11">
                        @error('phone')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                   class="w-full px-4 py-3 text-sm bg-gray-700/50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400 text-gray-100 transition-all duration-200"
                                   placeholder="Enter Your Password">
                            <span id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <x-icon name="icon-eye" class="h-7 w-7" />
                            </span>
                        </div>
                        @error('password')
                        <p id="password-error" class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="w-full px-4 py-3 text-sm bg-gray-700/50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400 text-gray-100 transition-all duration-200"
                                   placeholder="Confirm Your Password">
                            <span id="toggle-confirm-password" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <x-icon name="icon-eye" class="h-7 w-7" />
                            </span>
                        </div>
                        @error('password_confirmation')
                        <p id="confirm-password-error" class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg transition-all transform hover:-translate-y-0.5 shadow-lg flex items-center justify-center space-x-2 group">
                    <svg class="w-5 h-5 text-purple-200 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span class="transition-transform duration-200 group-hover:-translate-x-1">Register</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-300 text-sm">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-purple-400 hover:text-purple-300 transition-colors duration-200">
                        Log In
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection

@push('footer-scripts')
    <!-- Password Toggle Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Phone Number Validation
            const phoneInput = document.getElementById("phone");
            if (phoneInput) {
                phoneInput.addEventListener("input", validatePhoneNumber);
            }
            // Icon for Toggle to Display
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

            togglePassword('password', 'toggle-password');
            togglePassword('password_confirmation', 'toggle-confirm-password');



            // Validate Phone Number
            function validatePhoneNumber() {
                const phoneInput = document.getElementById("phone");
                phoneInput.value = phoneInput.value.replace(/\D/g, "");
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

            // Clear email error message
            clearErrorOnInput('email', 'email-error');

            // Clear password error message
            clearErrorOnInput('password', 'password-error');
            clearErrorOnInput('password_confirmation', 'confirm-password-error');
        });
    </script>

    <!-- Particle Network Script -->
    <script>
        (function () {
            // Particle class
            class Particle {
                constructor(canvas, ctx, particleColor, x, y, velocity) {
                    this.canvas = canvas;
                    this.ctx = ctx;
                    this.particleColor = particleColor;
                    this.x = x;
                    this.y = y;
                    this.velocity = velocity;
                }

                update() {
                    if (this.x > this.canvas.width + 20 || this.x < -20) {
                        this.velocity.x = -this.velocity.x;
                    }
                    if (this.y > this.canvas.height + 20 || this.y < -20) {
                        this.velocity.y = -this.velocity.y;
                    }

                    this.x += this.velocity.x;
                    this.y += this.velocity.y;
                }

                draw() {
                    this.ctx.beginPath();
                    this.ctx.fillStyle = this.particleColor;
                    this.ctx.globalAlpha = 0.7;
                    this.ctx.arc(this.x, this.y, 1.5, 0, Math.PI * 2);
                    this.ctx.fill();
                }
            }

            // Particle Network class
            class ParticleNetwork {
                constructor(container, options) {
                    this.container = container;
                    this.options = {
                        particleColor: options.particleColor || '#fff',
                        background: options.background || 'transparent',
                        interactive: options.interactive !== undefined ? options.interactive : true,
                        velocity: this.setVelocity(options.speed),
                        density: this.setDensity(options.density)
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
                        this.particles = [];
                        this.createParticles();
                    });

                    this.particles = [];
                    this.createParticles();

                    if (this.options.interactive) {
                        this.interactiveParticle = new Particle(
                            this.canvas,
                            this.ctx,
                            this.options.particleColor,
                            this.canvas.width / 2,
                            this.canvas.height / 2,
                            { x: 0, y: 0 }
                        );
                        this.particles.push(this.interactiveParticle);

                        let lastMouseX = this.interactiveParticle.x;
                        let lastMouseY = this.interactiveParticle.y;

                        this.canvas.addEventListener('mousemove', (event) => {
                            const mouseX = event.clientX;
                            const mouseY = event.clientY;

                            // Calculate velocity based on mouse movement
                            this.interactiveParticle.velocity.x = (mouseX - lastMouseX) * 0.1;
                            this.interactiveParticle.velocity.y = (mouseY - lastMouseY) * 0.1;

                            // Update the interactive particle's position
                            this.interactiveParticle.x = mouseX;
                            this.interactiveParticle.y = mouseY;

                            // Update last mouse position
                            lastMouseX = mouseX;
                            lastMouseY = mouseY;
                        });

                        this.canvas.addEventListener('mouseup', () => {
                            // Add some random velocity on mouseup
                            this.interactiveParticle.velocity = {
                                x: (Math.random() - 0.5) * this.options.velocity,
                                y: (Math.random() - 0.5) * this.options.velocity
                            };
                        });
                    }

                    this.animate();
                }

                setCanvasSize() {
                    this.canvas.width = window.innerWidth;
                    this.canvas.height = window.innerHeight;
                }

                createParticles() {
                    const particleCount = (this.canvas.width * this.canvas.height) / this.options.density;
                    for (let i = 0; i < particleCount; i++) {
                        const x = Math.random() * this.canvas.width;
                        const y = Math.random() * this.canvas.height;
                        const velocity = {
                            x: (Math.random() - 0.5) * this.options.velocity,
                            y: (Math.random() - 0.5) * this.options.velocity
                        };
                        this.particles.push(new Particle(this.canvas, this.ctx, this.options.particleColor, x, y, velocity));
                    }
                }

                animate() {
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

                    this.particles.forEach(particle => {
                        particle.update();
                        particle.draw();
                    });

                    for (let i = 0; i < this.particles.length; i++) {
                        for (let j = i + 1; j < this.particles.length; j++) {
                            const dx = this.particles[i].x - this.particles[j].x;
                            const dy = this.particles[i].y - this.particles[j].y;
                            const distance = Math.sqrt(dx * dx + dy * dy);

                            if (distance < 120) {
                                this.ctx.beginPath();
                                this.ctx.strokeStyle = this.options.particleColor;
                                this.ctx.globalAlpha = (120 - distance) / 120;
                                this.ctx.lineWidth = 0.7;
                                this.ctx.moveTo(this.particles[i].x, this.particles[i].y);
                                this.ctx.lineTo(this.particles[j].x, this.particles[j].y);
                                this.ctx.stroke();
                            }
                        }
                    }

                    requestAnimationFrame(this.animate.bind(this));
                }

                setVelocity(speed) {
                    switch (speed) {
                        case 'fast': return 2;
                        case 'slow': return 0.5;
                        case 'none': return 0;
                        default: return 1;
                    }
                }

                setDensity(density) {
                    switch (density) {
                        case 'high': return 5000;
                        case 'low': return 20000;
                        default: return 10000;
                    }
                }
            }
            // Initialize Particle Network
            const canvasDiv = document.getElementById('particle-canvas');
            const options = {
                particleColor: '#8b5cf6',
                background: 'transparent',
                interactive: true,
                speed: 'medium',
                density: 'high'
            };
            new ParticleNetwork(canvasDiv, options);
        })();
    </script>
@endpush
