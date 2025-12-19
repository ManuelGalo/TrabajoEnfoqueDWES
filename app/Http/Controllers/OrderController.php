<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart');

        if(!$cart){
            return redirect()->back()->with('error', 'El carrito está vacio');
        }

        

        //Transacción para guardar en la BD

        DB::beginTransaction();

        try{
            //calcular el total
            $total = 0;
            foreach($cart as $item){
                $total += $item['price'] * $item['quantity'];
            }
            //Crea el pedido
            $order =Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pendiente',
                'address' => $request->address ?? 'Recoger en tienda', 
            ]);
            //procesa cada producto del pedido
            foreach ($cart as $cartIndex => $details) {
                $product = Product::find($details['product_id']);
                //verificar stock
                $sizeRecord = ProductSize::where('product_id', $details['product_id'])
                                            ->where('size', $details['size'])
                                            ->first();
                if (!$sizeRecord || $sizeRecord->stock < $details['quantity']){
                    throw new \Exception("Lo sentimos, ya no tenemos stock suficiente de: " . $details['name'] . " (Talla " . $details['size'] . ")");
                }    
        
               //Crea detalles pedido
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'size' => $details['size'],
                ]);
                //se resta el stock
                $sizeRecord->decrement('stock', $details['quantity']);
            }
            DB::commit();

            //vaciar el carrito de la sesión
            session()->forget('cart');

            return view('cart.success', compact('order'));

        }catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', ' Error al procesar el pedido ' . $e->getMessage());
        }

    }
}
