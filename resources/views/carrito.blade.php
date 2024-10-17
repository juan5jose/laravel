@extends('partials.layout')

@section('content')
    <main class="cart">
        <div class="products">
            <div class="volver">
                <a href="{{ route('catalogo') }}">
                    <i class="fa-solid fa-arrow-left"></i> Continuar comprando
                </a>
            </div>
            <div class="items">
                <b>Carrito de compras</b> <br> <br>
                <p>Tienes {{ count($carrito) }} productos en tu carrito</p>
            </div>

            @foreach ($carrito as $id => $producto)
                <div class="producto">
                    <img src="{{ asset('images/' . $producto['imagen']) }}" alt="{{ $producto['nombre'] }}" />
                    <!-- Cambia 'image' a 'imagen' -->
                    <div class="infoProduc">
                        <b>{{ $producto['nombre'] }}</b> <!-- Cambia 'name' a 'nombre' -->
                        {{-- <p>{{ $producto['nombre'] }}</p> --}}
                    </div>
                    <!-- Control de cantidad con flechas -->
                    <div class="cantidad-control">
                        <!-- Botón para aumentar cantidad -->
                        <form method="POST" action="{{ route('carrito.actualizar', $id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="accion" value="aumentar">
                            <button type="submit" class="btn-cantidad">
                                <i class="fa-solid fa-chevron-up flecha_arriba"></i> <!-- Flecha hacia arriba -->
                            </button>
                        </form>


                        <p class="cantidad">{{ $producto['cantidad'] }}</p>

                        <!-- Botón para disminuir cantidad -->
                        <form method="POST" action="{{ route('carrito.actualizar', $id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="accion" value="disminuir">
                            <button type="submit" class="btn-cantidad">
                                <i class="fa-solid fa-chevron-down flecha_abajo"></i><!-- Flecha hacia abajo -->
                            </button>
                        </form>

                    </div>
                    <div class="precio">
                        <b>${{ number_format($producto['precio'] * $producto['cantidad'], 0, ',', '.') }}</b>
                        <!-- Cambia 'price' a 'precio' -->
                        <form method="POST" action="{{ route('carrito.eliminar', $id) }}">
                            @csrf
                            <button type="submit">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>

                    </div>
                </div>
            @endforeach
        


        </div>
    </main>
@endsection

@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/carrito.css')
@endsection

@section('javaScript')
    @vite('resources/js/payment.js')
    <script src="https://js.stripe.com/v3/"></script>
@endsection
