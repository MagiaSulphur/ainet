<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TshirtImage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'category_id',
        'name',
        'description',
        'image_url',
        'custom',
    ];

    protected function casts(): array
    {
        return [
            'custom' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getIsCatalogAttribute()
    {
        return is_null($this->customer_id);
    }
}
