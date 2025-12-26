<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('INICIANDO store - Cart: ' . json_encode(session('cart')));
        
        $cart = session()->get('cart');
        if (!$cart) {
            return redirect()->back()->with('error', 'El carrito está vacío');
        }

        try {
            // ← AQUÍ LLAMAS AL SERVICE (NO lo reemplazas)
            $order = app(OrderService::class)->createOrderFromCart($cart, [
                'user_id' => Auth::id(),
                'address' => $request->address ?? 'Recoger en tienda',
            ]);

            session()->forget('cart');
            return view('cart.success', compact('order'));
            
        } catch (\Exception $e) {
            \Log::error('Error OrderController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }
}
