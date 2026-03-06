<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_purchase',
        'max_uses',
        'used_count',
        'starts_at',
        'ends_at',
        'is_active',
        'applies_to',
    ];

    protected $casts = [
        'value'        => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'is_active'    => 'boolean',
        'starts_at'    => 'datetime',
        'ends_at'      => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}