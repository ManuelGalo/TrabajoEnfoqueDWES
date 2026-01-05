<?php

namespace App\Observers;

use App\Models\OrderItem;
use App\Models\ProductSize;

class OrderItemObserver
{
    // REstar stock cuadno se crea un nuevo create

    public function created(OrderItem $item)
    {
        \Log::info("OrderItemObserver: Restando stock al crear item #{$item->id}");
        
        $sizeRecord = ProductSize::where('product_id', $item->product_id)
            ->where('size', $item->size)
            ->first();

        if (!$sizeRecord || $sizeRecord->stock < $item->quantity) {
            throw new \Exception("Stock insuficiente para {$item->product->name} talla {$item->size}");
        }

        $sizeRecord->decrement('stock', $item->quantity);
        \Log::info("Stock restado: {$item->quantity} unidades de producto {$item->product_id} talla {$item->size}");
    }
    // Devolver stock cuando se elimina un item del pedido
     
    public function deleting(OrderItem $item)
    {
        \Log::info("OrderItemObserver: Devolviendo stock del item #{$item->id}");
        
        ProductSize::where('product_id', $item->product_id)
            ->where('size', $item->size)
            ->increment('stock', $item->quantity);
            
        \Log::info("Stock devuelto: Producto {$item->product_id}, Talla {$item->size}, Cantidad {$item->quantity}");
    }

    
    // Ajustar stock cuando se actualiza cantidad o talla
     
    public function updating(OrderItem $item)
    {
        // Si cambió la cantidaa
        if ($item->isDirty('quantity')) {
            $oldQuantity = $item->getOriginal('quantity');
            $newQuantity = $item->quantity;
            $difference = $newQuantity - $oldQuantity;

            \Log::info("OrderItemObserver: Cantidad cambió de {$oldQuantity} a {$newQuantity}");

            $sizeRecord = ProductSize::where('product_id', $item->product_id)
                ->where('size', $item->size)
                ->first();

            if ($difference > 0) {
                // Aumentó cantidad → restar más stock
                if (!$sizeRecord || $sizeRecord->stock < $difference) {
                    throw new \Exception("Stock insuficiente. Disponible: " . ($sizeRecord?->stock ?? 0));
                }
                $sizeRecord->decrement('stock', $difference);
                \Log::info("Stock restado: {$difference} unidades");
            } else {
                // Disminuyó cantidad → devolver stock
                $sizeRecord->increment('stock', abs($difference));
                \Log::info("Stock devuelto: " . abs($difference) . " unidades");
            }
        }

        // Si cambió la talla
        if ($item->isDirty('size')) {
            $oldSize = $item->getOriginal('size');
            $newSize = $item->size;
            
            \Log::info("OrderItemObserver: Talla cambió de {$oldSize} a {$newSize}");

            // Devolver stock a talla antigua
            ProductSize::where('product_id', $item->product_id)
                ->where('size', $oldSize)
                ->increment('stock', $item->quantity);

            // Restar stock de talla nueva
            $newSizeRecord = ProductSize::where('product_id', $item->product_id)
                ->where('size', $newSize)
                ->first();

            if (!$newSizeRecord || $newSizeRecord->stock < $item->quantity) {
                throw new \Exception("Stock insuficiente para talla {$newSize}. Disponible: " . ($newSizeRecord?->stock ?? 0));
            }
            
            $newSizeRecord->decrement('stock', $item->quantity);
            \Log::info("Stock ajustado: Devuelto {$item->quantity} a talla {$oldSize}, Restado {$item->quantity} de talla {$newSize}");
        }
    }
}
