@extends('partials.layout') <!-- Asegúrate de que esta vista base está configurada correctamente -->

@section('content')
    <div>
        <div>
            <h2>Agregar Producto</h2>

            <form method="POST" enctype="multipart/form-data" action="{{ route('products.store') }}">
                @csrf

                <div class="form-group">
                    <label for="addProductName">Nombre:</label>
                    <input type="text" id="addProductName" name="name" required>
                </div>

                <div>
                    <label for="addProductDescription">Descripción:</label>
                    <input type="text" id="addProductDescription" name="descrition" required>
                </div>

                <div>
                    <label for="addProductPrice">Precio:</label>
                    <input type="text" id="addProductPrice" name="price" required>
                </div>

                <div>
                    <label for="addProductExits">Existencias:</label>
                    <input type="number" id="addProductExits" name="stock" required>
                </div>

                <div>
                    <label for="addProductImage">Imagen:</label>
                    <input type="file" id="addProductImage" name="image" required>
                </div>

                <button type="submit">Agregar Producto</button>
            </form>
        </div>
    </div>
@endsection
