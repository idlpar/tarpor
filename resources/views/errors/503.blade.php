@extends('layouts.app')

@section('title', '503 - Service Unavailable  | ' . strtoupper(config('app.name')))

@push('styles')
    <style>
        /* Tailwind Custom Class for Particle Canvas */
        #particle-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        /* Smooth color transition for "503" */
        .gradient-text {
            background-image: linear-gradient(to right, #6ee7b7, #3b82f6, #9333ea, #f97316, #f43f5e);
            background-size: 400% 400%;
            color: transparent;
            -webkit-background-clip: text;
            animation: gradient-animation 3s ease infinite;
        }

        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 to-gray-800 overflow-hidden">
        <!-- Particle canvas -->
        <div id="particle-canvas"></div>

        <!-- Main content -->
        <div class="relative z-10 text-center px-4 max-w-2xl">
            <h1 class="text-9xl font-extrabold gradient-text animate-pulse">
                503
            </h1>
            <p class="mt-6 text-xl font-light text-gray-300">Service Unavailable</p>
            <p class="mt-4 text-gray-400">Our services are temporarily unavailable. We are working to restore them as soon as possible.</p>

            <div class="mt-8">
                <a href="{{ route('home') }}" class="inline-block px-6 py-3 text-white font-semibold rounded-lg bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-400 hover:to-purple-400 transition duration-300 ease-in-out">
                    Return Home
                </a>
            </div>

            <div class="mt-12">
                <svg class="w-24 h-24 mx-auto text-indigo-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            // Particle class for a futuristic wave-like effect
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
                    this.x += this.velocity.x;
                    this.y += this.velocity.y;

                    if (this.x > this.canvas.width) {
                        this.x = 0;
                    } else if (this.x < 0) {
                        this.x = this.canvas.width;
                    }
                    if (this.y > this.canvas.height) {
                        this.y = 0;
                    } else if (this.y < 0) {
                        this.y = this.canvas.height;
                    }
                }

                draw() {
                    this.ctx.beginPath();
                    this.ctx.fillStyle = this.particleColor;
                    this.ctx.globalAlpha = 0.8;
                    this.ctx.arc(this.x, this.y, 2, 0, Math.PI * 2);
                    this.ctx.fill();
                }
            }

            // Particle Network class
            class ParticleNetwork {
                constructor(container, options) {
                    this.container = container;
                    this.options = {
                        particleColor: options.particleColor || '#ffffff',
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
                        this.canvas.addEventListener('mousemove', (event) => {
                            const waveVelocity = { x: (Math.random() - 0.5) * this.options.velocity, y: (Math.random() - 0.5) * this.options.velocity };
                            for (let i = 0; i < 5; i++) {
                                this.particles.push(new Particle(this.canvas, this.ctx, this.options.particleColor, event.clientX, event.clientY, waveVelocity));
                            }
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

            // Initialize Particle Network with wave effect
            const canvasDiv = document.getElementById('particle-canvas');
            const options = {
                particleColor: '#ffffff', // White particles
                background: 'transparent',
                interactive: true,
                speed: 'medium',
                density: 'high'
            };
            new ParticleNetwork(canvasDiv, options);
        })();
    </script>
@endpush
