<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrderFromCart(array $cart, array $orderData = []): Order
    {
        $items = array_values($cart);
        $total = collect($items)->sum(fn($item) => $item['price'] * $item['quantity']);
        
        return $this->processOrder($items, array_merge($orderData, ['total_amount' => $total]));
    }
    
    public function createOrderFromItems(array $items, array $orderData): Order
    {
        $total = collect($items)->sum(fn($item) => $item['quantity'] * $item['price']);
        
        return $this->processOrder($items, array_merge($orderData, ['total_amount' => $total]));
    }
    
    private function processOrder(array $items, array $orderData): Order
    {
        DB::beginTransaction();
        
        try {
            // 1. validar el stock antes de crear (antes del Observer)
            $this->validateStock($items);
            
            // 2. Crear Order
            $order = Order::create($orderData);
            
            // 3. Crear OrderItems (Observer resta stock automáticamente)
            foreach ($items as $details) {
                $this->createOrderItem($order, $details);
            }
            
            DB::commit();
            return $order;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    
    //  Validar stock antes de crear items
     
    private function validateStock(array $items): void
    {
        foreach ($items as $details) {
            $sizeRecord = ProductSize::where('product_id', $details['product_id'])
                ->where('size', $details['size'])
                ->first();
            
            if (!$sizeRecord || $sizeRecord->stock < $details['quantity']) {
                $productName = Product::find($details['product_id'])?->name ?? 'Producto';
                throw new \Exception(
                    "Sin stock suficiente de {$productName} talla {$details['size']}. " .
                    "Disponible: " . ($sizeRecord?->stock ?? 0) . ", Solicitado: {$details['quantity']}"
                );
            }
        }
    }
    
    
    // Crear OrderItem (Observer maneja stock)
     
    private function createOrderItem(Order $order, array $details): void
    {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $details['product_id'],
            'quantity' => $details['quantity'],
            'price' => $details['price'],
            'size' => $details['size'],
        ]);
        
        // no necesitamos esto - el Observer lo hace
        // $sizeRecord->decrement('stock', $details['quantity']);
    }
}
