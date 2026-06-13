<?php

use App\Models\Category;
use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function catalogAdmin(): User
{
    return User::factory()->create(['user_type' => 'A']);
}

test('catalog management is exclusive to administrators', function () {
    $customer = User::factory()->create(['user_type' => 'C']);

    $this->actingAs($customer);

    foreach ([
        'admin.catalog-images.index',
        'admin.categories.index',
        'admin.colors.index',
        'admin.prices.edit',
    ] as $route) {
        $this->get(route($route))->assertForbidden();
    }
});

test('administrator can consult every catalog management area', function () {
    $this->actingAs(catalogAdmin());

    foreach ([
        'admin.catalog-images.index',
        'admin.categories.index',
        'admin.colors.index',
        'admin.prices.edit',
    ] as $route) {
        $this->get(route($route))
            ->assertOk()
            ->assertSee(__('T-shirt images'))
            ->assertSee(__('Categories'))
            ->assertSee(__('Colors'))
            ->assertSee(__('Prices'));
    }
});

test('creating and updating a catalog image requires an uploaded file', function () {
    Storage::fake('public');
    $this->actingAs(catalogAdmin());
    $category = Category::create(['name' => 'Sports']);

    $this->post(route('admin.catalog-images.store'), [
        'name' => 'Runner',
        'category_id' => $category->id,
    ])->assertSessionHasErrors('image');

    $this->post(route('admin.catalog-images.store'), [
        'name' => 'Runner',
        'description' => 'Public catalog design',
        'category_id' => $category->id,
        'image' => UploadedFile::fake()->image('runner.png'),
    ])->assertRedirect(route('admin.catalog-images.index'));

    $image = TshirtImage::query()->where('name', 'Runner')->firstOrFail();
    Storage::disk('public')->assertExists('tshirt_images/'.$image->image_url);
    $oldFile = $image->image_url;

    $this->put(route('admin.catalog-images.update', $image), [
        'name' => 'Runner updated',
        'category_id' => $category->id,
    ])->assertSessionHasErrors('image');

    $this->put(route('admin.catalog-images.update', $image), [
        'name' => 'Runner updated',
        'description' => 'New uploaded design',
        'category_id' => $category->id,
        'image' => UploadedFile::fake()->image('runner-new.webp'),
    ])->assertRedirect(route('admin.catalog-images.index'));

    $image->refresh();
    expect($image->name)->toBe('Runner updated')
        ->and($image->image_url)->not->toBe($oldFile);
    Storage::disk('public')->assertMissing('tshirt_images/'.$oldFile);
    Storage::disk('public')->assertExists('tshirt_images/'.$image->image_url);

    $this->delete(route('admin.catalog-images.destroy', $image))
        ->assertRedirect(route('admin.catalog-images.index'));
    $this->assertSoftDeleted($image);
});

test('administrator can create update and remove categories with uploaded images', function () {
    Storage::fake('public');
    $this->actingAs(catalogAdmin());

    $this->post(route('admin.categories.store'), [
        'name' => 'Music',
        'image' => UploadedFile::fake()->image('music.jpg'),
    ])->assertRedirect(route('admin.categories.index'));

    $category = Category::query()->where('name', 'Music')->firstOrFail();
    Storage::disk('public')->assertExists('categories/'.$category->image_url);
    $oldFile = $category->image_url;

    $this->put(route('admin.categories.update', $category), [
        'name' => 'Live music',
        'image' => UploadedFile::fake()->image('live-music.png'),
    ])->assertRedirect(route('admin.categories.index'));

    $category->refresh();
    Storage::disk('public')->assertMissing('categories/'.$oldFile);
    Storage::disk('public')->assertExists('categories/'.$category->image_url);

    $this->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'));
    $this->assertSoftDeleted($category);
});

test('a new color requires a base t-shirt image and administrators can replace it', function () {
    Storage::fake('public');
    $this->actingAs(catalogAdmin());

    $this->post(route('admin.colors.store'), [
        'code' => 'A1B2C3',
        'name' => 'Ocean',
    ])->assertSessionHasErrors('base_image');

    $this->post(route('admin.colors.store'), [
        'code' => 'A1B2C3',
        'name' => 'Ocean',
        'base_image' => UploadedFile::fake()->image('ocean.jpg'),
    ])->assertRedirect(route('admin.colors.index'));

    $color = Color::query()->findOrFail('a1b2c3');
    Storage::disk('public')->assertExists('tshirt_base/a1b2c3.jpg');

    $this->put(route('admin.colors.update', $color), [
        'name' => 'Deep ocean',
        'base_image' => UploadedFile::fake()->image('deep-ocean.jpg'),
    ])->assertRedirect(route('admin.colors.index'));

    expect($color->refresh()->name)->toBe('Deep ocean');
    Storage::disk('public')->assertExists('tshirt_base/a1b2c3.jpg');

    $this->delete(route('admin.colors.destroy', $color))
        ->assertRedirect(route('admin.colors.index'));
    $this->assertSoftDeleted($color);
});

test('administrator can configure catalog prices with valid discounts', function () {
    $this->actingAs(catalogAdmin());

    $this->put(route('admin.prices.update'), [
        'unit_price_catalog' => 20,
        'unit_price_catalog_discount' => 15,
        'unit_price_own' => 30,
        'unit_price_own_discount' => 25,
        'qty_discount' => 5,
    ])->assertRedirect(route('admin.prices.edit'));

    $price = Price::query()->firstOrFail();
    expect((float) $price->unit_price_catalog)->toBe(20.0)
        ->and((float) $price->unit_price_catalog_discount)->toBe(15.0)
        ->and((float) $price->unit_price_own)->toBe(30.0)
        ->and((float) $price->unit_price_own_discount)->toBe(25.0)
        ->and($price->qty_discount)->toBe(5);

    $this->put(route('admin.prices.update'), [
        'unit_price_catalog' => 20,
        'unit_price_catalog_discount' => 21,
        'unit_price_own' => 30,
        'unit_price_own_discount' => 31,
        'qty_discount' => 5,
    ])->assertSessionHasErrors([
        'unit_price_catalog_discount',
        'unit_price_own_discount',
    ]);
});
