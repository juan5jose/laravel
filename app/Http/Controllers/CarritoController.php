<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Cart;
use App\Models\Cartproduct;
use App\Models\Output;
use App\Models\Productsoutput;

class CarritoController extends Controller
{
    public function agregarAlCarrito(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);

        // Verificar si el producto ya está en el carrito
        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        // Verificar si el producto ya está en el carrito
        $cartItem = Cartproduct::where('product_id', $id)
            ->where('cart_id', $cart->id) // Usar el ID del carrito
            ->first();

        // Si el producto ya está en el carrito, incrementamos la cantidad
        if ($cartItem) {
            // Actualizar la cantidad
            $cartItem->amount += $quantity;
            $cartItem->save();
        } else {
            // Si no existe, crear un nuevo registro en la tabla intermedia
            Cartproduct::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'amount' => $quantity,
            ]);
        }

        // Redirigimos al carrito o a la página actual
        return redirect()->route('carrito.ver')->with('success', 'Producto agregado al carrito');
    }

    // Ver el carrito
    public function verCarrito()
    {
        // Obtener el carrito del usuario autenticado
        $cart = Cart::where('user_id', auth()->id())->first();

        // Inicializar el carrito y subtotal
        $carrito = [];
        $subtotal = 0;

        // Verificar si el carrito existe
        if ($cart) {
            // Obtener los productos del carrito
            $carrito = CartProduct::with('product') // Asegúrate de tener la relación definida
                ->where('cart_id', $cart->id)
                ->get();

            // Calcular subtotal
            foreach ($carrito as $item) {
                $subtotal += $item->product->precio * $item->amount; // Asegúrate de que 'precio' esté en el modelo Product
            }
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
        // Obtener el carrito del usuario autenticado
        $cart = Cart::where('user_id', auth()->id())->first();

        // Verificar si el carrito existe
        if ($cart) {
            // Verificar si el producto existe en el carrito
            $cartItem = CartProduct::where('cart_id', $cart->id)
                ->where('product_id', $id)
                ->first();

            if ($cartItem) {
                // Aumentar o disminuir la cantidad
                if ($request->accion == 'aumentar') {
                    $cartItem->amount++;
                } elseif ($request->accion == 'disminuir' && $cartItem->amount > 1) {
                    $cartItem->amount--;
                }

                // Guardar los cambios en la base de datos
                $cartItem->save();
            }
        }

        // Redireccionar de vuelta a la vista del carrito
        return redirect()->back();
    }
}
