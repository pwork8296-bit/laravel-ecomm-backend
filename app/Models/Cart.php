<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'products',
        'product_id',
        'variant',
        'quantity',
        'price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'product_id' => 'integer',
            'quantity' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    /**
     * Get the user who owns this cart item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the product in this cart item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the orders associated with this cart.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'cart_id', 'id');
    }
}
