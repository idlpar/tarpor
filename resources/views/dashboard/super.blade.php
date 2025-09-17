@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">
                Logout
            </button>
        </form>
        <h1>Super Admin Dashboard</h1>
        <p>Welcome Super Admin!</p>
    </div>
@endsection
