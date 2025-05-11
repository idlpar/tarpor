@extends('layouts.app')
@section('title', '401 - Unauthorized | ' . strtoupper(config('app.name')))

@push('styles')
    <style>
        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 4s ease-in-out infinite; }

        /* Glowing animation */
        @keyframes glow {
            0%, 100% { text-shadow: 0 0 10px rgba(16, 185, 129, 0.8); }
            50% { text-shadow: 0 0 20px rgba(16, 185, 129, 1); }
        }
        .animate-glow { animation: glow 3s ease-in-out infinite; }

        /* Gradient shift animation */
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-gradient-shift {
            background: linear-gradient(-45deg, #10b981, #059669, #10b981, #059669);
            background-size: 200% 200%;
            animation: gradient-shift 8s ease infinite;
        }

        /* Pulsating scale animation */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .animate-pulse { animation: pulse 2s ease-in-out infinite; }

        /* Rotating gradient animation */
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .animate-rotate {
            background: linear-gradient(-45deg, #10b981, #059669, #10b981, #059669);
            background-size: 200% 200%;
            animation: rotate 10s linear infinite;
        }

        /* Flickering light animation */
        @keyframes flicker {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .animate-flicker { animation: flicker 1.5s ease-in-out infinite; }

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

        /* Gradient flow through each digit */
        .gradient-flow {
            background: linear-gradient(-45deg, #10b981, #059669, #10b981, #059669);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: gradient-shift 8s ease infinite;
        }
    </style>
@endpush

@section('content')
    <div class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 to-gray-800">
        <!-- Particle canvas -->
        <div id="particle-canvas"></div>

        <!-- Main content -->
        <div class="text-center max-w-2xl px-4 relative z-10">
            <div class="relative">
                <!-- Floating, glowing, and animated container -->
                <div class="animate-float">
                    <h1 class="text-9xl font-bold gradient-flow">
                        401
                    </h1>
                </div>

                <!-- Subtle shadow effect -->
                <div class="absolute inset-0 -z-10 blur-md opacity-50">
                    <h1 class="text-9xl font-bold gradient-flow">
                        401
                    </h1>
                </div>
            </div>

            <p class="text-2xl mt-6 font-light text-gray-300">Unauthorized Access</p>

            <p class="mt-4 text-gray-400">
                You must be authenticated to access this page. Please log in or provide valid credentials.
            </p>

            <div class="mt-8">
                <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold uppercase rounded-lg shadow-lg hover:from-green-700 hover:to-emerald-700 hover:shadow-xl transition-all duration-300">
                    Log In
                </a>
            </div>

            <div class="mt-12">
                <svg class="w-24 h-24 mx-auto text-green-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
                        particleColor: options.particleColor || '#10b981', // Green color for particles
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

                        this.canvas.addEventListener('mousemove', (event) => {
                            this.interactiveParticle.x = event.clientX;
                            this.interactiveParticle.y = event.clientY;
                        });

                        this.canvas.addEventListener('mouseup', () => {
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
                particleColor: '#10b981', // Green color for particles
                background: 'transparent',
                interactive: true,
                speed: 'medium',
                density: 'high'
            };
            new ParticleNetwork(canvasDiv, options);
        })();
    </script>
@endpush
