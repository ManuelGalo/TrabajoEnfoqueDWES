<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Mostrar checkout
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'El carrito está vacío');
        }

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('cart.checkout', compact('cart', 'total'));
    }

    // Crear pedido
    public function store(Request $request)
    {
        \Log::info('INICIANDO store - Cart: ' . json_encode(session('cart')));
        
        $cart = session()->get('cart');
        if (!$cart) {
            return redirect()->back()->with('error', 'El carrito está vacío');
        }

        try {
            $order = app(OrderService::class)->createOrderFromCart($cart, [
                'user_id' => Auth::id(),
                'address' => $request->address ?? 'Recoger en tienda',
            ]);

            session()->forget('cart');
            
            // Redirigir a página de pago (NO a success todavía)
            return redirect()->route('order.payment', $order->id);
            
        } catch (\Exception $e) {
            \Log::error('Error OrderController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    // Mostrar página de pago
    public function payment(Order $order)
    {
        // Verificar que el pedido pertenece al usuario
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('cart.payment', compact('order'));
    }

    // Confirmar pago (simulado)
    public function confirmPayment(Order $order)
    {
        // Verificar que el pedido pertenece al usuario
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Cambiar estado a "pagado"
        $order->update(['status' => 'pagado']);

        return redirect()->route('order.success', $order->id)
            ->with('success', '¡Pago confirmado! Tu pedido está siendo procesado.');
    }

    // Página de éxito
    public function success(Order $order)
    {
        // Verificar que el pedido pertenece al usuario
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('cart.success', compact('order'));
    }
}
