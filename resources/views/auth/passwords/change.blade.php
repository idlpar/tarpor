@extends('layouts.app')

@section('title', 'Change Password - TARPOR | Secure User Authentication')

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
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Particle Canvas -->
        <canvas id="particle-canvas" class="absolute inset-0 z-0"></canvas>

        <!-- Main Content -->
        <div class="max-w-md w-full bg-gray-800/80 backdrop-blur-lg border border-gray-700 p-10 rounded-2xl shadow-2xl transition-all hover:-translate-y-1 relative z-10">
            <!-- Header Section -->
            <div class="text-center">
                <div class="mx-auto mb-4 flex justify-center">
                    <div class="p-3 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-lg shadow-lg">
                        <!-- Key Icon -->
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent">
                    Change Password
                </h2>
                <p class="mt-2 text-sm text-gray-200">
                    Update your account password securely
                </p>
            </div>

            <!-- Change Password Form -->
            <form class="mt-8 space-y-6" method="POST" action="{{ route('password.change') }}">
                @csrf

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-300 mb-2">Current Password</label>
                    <div class="relative">
                        <input
                            id="current_password"
                            name="current_password"
                            type="password"
                            required
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400 text-gray-100 transition-all duration-200"
                            placeholder="Enter your current password"
                        >
                        <span id="toggle-current-password" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" aria-label="Toggle password visibility">
                            <!-- Eye Icon (Show Password) -->
                            <svg class="eye-icon h-5 w-5 text-purple-300 hover:text-purple-200 transition-colors duration-200" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path fill="currentColor" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"></path>
                            </svg>
                            <!-- Eye-Slash Icon (Hide Password, Hidden by Default) -->
                            <svg class="eye-slash-icon h-5 w-5 text-purple-300 hover:text-purple-200 transition-colors duration-200 hidden" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye-slash" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                <path fill="currentColor" d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z"></path>
                            </svg>
                        </span>
                        @error('current_password')
                        <p id="current-password-error" class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- New Password -->
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                    <div class="relative">
                        <input
                            id="new_password"
                            name="new_password"
                            type="password"
                            required
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400 text-gray-100 transition-all duration-200"
                            placeholder="Enter your new password"
                        >
                        <span id="toggle-new-password" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" aria-label="Toggle password visibility">
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
                    <div id="password-requirements" class="mt-2 text-sm text-gray-400 hidden">
                        <ul>
                            <li id="uppercase" class="text-red-400">At least one uppercase letter</li>
                            <li id="lowercase" class="text-red-400">At least one lowercase letter</li>
                            <li id="number" class="text-red-400">At least one number</li>
                            <li id="special" class="text-red-400">At least one special character</li>
                            <li id="length" class="text-red-400">At least 8 characters</li>
                        </ul>
                    </div>
                    @error('new_password')
                    <p id="new-password-error" class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirm New Password</label>
                    <div class="relative">
                        <input
                            id="new_password_confirmation"
                            name="new_password_confirmation"
                            type="password"
                            required
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400 text-gray-100 transition-all duration-200"
                            placeholder="Confirm your new password"
                        >
                        <span id="toggle-confirm-password" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" aria-label="Toggle password visibility">
                            <!-- Eye Icon (Show Password) -->
                            <svg class="eye-icon h-5 w-5 text-purple-300 hover:text-purple-200 transition-colors duration-200" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path fill="currentColor" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"></path>
                            </svg>
                            <!-- Eye-Slash Icon (Hide Password, Hidden by Default) -->
                            <svg class="eye-slash-icon h-5 w-5 text-purple-300 hover:text-purple-200 transition-colors duration-200 hidden" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye-slash" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                <path fill="currentColor" d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z"></path>
                            </svg>
                        </span>
                        @error('new_password_confirmation')
                        <p id="new-password-confirmation-error" class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="group w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-purple-900/30 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5 text-purple-200 transition-all duration-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17a2 2 0 100-4 2 2 0 000 4z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10V7a5 5 0 0110 0v3"/>
                        <rect x="5" y="10" width="14" height="10" rx="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Change Password</span>
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Particle Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('particle-canvas');
            const ctx = canvas.getContext('2d');

            let particlesArray = [];
            const numberOfParticles = 100;
            const colors = ['#6C5CE7', '#0984E3', '#00B894', '#FDCB6E', '#E17055', '#D63031']; // Distinct color scheme

            // Set canvas size
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            // Particle class
            class Particle {
                constructor(x, y, size, color, weight) {
                    this.x = x;
                    this.y = y;
                    this.size = size;
                    this.color = color;
                    this.weight = weight;
                }

                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2, false);
                    ctx.fillStyle = this.color;
                    ctx.fill();
                }

                update() {
                    this.size -= 0.05; // Shrink particles over time
                    if (this.size < 0) {
                        this.x = Math.random() * canvas.width;
                        this.y = Math.random() * canvas.height;
                        this.size = Math.random() * 5 + 2;
                        this.weight = Math.random() * 2 - 1;
                    }
                    this.y += this.weight;
                    this.weight += 0.1; // Gravity effect

                    // Bounce off the bottom
                    if (this.y > canvas.height - this.size) {
                        this.weight *= -0.6;
                    }
                }
            }

            // Initialize particles
            function init() {
                particlesArray = [];
                for (let i = 0; i < numberOfParticles; i++) {
                    const x = Math.random() * canvas.width;
                    const y = Math.random() * canvas.height;
                    const size = Math.random() * 5 + 2;
                    const color = colors[Math.floor(Math.random() * colors.length)];
                    const weight = Math.random() * 2 - 1;
                    particlesArray.push(new Particle(x, y, size, color, weight));
                }
            }

            // Animate particles
            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                particlesArray.forEach(particle => {
                    particle.update();
                    particle.draw();
                });
                requestAnimationFrame(animate);
            }

            // Handle window resize
            window.addEventListener('resize', () => {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                init();
            });

            init();
            animate();
        });
    </script>
    <!-- Password Toggle Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Toggle Password Visibility
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

            // Initialize password toggles
            togglePassword('current_password', 'toggle-current-password');
            togglePassword('new_password', 'toggle-new-password');
            togglePassword('new_password_confirmation', 'toggle-confirm-password');

            // Password Validation Logic
            const passwordInput = document.querySelector("input[name='new_password']");
            const confirmPasswordInput = document.querySelector("input[name='new_password_confirmation']");
            const passwordRequirements = document.getElementById('password-requirements');
            const newPasswordError = document.getElementById('new-password-error');
            const confirmPasswordError = document.getElementById('confirm-password-error');

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
                    newPasswordError.classList.add('hidden');
                } else {
                    passwordRequirements.classList.remove('hidden');
                    newPasswordError.textContent = 'Password does not meet the requirements.';
                    newPasswordError.classList.remove('hidden');
                }
            };

            // Function to validate password match
            const validatePasswordMatch = () => {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (password !== confirmPassword) {
                    confirmPasswordError.textContent = 'Passwords do not match.';
                    confirmPasswordError.classList.remove('hidden');
                } else {
                    confirmPasswordError.classList.add('hidden');
                }
            };

            // Attach validation to password inputs
            if (passwordInput && confirmPasswordInput) {
                passwordInput.addEventListener('input', () => {
                    validatePassword();
                    validatePasswordMatch();
                });

                confirmPasswordInput.addEventListener('input', validatePasswordMatch);
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
            clearErrorOnInput('current_password', 'current-password-error');
            clearErrorOnInput('new_password', 'new-password-error');
            clearErrorOnInput('new_password_confirmation', 'new-password-confirmation-error');
        });
    </script>
@endpush
