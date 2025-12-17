<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
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
            $total = 0;
            foreach($cart as $item){
            $total += $item['price'] * $item['quantity'];
            }
            //1. Crea el pedido
            $order =Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pendiente',
                'address' => $request->address ?? 'Recoger en tienda', 
            ]);
            //2. Crea detalles pedido
            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
            }
            DB::commit();

            //3. vaciar el carrito de la sesión
            session()->forget('cart');

            return view('cart.success', compact('order'));

        }catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', ' Error al procesar el pedido ' . $e->getMessage());
        }

    }
}
