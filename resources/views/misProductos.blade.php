@extends('partials.layout')

@section('content')
    <div class="containerCatalogo">
        <h3 class="title">Mis Productos</h3>

        <!-- Botón de agregar producto -->
        <div class="button-container">
            <a href="javascript:void(0)" class="add-product-btn" id="showAddProductModal" href="{{route('misProductos.store')}}">
                <span class="icon">+</span> <!-- Ícono de más -->
                <span class="text">Agregar Producto</span> <!-- Texto -->
            </a>
        </div>

        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

        <div class="products-container">
            <!-- Mostrar todos los productos -->
            @foreach ($productos as $producto)
                <div class="product" data-name="p-{{ $producto->id }}">
                    <img src="{{ asset('images/' . $producto->image) }}" alt="{{ $producto->name }}">
                    <h3>{{ $producto->name }}</h3>
                    <div class="price">${{ number_format($producto->price, 0, ',', '.') }}</div>

                    <!-- Botones de editar y eliminar -->
                    <div class="product-actions">
                        <button class="edit-btn" data-id="{{ $producto->id }}" data-name="{{ $producto->name }}"
                            data-descrition="{{ $producto->descrition }}" data-price="{{ $producto->price }}"
                            data-exits="{{ $producto->exits }}" data-image="{{ $producto->image }}">
                            Editar
                        </button>

                        <button class="delete-btn" data-id="{{ $producto->id }}" data-name="{{ $producto->name }}">
                            Bajas
                        </button>

                        <button class="entradas-btn" data-id="{{ $producto->id }}" data-name="{{ $producto->name }}">
                            Entradas
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal de edición -->
    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Producto</h2>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" id="productId" name="productId">

                <div class="form-group">
                    <label for="name">Nombre:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="descrition">Descripción:</label>
                    <input type="text" id="descrition" name="descrition" required>
                </div>

                <div class="form-group">
                    <label for="price">Precio:</label>
                    <input type="text" id="price" name="price" required>
                </div>

                <div class="form-group">
                    <label for="exits">Existencias:</label>
                    <input type="number" id="exits" name="exits" required>
                </div>

                <div class="form-group">
                    <label for="image">Imagen:</label>
                    <input type="file" id="image" name="image">
                </div>

                <button type="submit" class="save-btn">Guardar Cambios</button>
            </form>
        </div>
    </div>
@endsection

@section('style')
    @vite('resources/css/catalogo.css')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // Código para el modal de agregar producto
            document.getElementById('showAddProductModal').addEventListener('click', function() {
                const modal = document.getElementById('addProductModal');
                modal.style.display = "block"; // Mostrar el modal
            });

            // Cerrar el modal de agregar producto
            document.querySelector('.close-add-product').addEventListener('click', function() {
                document.getElementById('addProductModal').style.display = "none";
            });

            // Cerrar el modal de agregar producto cuando se haga clic fuera de él
            window.onclick = function(event) {
                const modal = document.getElementById('addProductModal');
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            // Código para el modal de edición
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const modal = document.getElementById('editModal');
                    modal.style.display = "block"; // Mostrar el modal

                    // Obtener los datos del producto desde los atributos del botón
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const descrition = this.getAttribute('data-descrition');
                    const price = this.getAttribute('data-price');
                    const exits = this.getAttribute('data-exits');
                    const image = this.getAttribute('data-image');

                    // Llenar la información del producto en el modal
                    document.getElementById('productId').value = id;
                    document.getElementById('name').value = name;
                    document.getElementById('descrition').value = descrition;
                    document.getElementById('price').value = price;
                    document.getElementById('exits').value = exits;

                    const editForm = document.getElementById('editForm');
                    editForm.action = `/products/${id}`;

                    // Aquí puedes agregar el código para mostrar la imagen si lo deseas
                });
            });

            // Cerrar el modal de edición
            document.querySelector('.close').addEventListener('click', function() {
                document.getElementById('editModal').style.display = "none";
            });

            // Cerrar el modal de edición cuando se haga clic fuera de él
            window.onclick = function(event) {
                const modal = document.getElementById('editModal');
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            // Código para el modal de bajas
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const modal = document.getElementById('bajasModal');
                    modal.style.display = "block"; // Mostrar el modal

                    // Obtener el ID y nombre del producto
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    // Llenar la información del producto en el modal
                    document.getElementById('bajasProductId').value = id;
                    document.getElementById('bajasProductoNombre').textContent = name;

                    // Actualizar la acción del formulario con la URL correcta
                    document.getElementById('bajasForm').action = `/bajas`;
                });
            });

            // Cerrar el modal de bajas
            document.querySelector('.close-bajas').addEventListener('click', function() {
                document.getElementById('bajasModal').style.display = "none";
            });

            // Cerrar el modal de bajas cuando se haga clic fuera de él
            window.onclick = function(event) {
                const modal = document.getElementById('bajasModal');
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            // Cancelar botón del modal de bajas
            document.querySelector('.cancel-btn').addEventListener('click', function() {
                document.getElementById('bajasModal').style.display = "none";
            });

            // Código para el modal de entradas
            document.querySelectorAll('.entradas-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const modal = document.getElementById('entradasModal');
                    modal.style.display = "block"; // Mostrar el modal

                    // Obtener el ID y nombre del producto
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    // Llenar la información del producto en el modal
                    document.getElementById('entradasProductId').value = id;
                    document.getElementById('producto_nombre').value =
                    name; // Se habilita solo en el modal
                    const entradas = document.getElementById('entradasForm');
                    entradas.action = `/entradas`;
                });
            });

            // Cerrar el modal de entradas
            document.querySelector('.close-entradas').addEventListener('click', function() {
                document.getElementById('entradasModal').style.display = "none";
            });

            // Cerrar el modal de entradas cuando se haga clic fuera de él
            window.onclick = function(event) {
                const modal = document.getElementById('entradasModal');
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });
    </script>
@endsection
