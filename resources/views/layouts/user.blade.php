@extends('layouts.app')

@section('content')
    <div class="flex flex-col w-full mx-auto md:flex-row min-h-full" x-data="{ openSubmenu: null, isSidebarCollapsed: false }">
        <!-- Left Side Navigation Bar -->
        @include('partials.sidebars.user-sidebar')

        @yield('page-content')
    </div>
@endsection
