@extends('partials.layout')

@section('content')
<main>
    <div class="informacion">
        <img src="{{ asset('images/' . $producto->image) }}" alt="{{ $producto->name }}" class="informacion__img" />
        <div class="informacion__datos">            
            <b>
                {{ $producto->name }} <br />
                ${{ number_format($producto->price, 0, ',', '.') }}
            </b>

            <p>Existencias: {{ $producto->stock }}</p>
            <p>
                <h3>Descripción</h3><br />
                {{ $producto->descrition }}
            </p>
            <div class="botonesInfo">
                <form action="{{ route('carrito.agregar', ['id' => $producto->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="informacion__boton-agregar">Agregar a carrito</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@section('style')
@vite('resources/css/infoProduc.css')
@endsection
