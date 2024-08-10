<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class location extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "area",
        "street",
        "building"
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
