@extends('layouts.app')
@section('content')
<div class="quickview-overlay" id="quickview">
    <div class="quickview-box">

        <!-- CLOSE -->
        <button class="close-btn" onclick="closeQuickView()">✕</button>

        <!-- LEFT IMAGE -->
        <div class="quickview-left">
            <img src="{{ asset($product->image) }}" class="main-img">
        </div>

        <!-- RIGHT INFO -->
        <div class="quickview-right">

            <h2>{{ $product->name }}</h2>
            <h3>${{ $product->price }}</h3>

            <p>{{ $product->short_description }}</p>

            <p>{{ $product->description }}</p>

            <!-- SIZE -->
            <div class="sizes">
                <button>S</button>
                <button>M</button>
                <button>L</button>
                <button>XL</button>
            </div>

            <a href="{{ route('cart.add') }}" class="btn">Add to cart</a>

        </div>
    </div>
</div>

@endsection