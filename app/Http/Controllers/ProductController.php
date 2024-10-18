<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Método para mostrar todos los productos en la vista de misProductos
    public function index()
    {
        // Obtener los productos del usuario autenticado
        $productos = Product::where('user_id', auth()->id())->get();

        // Retornar la vista 'misProductos' con los productos del usuario
        return view('misProductos', compact('productos'));
    }

    // mostrar los productos en catalogo
    public function catalogo()
    {
        $productos = Product::all();

        // Retornar la vista 'catalogo' con la lista de productos
        return view('catalogo', compact('productos'));
    }

    // Método para almacenar un producto


    public function store(Request $request)
    {
        // Validar los datos del formulario, incluida la imagen
        $request->validate([
            'name' => 'required|string|max:255',
            'descrition' => 'required|string|max:255',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de la imagen
        ]);

        // Limpiar el campo price
        $price = str_replace(['$', ','], '', $request->input('price'));

        // Subir la imagen
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName); // Guardar la imagen en la carpeta 'img'
        }

        // Obtener el ID del usuario autenticado
        $userId = auth()->id();

        // Crear el producto en la base de datos
        $product = Product::create([
            'name' => $request->input('name'),
            'descrition' => $request->input('descrition'),
            'price' => (int) $price,
            'image' => $imageName, // Guardar el nombre de la imagen
            'stock' => $request->input('stock'),
            'user_id' => $userId,
        ]);


        return redirect()->route('misProductos')->with('success', 'Producto guardado exitosamente.');
    }


    // Método para editar un producto (formulario)
    public function edit($id)
    {
        // Buscar el producto por su ID
        $producto = Product::findOrFail($id);

        // Retornar la vista de edición con el producto
        return view('editProduct', compact('producto'));
    }

    // Método para actualizar un producto
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'descrition' => 'required|string|max:255',
            'price' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imagen
            'stock' => 'require|integer',

        ]);

        // Buscar el producto por su ID
        $producto = Product::findOrFail($id);

        // Limpiar el campo price
        $price = str_replace(['$', ','], '', $request->input('price'));

        // Actualizar el producto
        $producto->name = $request->input('name');
        $producto->descrition = $request->input('descrition');
        $producto->price = (int) $price;
        $producto->stock = $request->input('exits');

        // Manejar la imagen
        if ($request->hasFile('image')) {
            // Eliminar la imagen antigua si existe
            if ($producto->image) {
                $oldImagePath = public_path('images/' . $producto->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Subir la nueva imagen
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            // Actualizar el nombre de la imagen en el modelo
            $producto->image = $imageName;
        }

        // Guardar los cambios
        $producto->save();

        // Redirigir a la vista de misProductos
        return redirect()->route('misProductos')->with('success', 'Producto actualizado exitosamente.');
    }

    // mostrar la info de los productos en el carrito
    public function show($id)
    {
        // Busca el producto por su ID y carga las relaciones
        $producto = Product::findOrFail($id);

        // Retorna la vista con los datos del producto
        return view('infoProduc', compact('producto'));
    }
}
