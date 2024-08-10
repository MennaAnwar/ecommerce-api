<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_items extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity',
    ];

    public function order() {
        return $this->belongsTo(orders::class, 'order_id');
    }

    public function product() {
        return $this->belongsTo(products::class, 'product_id');
    }

    public function items() {
        return $this->hasMany(order_items::class);
    }
}
