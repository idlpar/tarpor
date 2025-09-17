@extends('layouts.app')
 @push('styles')
     <style>
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
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-indigo-900 to-gray-800 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Particle Canvas -->
        <canvas id="particle-canvas" class="absolute inset-0 z-0"></canvas>

        <!-- Main Content -->
        <div class="max-w-md w-full space-y-8 bg-gray-800/90 backdrop-blur-lg rounded-3xl shadow-xl border border-gray-700 p-10 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 relative z-10">

            <!-- Icon Section -->
            <div class="mx-auto flex justify-center">
                <div class="p-4 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-full shadow-lg animate-pulse">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>

            <!-- Header Section -->
            <h2 class="animated-gradient text-3xl font-semibold text-center text-transparent bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text">
                Reset Your Password
            </h2>
            <p class="text-sm text-gray-400 text-center">Enter your email to receive a reset code</p>

            <!-- Session Messages -->
            @if(session('status'))
                <div class="p-4 bg-green-800/30 border border-green-600 rounded-lg text-green-400 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-800/30 border border-red-600 rounded-lg text-red-400 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-md font-medium text-gray-300 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required autofocus
                           class="w-full px-4 py-3 text-lg bg-gray-700/50 border border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400 text-gray-100 transition-all duration-200"
                           placeholder="your@email.com">
                    @error('email')
                    <p id="email-error" class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="group w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-purple-900/30 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5 text-purple-200 group-hover:text-white transition-all duration-200 transform group-hover:translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3 3L22 4"/>
                    </svg>
                    <span class="group-hover:text-white transition-all duration-200 group-hover:translate-y-1">Send Reset OTP</span>
                </button>

            </form>

            <!-- Back to Login Link -->
            <div class="text-center pt-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition-all">
                    <span class="inline-block transform transition-transform hover:scale-105">
                        <i class="fa-solid fa-left-long"></i> &nbsp;
                    </span> Back to Login
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('particle-canvas');
            const ctx = canvas.getContext('2d');

            if (!ctx) {
                console.error("Canvas context not found!");
                return;
            }

            let particlesArray = [];
            const numberOfParticles = 100;
            const colors = ['#6C5CE7', '#0984E3', '#00B894', '#FDCB6E', '#E17055', '#D63031'];

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
                    this.size -= 0.05;
                    if (this.size < 0) {
                        this.x = Math.random() * canvas.width;
                        this.y = Math.random() * canvas.height;
                        this.size = Math.random() * 5 + 2;
                        this.weight = Math.random() * 2 - 1;
                    }
                    this.y += this.weight;
                    this.weight += 0.1;

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
    </script>

@endpush
