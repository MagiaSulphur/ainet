<?php

use App\Models\Color;
use App\Models\TshirtImage;

test('catalog product page exposes interactive color previews', function () {
    $image = TshirtImage::create([
        'customer_id' => null,
        'category_id' => null,
        'name' => 'Preview design',
        'description' => 'Transparent design',
        'image_url' => 'preview.png',
    ]);

    Color::create(['code' => '112233', 'name' => 'Navy']);
    Color::create(['code' => 'aabbcc', 'name' => 'Silver']);

    $this->get(route('catalog.show', $image))
        ->assertOk()
        ->assertSee('data-tshirt-preview', false)
        ->assertSee('data-tshirt-base', false)
        ->assertSee('storage/tshirt_base/112233.jpg', false)
        ->assertSee('storage/tshirt_base/aabbcc.jpg', false)
        ->assertSee('data-color-option', false)
        ->assertSee('data-size-option', false)
        ->assertSee('data-selected-size-input', false)
        ->assertSee(route('cart.store', $image), false)
        ->assertSee('aria-pressed="true"', false);
});

test('selected color size and quantity can be added to the cart', function () {
    $image = TshirtImage::create([
        'customer_id' => null,
        'category_id' => null,
        'name' => 'Cart design',
        'description' => 'Design ready for purchase',
        'image_url' => 'cart-design.png',
    ]);

    Color::create(['code' => '284d9d', 'name' => 'Blue']);

    $this->post(route('cart.store', $image), [
        'color_code' => '284d9d',
        'size' => 'L',
        'qty' => 2,
    ])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('cart.items.1|284d9d|L', [
            'tshirt_image_id' => $image->id,
            'color_code' => '284d9d',
            'size' => 'L',
            'qty' => 2,
        ]);
});
