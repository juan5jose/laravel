@extends('partials.layout')

@section('content')
    <div>
        <h3>Mis Productos</h3>

        <!-- Botón de agregar producto -->
        <div>
            <a href="{{route('misProductos.store')}}" id="showAddProductModal">
                <span>+</span> <!-- Ícono de más -->
                <span>Agregar Producto</span> <!-- Texto -->
            </a>
        </div>

        

        <div>
            <!-- Mostrar todos los productos -->
            @foreach ($productos as $producto)
                <div data-name="p-{{ $producto->id }}">
                    <img src="{{ asset('images/' . $producto->image) }}" alt="{{ $producto->name }}">
                    <h3>{{ $producto->name }}</h3>
                    <div>{{$producto->stock}} </div>
                    <div>${{ number_format($producto->price, 0, ',', '.') }}</div>

                    <!-- Botones de editar y eliminar -->
                    <div>
                        <button class="edit-btn" data-id="{{ $producto->id }}" data-name="{{ $producto->name }}"
                            data-descrition="{{ $producto->descrition }}" data-price="{{ $producto->price }}"
                            data-exits="{{ $producto->stock }}" data-image="{{ $producto->image }}">
                            Editar
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

                <div>
                    <label for="name">Nombre:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div>
                    <label for="descrition">Descripción:</label>
                    <input type="text" id="descrition" name="descrition" required>
                </div>

                <div>
                    <label for="price">Precio:</label>
                    <input type="text" id="price" name="price" required>
                </div>

                <div>
                    <label for="exits">Existencias:</label>
                    <input type="number" id="exits" name="exits" required>
                </div>

                <div>
                    <label for="image">Imagen:</label>
                    <input type="file" id="image" name="image">
                </div>

                <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {

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
        });
    </script>
@endsection
