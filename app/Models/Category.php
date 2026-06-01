<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'image_url',
        'custom',
    ];

    protected function casts(): array
    {
        return [
            'custom' => 'array',
        ];
    }

    public function tshirtImages(): HasMany
    {
        return $this->hasMany(TshirtImage::class);
    }
}
