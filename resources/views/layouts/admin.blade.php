@extends('layouts.app')

@section('content')
<div class="flex bg-gray-100">
    <!-- Sidebar -->
    @include('components.admin.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- You might want a separate admin navbar here if needed --}}

        <main class="flex-1 overflow-x-hidden bg-gray-100 p-4">
            @includeIf('components.admin.breadcrumb')
            @yield('admin_content')
        </main>
    </div>
</div>
@endsection