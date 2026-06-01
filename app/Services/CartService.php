<?php

namespace App\Services;

use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'cart.items';

    /**
     * @return array<string, array{tshirt_image_id:int,color_code:string,size:string,qty:int}>
     */
    public function raw(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function add(int $tshirtImageId, string $colorCode, string $size, int $qty): void
    {
        $items = $this->raw();
        $key = $this->key($tshirtImageId, $colorCode, $size);
        $currentQty = $items[$key]['qty'] ?? 0;

        $items[$key] = [
            'tshirt_image_id' => $tshirtImageId,
            'color_code' => $colorCode,
            'size' => $size,
            'qty' => $currentQty + $qty,
        ];

        session([self::SESSION_KEY => $items]);
    }

    public function update(string $key, string $colorCode, string $size, int $qty): void
    {
        $items = $this->raw();

        if (! isset($items[$key])) {
            return;
        }

        $item = $items[$key];
        unset($items[$key]);

        if ($qty > 0) {
            $newKey = $this->key($item['tshirt_image_id'], $colorCode, $size);
            $items[$newKey] = [
                'tshirt_image_id' => $item['tshirt_image_id'],
                'color_code' => $colorCode,
                'size' => $size,
                'qty' => $qty,
            ];
        }

        session([self::SESSION_KEY => $items]);
    }

    public function remove(string $key): void
    {
        $items = $this->raw();
        unset($items[$key]);
        session([self::SESSION_KEY => $items]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function summary(): array
    {
        $rawItems = $this->raw();
        $images = TshirtImage::query()
            ->with('category')
            ->whereIn('id', collect($rawItems)->pluck('tshirt_image_id')->unique())
            ->get()
            ->keyBy('id');

        $price = Price::query()->firstOrFail();

        $items = collect($rawItems)
            ->map(function (array $item, string $key) use ($images, $price) {
                $image = $images->get($item['tshirt_image_id']);

                if (! $image) {
                    return null;
                }

                $unitPrice = $this->unitPrice($image, $item['qty'], $price);

                return [
                    'key' => $key,
                    'image' => $image,
                    'color_code' => $item['color_code'],
                    'size' => $item['size'],
                    'qty' => $item['qty'],
                    'unit_price' => $unitPrice,
                    'sub_total' => round($unitPrice * $item['qty'], 2),
                    'discounted' => $this->isDiscounted($item['qty'], $price),
                ];
            })
            ->filter()
            ->values();

        return [
            'items' => $items,
            'total' => round($items->sum('sub_total'), 2),
            'count' => $items->sum('qty'),
        ];
    }

    public function isEmpty(): bool
    {
        return count($this->raw()) === 0;
    }

    private function key(int $tshirtImageId, string $colorCode, string $size): string
    {
        return implode('|', [$tshirtImageId, $colorCode, $size]);
    }

    private function unitPrice(TshirtImage $image, int $qty, Price $price): float
    {
        $own = $image->customer_id !== null;
        $discounted = $this->isDiscounted($qty, $price);

        return (float) match (true) {
            $own && $discounted => $price->unit_price_own_discount,
            $own => $price->unit_price_own,
            $discounted => $price->unit_price_catalog_discount,
            default => $price->unit_price_catalog,
        };
    }

    private function isDiscounted(int $qty, Price $price): bool
    {
        return $price->qty_discount && $qty >= $price->qty_discount;
    }
}
