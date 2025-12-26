<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    // ← MÉTODO PÚBLICO para CARRITO (Web)
    public function createOrderFromCart(array $cart, array $orderData = []): Order
    {
        $items = array_values($cart); // Convertir keys a array simple
        $total = collect($items)->sum(fn($item) => $item['price'] * $item['quantity']);
        
        return $this->processOrder($items, array_merge($orderData, ['total_amount' => $total]));
    }
    
    // ← MÉTODO PÚBLICO para FILAMENT (Admin) ← ESTE FALTABA
    public function createOrderFromItems(array $items, array $orderData): Order
    {
        $total = collect($items)->sum(fn($item) => $item['quantity'] * $item['price']);
        
        return $this->processOrder($items, array_merge($orderData, ['total_amount' => $total]));
    }
    
    // ← MÉTODO PRIVADO reutilizable
    private function processOrder(array $items, array $orderData): Order
    {
        DB::beginTransaction();
        
        try {
            $order = Order::create($orderData);
            
            foreach ($items as $details) {
                $this->processItem($order, $details);
            }
            
            DB::commit();
            return $order;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    private function processItem(Order $order, array $details): void
    {
        // Verificar stock
        $sizeRecord = ProductSize::where('product_id', $details['product_id'])
            ->where('size', $details['size'])->first();
            
        if (!$sizeRecord || $sizeRecord->stock < $details['quantity']) {
            $productName = Product::find($details['product_id'])?->name ?? 'Producto';
            throw new \Exception("Sin stock de {$productName} talla {$details['size']}");
        }
        
        // Crear OrderItem
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $details['product_id'],
            'quantity' => $details['quantity'],
            'price' => $details['price'],
            'size' => $details['size'],
        ]);
        
        // Restar stock
        $sizeRecord->decrement('stock', $details['quantity']);
    }
}
