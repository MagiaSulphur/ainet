<?php

use App\Models\Category;
use App\Models\Color;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TshirtImage;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

it('defines the requested model relationships', function (
    string $modelClass,
    string $relationship,
    string $relationClass,
    string $relatedModelClass,
) {
    $relation = (new $modelClass)->{$relationship}();

    expect($relation)->toBeInstanceOf($relationClass)
        ->and($relation->getRelated())->toBeInstanceOf($relatedModelClass);
})->with([
    [Customer::class, 'user', BelongsTo::class, User::class],
    [Customer::class, 'orders', HasMany::class, Order::class],
    [Category::class, 'tshirtImages', HasMany::class, TshirtImage::class],
    [TshirtImage::class, 'category', BelongsTo::class, Category::class],
    [TshirtImage::class, 'customer', BelongsTo::class, Customer::class],
    [Order::class, 'customer', BelongsTo::class, Customer::class],
    [Order::class, 'items', HasMany::class, OrderItem::class],
    [OrderItem::class, 'order', BelongsTo::class, Order::class],
    [OrderItem::class, 'tshirtImage', BelongsTo::class, TshirtImage::class],
    [OrderItem::class, 'color', BelongsTo::class, Color::class],
]);
