@extends('layouts.app')

@section('content')
    <div class="container">
        @include('components.breadcrumbs', [
            'links' => [
                'My Addresses' => null
            ]
        ])
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">My Addresses</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="mb-4">
                            <a href="{{ route('profile.addresses.create') }}" class="btn btn-primary">
                                Add New Address
                            </a>
                        </div>

                        @if($addresses->isEmpty())
                            <p>You haven't added any addresses yet.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Label</th>
                                        <th>Address</th>
                                        <th>Default</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($addresses as $address)
                                        <tr>
                                            <td>{{ $address->label ?? 'N/A' }}</td>
                                            <td>
                                                {{ $address->street_address }}<br>
                                                {{ $address->union ? $address->union . ', ' : '' }}
                                                {{ $address->upazila ? $address->upazila . ', ' : '' }}
                                                {{ $address->district }}, {{ $address->division }}<br>
                                                {{ $address->postal_code }}
                                            </td>
                                            <td>
                                                @if($address->is_default)
                                                    <span class="badge badge-success">Default</span>
                                                @else
                                                    <form action="{{ route('profile.addresses.set-default', $address) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Set Default</button>
                                                    </form>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('profile.addresses.edit', $address) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('profile.addresses.destroy', $address) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
