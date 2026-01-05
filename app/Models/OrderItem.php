<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'size'
    ];

    public function order() 
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    { 
        return $this->belongsTo(Product::class); 
    }
    public function size(){
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }

}
