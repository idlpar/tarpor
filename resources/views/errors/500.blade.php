@extends('layouts.app')

@push('styles')
    <style>
        /* Particle Canvas Style */
        #particle-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        /* Smooth Gradient Text Animation with Six Color Transitions */
        .gradient-text {
            font-size: 8rem;
            font-weight: 800;
            text-transform: uppercase;
            background: linear-gradient(90deg, #00c6ff, #0072ff, #FF4B2B, #FF416C, #FDCB82, #6D4C41);
            background-size: 400% 400%;
            animation: gradient-animation 6s linear infinite;
            -webkit-background-clip: text;
            color: transparent;
        }

        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }
            20% {
                background-position: 20% 50%;
            }
            40% {
                background-position: 40% 50%;
            }
            60% {
                background-position: 60% 50%;
            }
            80% {
                background-position: 80% 50%;
            }
            100% {
                background-position: 100% 50%;
            }
        }

        /* Centered Text and Button */
        .error-message {
            text-align: center;
            color: #ffffff;
            z-index: 1;
            position: relative;
            padding: 2rem;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #0072ff;
            color: white;
            font-size: 1.125rem;
            font-weight: 600;
            text-transform: uppercase;
            border-radius: 4px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .button:hover {
            background-color: #00c6ff;
            transform: translateY(-3px);
        }

        .button:active {
            transform: translateY(1px);
        }

        /* Adjusted SVG Icon */
        .icon {
            fill: #0072ff;
            opacity: 0.5;
            width: 7rem;
            height: 7rem;
            margin-top: 1.5rem;
        }
    </style>
@endpush

@section('title', '500 - Internal Server Error | ' . strtoupper(config('app.name')))

@section('content')
    <div class="relative min-h-screen flex items-center justify-center bg-gray-900 overflow-hidden">
        <!-- Particle canvas -->
        <div id="particle-canvas"></div>

        <!-- Error message content -->
        <div class="error-message">
            <h1 class="gradient-text">500</h1>
            <p class="text-lg mt-4">An unexpected error occurred on our end. We are working on fixing it!</p>
            <p class="mt-4 text-gray-300">Please try again later.</p>

            <div class="mt-8">
                <a href="{{ route('home') }}" class="button">Go Back Home</a>
            </div>

            <!-- Error Icon -->
            <svg class="icon mx-auto mt-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L2 12l10 10 10-10L12 2z"></path>
                <path d="M12 8v4"></path>
                <path d="M12 16h.01"></path>
            </svg>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Professional geometric particle network for a unique and clean look
        (function () {
            class Particle {
                constructor(canvas, ctx, x, y, velocity) {
                    this.canvas = canvas;
                    this.ctx = ctx;
                    this.x = x;
                    this.y = y;
                    this.velocity = velocity;
                    this.size = Math.random() * 3 + 2;
                    this.color = 'rgba(0, 174, 255, 0.8)';
                    this.angle = Math.random() * Math.PI * 2;
                }

                update() {
                    this.x += this.velocity.x;
                    this.y += this.velocity.y;

                    // Recycle particles when they go out of canvas
                    if (this.x > this.canvas.width) this.x = 0;
                    if (this.x < 0) this.x = this.canvas.width;
                    if (this.y > this.canvas.height) this.y = 0;
                    if (this.y < 0) this.y = this.canvas.height;
                }

                draw() {
                    this.ctx.beginPath();
                    this.ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    this.ctx.fillStyle = this.color;
                    this.ctx.fill();
                }
            }

            class ParticleNetwork {
                constructor(container) {
                    this.container = container;
                    this.canvas = document.createElement('canvas');
                    this.container.appendChild(this.canvas);
                    this.ctx = this.canvas.getContext('2d');
                    this.particles = [];
                    this.velocity = 0.5;
                    this.numParticles = 300;

                    window.addEventListener('resize', () => this.setCanvasSize());
                    this.setCanvasSize();
                    this.createParticles();
                    this.animate();
                }

                setCanvasSize() {
                    this.canvas.width = window.innerWidth;
                    this.canvas.height = window.innerHeight;
                }

                createParticles() {
                    for (let i = 0; i < this.numParticles; i++) {
                        const x = Math.random() * this.canvas.width;
                        const y = Math.random() * this.canvas.height;
                        const velocity = {
                            x: (Math.random() - 0.5) * this.velocity,
                            y: (Math.random() - 0.5) * this.velocity
                        };
                        this.particles.push(new Particle(this.canvas, this.ctx, x, y, velocity));
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
            }

            // Initialize Particle Network
            const canvasDiv = document.getElementById('particle-canvas');
            new ParticleNetwork(canvasDiv);
        })();
    </script>
@endpush
