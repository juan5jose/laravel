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

            <?php 
            $total = 0;

            // Verificar si hay productos de entrada y sumar sus cantidades
            if ($producto->productsoinput && $producto->productsoinput->isNotEmpty()) {
                foreach ($producto->productsoinput as $input) {
                    $total += $input->amount; // Acceso correcto a la propiedad amount
                }
            }

            // Verificar si hay productos de salida y restar sus cantidades
            if ($producto->productsoutput) {
                foreach ($producto->productsoutput as $output) {
                    $total -= $output->amount;
                }
            }

            // Verificar si hay productos en espera y restar sus cantidades
            if ($producto->productslow) {
                foreach ($producto->productslow as $slow) {
                    $total -= $slow->amount;
                }
            }
            ?>

            <p>Existencias: {{ $total }}</p>
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
