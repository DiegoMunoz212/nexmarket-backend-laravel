<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'total_sales',
        'total_revenue',
        'total_orders',
        'total_views',
        'conversion_rate',
        'avg_rating',
    ];

    protected $casts = [
        'date'            => 'date',
        'total_revenue'   => 'decimal:2',
        'conversion_rate' => 'decimal:2',
        'avg_rating'      => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}