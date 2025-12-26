<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Calcular total 
        $data['total_amount'] = $this->record->items->sum(fn($item) => 
            $item->quantity * $item->price
        );
        return $data;
    }

    protected function afterSave(): void
    {
        //REcalcular y guardar total después de editar
        $total = $this->record->items->sum(fn($item) => 
            $item->quantity * $item->price
        );
        
        $this->record->update(['total_amount' => $total]);
    }
}
