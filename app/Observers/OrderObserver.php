<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\ProductSize;

class OrderObserver
{
    
    // Recalcular total automáticamente después de guardar
    
    public function saved(Order $order)
    {
        // Solo recalcular si tiene items
        if ($order->items->count() === 0) {
            \Log::info("OrderObserver: Order #{$order->id} sin items, NO recalcular");
            return;
        }

        $newTotal = $order->items->sum(fn($item) => 
            $item->quantity * $item->price
        );
        
        if ($order->total_amount != $newTotal) {
            $order->updateQuietly(['total_amount' => $newTotal]);
        }
    }

    
    // Devolver stock cuando se elimina el pedido completo
     
    public function deleting(Order $order)
    {
        \Log::info("OrderObserver: Devolviendo stock del pedido #{$order->id}");
        
        foreach ($order->items as $item) {
            ProductSize::where('product_id', $item->product_id)
                ->where('size', $item->size)
                ->increment('stock', $item->quantity);
                
            \Log::info("Stock devuelto: Producto {$item->product_id}, Talla {$item->size}, Cantidad {$item->quantity}");
        }
    }
}
