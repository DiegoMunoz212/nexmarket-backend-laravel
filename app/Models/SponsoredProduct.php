<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponsoredProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'starts_at',
        'ends_at',
        'budget',
        'spent',
        'position',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'budget'    => 'decimal:2',
        'spent'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}