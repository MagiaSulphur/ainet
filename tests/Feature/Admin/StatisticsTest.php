<?php

use App\Models\Category;
use App\Models\Color;
use App\Models\Customer;
use App\Models\Order;
use App\Models\TshirtImage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

function statisticsAdmin(): User
{
    return User::factory()->create(['user_type' => 'A']);
}

function statisticsCustomer(string $name = 'Customer'): Customer
{
    $user = User::factory()->create([
        'name' => $name,
        'user_type' => 'C',
    ]);

    return Customer::create([
        'id' => $user->id,
        'nif' => '123456789',
        'address' => 'Test address',
        'default_payment_type' => 'Visa',
        'default_payment_ref' => '1234567812345678',
    ]);
}

function statisticsOrder(Customer $customer, string $status, string $date, float $total): Order
{
    return Order::create([
        'status' => $status,
        'customer_id' => $customer->id,
        'date' => $date,
        'total_price' => $total,
        'nif' => '123456789',
        'address' => 'Test address',
        'payment_type' => 'Visa',
        'payment_ref' => '1234567812345678',
    ]);
}

beforeEach(function () {
    $this->category = Category::create(['name' => 'Sports']);
    $this->color = Color::create(['code' => '112233', 'name' => 'Navy']);
    $this->image = TshirtImage::create([
        'customer_id' => null,
        'category_id' => $this->category->id,
        'name' => 'Runner',
        'description' => 'Sports design',
        'image_url' => 'runner.png',
    ]);
    $this->customer = statisticsCustomer('Maria Customer');
});

test('statistics area is exclusive to administrators', function () {
    $this->actingAs($this->customer->user);

    $this->get(route('admin.statistics.index'))->assertForbidden();
});

test('statistics dashboard calculates business indicators from closed orders', function () {
    $january = statisticsOrder($this->customer, 'closed', '2026-01-15', 100);
    $february = statisticsOrder($this->customer, 'closed', '2026-02-10', 50);
    statisticsOrder($this->customer, 'pending', '2026-02-12', 80);
    statisticsOrder($this->customer, 'canceled', '2026-02-14', 40);

    DB::table('order_items')->insert([
        [
            'order_id' => $january->id,
            'tshirt_image_id' => $this->image->id,
            'color_code' => $this->color->code,
            'size' => 'M',
            'qty' => 10,
            'unit_price' => 10,
            'sub_total' => 100,
        ],
        [
            'order_id' => $february->id,
            'tshirt_image_id' => $this->image->id,
            'color_code' => $this->color->code,
            'size' => 'L',
            'qty' => 5,
            'unit_price' => 10,
            'sub_total' => 50,
        ],
    ]);

    $response = $this->actingAs(statisticsAdmin())
        ->get(route('admin.statistics.index'));

    $response
        ->assertOk()
        ->assertSee(__('Business statistics'))
        ->assertSee('150.00 EUR')
        ->assertSee('Maria Customer')
        ->assertSee('Sports')
        ->assertSee('Runner');

    expect($response->viewData('kpis'))->toMatchArray([
        'revenue' => 150.0,
        'units' => 15,
        'orders' => 2,
        'average_order' => 75.0,
        'average_units' => 7.5,
        'customers' => 1,
        'maximum_order' => 100.0,
        'minimum_order' => 50.0,
    ]);

    expect($response->viewData('statusSummary')->all())->toBe([
        'pending' => 1,
        'closed' => 2,
        'canceled' => 1,
    ]);

    expect($response->viewData('periods')->pluck('label')->all())
        ->toBe(['2026-01', '2026-02']);
});

test('statistics can be filtered by date and grouped by year', function () {
    statisticsOrder($this->customer, 'closed', '2025-12-20', 90);
    statisticsOrder($this->customer, 'closed', '2026-03-05', 120);

    $response = $this->actingAs(statisticsAdmin())
        ->get(route('admin.statistics.index', [
            'from' => '2026-01-01',
            'to' => '2026-12-31',
            'group' => 'year',
        ]));

    $response->assertOk();

    expect($response->viewData('kpis')['revenue'])->toBe(120.0)
        ->and($response->viewData('periods')->pluck('label')->all())->toBe(['2026'])
        ->and($response->viewData('filters'))->toBe([
            'from' => '2026-01-01',
            'to' => '2026-12-31',
            'group' => 'year',
        ]);
});

test('statistics reject an invalid date range', function () {
    $this->actingAs(statisticsAdmin())
        ->get(route('admin.statistics.index', [
            'from' => '2026-05-01',
            'to' => '2026-04-01',
        ]))
        ->assertSessionHasErrors('to');
});
