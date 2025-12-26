<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Product;
use App\Models\ProductSize;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Poner total temporal en 0 (se actualiza después)
        $data['total_amount'] = 0;
        
        return $data;
    }

    protected function afterCreate(): void
    {
        DB::transaction(function () {
            $total = 0;
            
            // Recorrer items YA CREADOS por Filament
            foreach ($this->record->items as $orderItem) {
                // Verificar stock
                $sizeRecord = ProductSize::where('product_id', $orderItem->product_id)
                    ->where('size', $orderItem->size)
                    ->first();

                if (!$sizeRecord || $sizeRecord->stock < $orderItem->quantity) {
                    // Eliminar pedido si no hay stock
                    $this->record->delete();
                    throw new \Exception("Sin stock de {$orderItem->product->name} talla {$orderItem->size}");
                }

                // Restar stock
                $sizeRecord->decrement('stock', $orderItem->quantity);
                
                // Sumar al total
                $total += $orderItem->quantity * $orderItem->price;
            }
            
            // ACTUALIZAR total_amount del pedido
            $this->record->update(['total_amount' => $total]);
        });
    }
}
