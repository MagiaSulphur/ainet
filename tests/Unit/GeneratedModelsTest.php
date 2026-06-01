<?php

use App\Models\Category;
use App\Models\Color;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Database\Eloquent\Model;

it('creates the requested Eloquent models', function (string $modelClass) {
    expect(class_exists($modelClass))->toBeTrue()
        ->and(new $modelClass)->toBeInstanceOf(Model::class);
})->with([
    Customer::class,
    Category::class,
    Color::class,
    Price::class,
    TshirtImage::class,
    Order::class,
    OrderItem::class,
]);

it('defines the expected fillable attributes on the generated models', function (string $modelClass, array $fillable) {
    expect((new $modelClass)->getFillable())->toBe($fillable);
})->with([
    [Customer::class, ['id', 'nif', 'address', 'default_payment_type', 'default_payment_ref', 'custom']],
    [Category::class, ['name', 'image_url', 'custom']],
    [Color::class, ['code', 'name', 'custom']],
    [Price::class, ['unit_price_catalog', 'unit_price_own', 'unit_price_catalog_discount', 'unit_price_own_discount', 'qty_discount', 'custom']],
    [TshirtImage::class, ['customer_id', 'category_id', 'name', 'description', 'image_url', 'custom']],
    [Order::class, ['status', 'customer_id', 'date', 'total_price', 'notes', 'reason_for_cancellation', 'nif', 'address', 'payment_type', 'payment_ref', 'receipt_url', 'custom']],
    [OrderItem::class, ['order_id', 'tshirt_image_id', 'color_code', 'size', 'qty', 'unit_price', 'sub_total', 'custom']],
]);
