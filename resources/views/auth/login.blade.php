@extends('layouts.app')

@section('title', 'Login - TARPOR | Secure User Authentication')
@section('meta_title', 'Login - TARPOR | Secure User Authentication')
@section('description', 'Access your TARPOR account securely. Log in with your credentials to explore our features.')

@push('styles')
    <style>
        /* Hide the default password toggle icon in Edge and other browsers */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-webkit-reveal {
            display: none;
        }
        input[type="password"].password-field {
            font-size: 1.25rem;
            letter-spacing: 0.1rem;
        }

        input[type="password"].password-field::placeholder {
            font-size: 1rem;
            letter-spacing: normal;
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
    </style>
@endpush

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 px-4 sm:px-6 lg:px-8">
        <!-- Particle canvas -->
        <div id="particle-canvas"></div>

        <!-- Main content -->
        <div class="max-w-md w-full bg-gray-900/50 backdrop-blur-md border border-gray-700 p-10 rounded-2xl
            shadow-[0_0_5px_rgba(139,92,246,0.6)] transition-all hover:-translate-y-1 hover:shadow-[0_0_15px_rgba(139,92,246,0.9)] relative z-10">
            <!-- Header Section -->
            <div class="text-center">
                <div class="mx-auto mb-4 flex justify-center">
                    <div class="p-3 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-lg shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                </div>
                <h2 class="animated-gradient text-3xl font-bold bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent">
                    Welcome Back
                </h2>
                <p class="mt-2 text-sm text-gray-200">
                    Log in to your account to continue
                </p>
            </div>

            <!-- Error Message -->
            @if (session('error'))
                <div class="rounded-lg bg-red-900/30 p-4 border border-red-800/50">
                    <div class="flex items-center space-x-3">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-red-300">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form class="mt-8 space-y-6" method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="space-y-4">
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-md font-medium text-gray-300 mb-2">Email address</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400 text-gray-100 transition-all duration-200"
                            placeholder="Enter Your Email"
                        >
                        @error('email')
                        <p  id="email-error" class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-md font-medium text-gray-300 mb-2">Password</label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400 text-gray-100 text-base font-sans transition-all duration-200"
                                placeholder="Enter Your Password"
                            >
                            <span id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" aria-label="Toggle password visibility">
                                <!-- Eye Icon (Show Password) -->
                                <svg class="eye-icon h-5 w-5 text-purple-300 hover:text-purple-200 transition-colors duration-200" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                    <path fill="currentColor" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"></path>
                                </svg>
                                                    <!-- Eye-Slash Icon (Hide Password, Hidden by Default) -->
                                <svg class="eye-slash-icon h-5 w-5 text-purple-300 hover:text-purple-200 transition-colors duration-200 hidden" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye-slash" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                    <path fill="currentColor" d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z"></path>
                                </svg>
                            </span>
                        </div>
                        @error('password')
                        <p id="password-error" class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember_me"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-600 rounded bg-gray-700/50"
                        >
                        <label for="remember_me" class="ml-2 block text-sm text-gray-300">Remember me</label>
                    </div>
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-purple-400 hover:text-purple-300 transition-colors duration-200">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg transition-all transform hover:-translate-y-0.5 shadow-lg flex items-center justify-center space-x-2 group">
                    <svg class="w-5 h-5 text-purple-200 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span class="transition-transform duration-200 group-hover:-translate-x-1">Log In</span>
                </button>
            </form>

            <!-- Social Media Login -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-700/50"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gray-800/90 text-gray-200">Or continue with</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-4">
                    <!-- Google Login -->
                    <a href="{{ route('login.google') }}" class="w-full flex items-center justify-center px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-lg hover:bg-gray-700 transition-all duration-200">
                        <span class="w-6 h-6">
                            <svg class="w-full h-full" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23.75,16A7.7446,7.7446,0,0,1,8.7177,18.6259L4.2849,22.1721A13.244,13.244,0,0,0,29.25,16" fill="#00ac47"/>
                                <path d="M23.75,16a7.7387,7.7387,0,0,1-3.2516,6.2987l4.3824,3.5059A13.2042,13.2042,0,0,0,29.25,16" fill="#4285f4"/>
                                <path d="M8.25,16a7.698,7.698,0,0,1,.4677-2.6259L4.2849,9.8279a13.177,13.177,0,0,0,0,12.3442l4.4328-3.5462A7.698,7.698,0,0,1,8.25,16Z" fill="#ffba00"/>
                                <polygon fill="#2ab2db" points="8.718 13.374 8.718 13.374 8.718 13.374 8.718 13.374"/>
                                <path d="M16,8.25a7.699,7.699,0,0,1,4.558,1.4958l4.06-3.7893A13.2152,13.2152,0,0,0,4.2849,9.8279l4.4328,3.5462A7.756,7.756,0,0,1,16,8.25Z" fill="#ea4435"/>
                                <polygon fill="#2ab2db" points="8.718 18.626 8.718 18.626 8.718 18.626 8.718 18.626"/>
                                <path d="M29.25,15v1L27,19.5H16.5V14H28.25A1,1,0,0,1,29.25,15Z" fill="#4285f4"/>
                            </svg>
                        </span>
                        <span class="ml-2 text-white">Google</span>
                    </a>

                    <!-- Facebook Login -->
                    <a href="{{ route('login.facebook') }}" class="w-full flex items-center justify-center px-4 py-2 bg-blue-700/60 border border-gray-600 rounded-lg hover:bg-blue-700 transition-all duration-200">
                        <span class="w-6 h-6">
                            <svg class="w-full h-full" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#ffffff" fill-rule="evenodd" d="M9.94474914,22 L9.94474914,13.1657526 L7,13.1657526 L7,9.48481614 L9.94474914,9.48481614 L9.94474914,6.54006699 C9.94474914,3.49740494 11.8713513,2 14.5856738,2 C15.8857805,2 17.0033128,2.09717672 17.3287076,2.13987558 L17.3287076,5.32020466 L15.4462767,5.32094085 C13.9702212,5.32094085 13.6256856,6.02252733 13.6256856,7.05171716 L13.6256856,9.48481614 L17.306622,9.48481614 L16.5704347,13.1657526 L13.6256856,13.1657526 L13.6845806,22"/>
                            </svg>
                        </span>
                        <span class="ml-2 text-white">Facebook</span>
                    </a>
                </div>
            </div>

            <div class="mt-6 text-center">
                <p class="text-gray-300 text-sm">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-purple-400 hover:text-purple-300 transition-colors duration-200">
                        Register Now
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Password Toggle Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const togglePassword = (inputId, toggleId) => {
                const input = document.getElementById(inputId);
                const toggle = document.getElementById(toggleId);
                const eyeIcon = toggle.querySelector('.eye-icon');
                const eyeSlashIcon = toggle.querySelector('.eye-slash-icon');

                // Initially hide the toggle
                toggle.style.display = 'none';

                // Show toggle when user starts typing
                input.addEventListener('input', () => {
                    if (input.value.length > 0) {
                        toggle.style.display = 'flex';
                    } else {
                        toggle.style.display = 'none';
                    }
                });

                // Toggle password visibility and icons
                toggle.addEventListener('click', () => {
                    if (input.type === 'password') {
                        input.type = 'text';
                        eyeIcon.classList.add('hidden');
                        eyeSlashIcon.classList.remove('hidden');

                        // Revert back after 3 seconds
                        setTimeout(() => {
                            input.type = 'password';
                            eyeIcon.classList.remove('hidden');
                            eyeSlashIcon.classList.add('hidden');
                        }, 3000);
                    } else {
                        input.type = 'password';
                        eyeIcon.classList.remove('hidden');
                        eyeSlashIcon.classList.add('hidden');
                    }
                });
            };

            togglePassword('password', 'toggle-password');

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

                            // Debug: Log mouse coordinates
                            console.log('Mouse X:', mouseX, 'Mouse Y:', mouseY);

                            // Calculate velocity based on mouse movement
                            this.interactiveParticle.velocity.x = (mouseX - lastMouseX) * 0.1;
                            this.interactiveParticle.velocity.y = (mouseY - lastMouseY) * 0.1;

                            // Update the interactive particle's position
                            this.interactiveParticle.x = mouseX;
                            this.interactiveParticle.y = mouseY;

                            // Debug: Log interactive particle position and velocity
                            console.log('Interactive Particle X:', this.interactiveParticle.x, 'Y:', this.interactiveParticle.y);
                            console.log('Interactive Particle Velocity X:', this.interactiveParticle.velocity.x, 'Y:', this.interactiveParticle.velocity.y);

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
