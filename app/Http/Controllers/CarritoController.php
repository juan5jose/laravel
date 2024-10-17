<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Output;
use App\Models\Productsoutput;

class CarritoController extends Controller
{
    public function agregarAlCarrito(Request $request, $id)
    {
        // Obtener el producto desde la base de datos
        $producto = Product::findOrFail($id);

        // Obtener el carrito de la sesión o un arreglo vacío si no existe
        $carrito = session()->get('carrito', []);

        // Si el producto ya está en el carrito, incrementamos la cantidad
        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad']++;
        } else {
            // Si no está, lo añadimos con cantidad 1
            $carrito[$id] = [
                'id' => $producto->id,
                'nombre' => $producto->name,
                'precio' => $producto->price,
                'cantidad' => 1,
                'imagen' => $producto->image,
            ];
        }

        // Guardamos el carrito actualizado en la sesión
        session()->put('carrito', $carrito);

        // Redirigimos al carrito o a la página actual
        return redirect()->route('carrito.ver')->with('success', 'Producto agregado al carrito');
    }

    // Ver el carrito
    public function verCarrito()
    {
        $carrito = session()->get('carrito', []);

        // Calcular subtotal
        $subtotal = 0;
        foreach ($carrito as $producto) {
            $subtotal += $producto['precio'] * $producto['cantidad'];
        }

        return view('carrito', compact('carrito', 'subtotal'));
    }

    // Eliminar completamente el producto del carrito
    public function eliminarDelCarrito($id)
    {
        // Obtener el carrito de la sesión
        $carrito = session()->get('carrito', []);

        // Verificar si el producto existe en el carrito
        if (isset($carrito[$id])) {
            // Eliminar el producto del carrito
            unset($carrito[$id]);

            // Actualizar el carrito en la sesión
            session()->put('carrito', $carrito);
        }

        // Redirigir de vuelta a la vista del carrito con un mensaje de éxito
        return redirect()->route('carrito.ver')->with('success', 'Producto eliminado del carrito');
    }

    public function actualizar(Request $request, $id)
    {
        // Obtener el carrito de la sesión
        $carrito = session()->get('carrito');

        // Verificar si el producto existe en el carrito
        if (isset($carrito[$id])) {
            // Aumentar o disminuir la cantidad
            if ($request->accion == 'aumentar') {
                $carrito[$id]['cantidad']++;
            } elseif ($request->accion == 'disminuir' && $carrito[$id]['cantidad'] > 1) {
                $carrito[$id]['cantidad']--;
            }
        }

        // Guardar el carrito actualizado en la sesión
        session()->put('carrito', $carrito);

        // Redireccionar de vuelta a la vista del carrito
        return redirect()->back();
    }

}
