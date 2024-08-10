<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;

    protected $fillable = [
    'category_id',
    'name',
    'price',
    'stock',
    'image',
    ];

    public function category() {
        return $this->belongsTo(categories::class, 'category_id');
    }

    public function brand() {
        return $this->belongsTo(Brands::class, 'brand_id');
    }
}
