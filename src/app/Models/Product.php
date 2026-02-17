<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'category_id',
        'image_path',
        'description'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function scopeApplyFilters($query, array $filters): Builder
    {
        return $query
            ->when($filters['name'] ?? null, fn ($q, $name) =>
                $q->where('name', 'like', "%{$name}%")
            )
            ->when($filters['category_id'] ?? null, fn ($q, $categoryId) =>
                $q->where('category_id', $categoryId)
            )
            ->when($filters['min_price'] ?? null, fn ($q, $min) =>
                $q->where('price', '>=', $min)
            )
            ->when($filters['max_price'] ?? null, fn ($q, $max) =>
                $q->where('price', '<=', $max)
            );
    }
}
