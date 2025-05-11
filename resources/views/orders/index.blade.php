@extends('layouts.app')
@section('content')
    <h1>Your Orders</h1>
    <table class="table">
        <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->product->name }}</td>
                <td>{{ $order->quantity }}</td>
                <td>{{ number_format($order->total_price, 2) }}</td>
                <td>{{ $order->status }}</td>
                <td>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-info">View</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $orders->links() }}
@endsection
