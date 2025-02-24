<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'code',
        'name',
        'price',
        'stock',
        'discount'
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
