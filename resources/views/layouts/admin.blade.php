@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    @include('components.admin.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- You might want a separate admin navbar here if needed --}}

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4">
            @yield('admin_content')
        </main>
    </div>
</div>
@endsection