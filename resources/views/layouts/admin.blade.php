@extends('layouts.app')

@section('content')
<div class="flex bg-bg-light">
    <!-- Sidebar -->
    @include('components.admin.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- You might want a separate admin navbar here if needed --}}

        <main class="flex-1 overflow-x-hidden bg-bg-light p-4">
            @include('components.app.toast')
            <div class="container mx-auto px-4">
                @includeIf('components.admin.breadcrumb')
                @yield('admin_content')
            </div>
        </main>
    </div>
</div>
@endsection
