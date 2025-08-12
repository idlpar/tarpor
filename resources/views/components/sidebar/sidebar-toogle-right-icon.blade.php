{{--<span>--}}
{{--    <svg class="h-8 w-8"  viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--      <!-- Right Arrow -->--}}
{{--      <path d="M14 6L20 12L14 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>--}}
{{--      <path d="M20 12H4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>--}}

{{--        <!-- Three Bars -->--}}
{{--      <line x1="4" y1="7" x2="10" y2="7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>--}}
{{--      <line x1="4" y1="12" x2="10" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>--}}
{{--      <line x1="4" y1="17" x2="10" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>--}}
{{--    </svg>--}}
{{--</span>--}}

@push('styles')
    <style>
        @keyframes rotateGradient {
            0% {
                --gradient-rotate: 0deg;
            }
            100% {
                --gradient-rotate: 360deg;
            }
        }

        .gradient-icon svg {
            animation: rotateGradient 5s linear infinite;
        }

        .gradient-icon svg radialGradient {
            gradientTransform: rotate(var(--gradient-rotate));
        }

        .gradient-icon svg path,
        .gradient-icon svg line {
            stroke: url(#gradient);
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .gradient-icon:hover svg {
            animation-play-state: paused;
        }
    </style>
@endpush

<span class="gradient-icon">
        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Define the Radial Gradient -->
            <defs>
                <radialGradient id="gradient" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
                    <stop offset="0%" stop-color="#FF7E5F" />
                    <stop offset="20%" stop-color="#FEB47B" />
                    <stop offset="40%" stop-color="#FF6A6A" />
                    <stop offset="60%" stop-color="#FFD700" />
                    <stop offset="80%" stop-color="#32CD32" />
                    <stop offset="100%" stop-color="#00BFFF" />
                </radialGradient>
            </defs>

            <!-- Right Arrow -->
            <path d="M14 6L20 12L14 18" stroke="url(#gradient)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M20 12H4" stroke="url(#gradient)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>

            <!-- Three Bars -->
            <line x1="4" y1="7" x2="10" y2="7" stroke="url(#gradient)" stroke-width="2" stroke-linecap="round"/>
            <line x1="4" y1="12" x2="10" y2="12" stroke="url(#gradient)" stroke-width="2" stroke-linecap="round"/>
            <line x1="4" y1="17" x2="10" y2="17" stroke="url(#gradient)" stroke-width="2" stroke-linecap="round"/>
        </svg>
    </span>
