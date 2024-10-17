@extends('partials.layout')

@section('content')
<div class="containerCatalogo">
    <h3 class="title">Catalogo</h3>

    <div class="products-container">
        <!-- Mostrar todos los productos -->
        @foreach($productos as $producto)
            <a href="{{ route('products.show', $producto->id) }}">
                <div class="product" data-name="p-{{ $producto->id }}">
                    <img src="{{ asset('images/' . $producto->image) }}" alt="{{ $producto->name }}">
                    <h3>{{ $producto->name }}</h3>
                    <div class="price">${{ number_format($producto->price, 0, ',', '.') }}</div>
                </div>
            </a>
        @endforeach
    </div>

</div>
@endsection

@section('style')
@vite('resources/css/catalogo.css')
@endsection
