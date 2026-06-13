<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'group' => ['nullable', 'in:month,year'],
        ]);

        $group = $validated['group'] ?? 'month';

        $orders = Order::query()
            ->with([
                'customer.user',
                'items.color',
                'items.tshirtImage.category',
            ])
            ->when($validated['from'] ?? null, fn ($query, $from) => $query->whereDate('date', '>=', $from))
            ->when($validated['to'] ?? null, fn ($query, $to) => $query->whereDate('date', '<=', $to))
            ->orderBy('date')
            ->get();

        $closedOrders = $orders->where('status', 'closed')->values();
        $closedItems = $closedOrders->flatMap->items;
        $periods = $this->periods($closedOrders, $group);

        $totalRevenue = (float) $closedOrders->sum('total_price');
        $totalUnits = (int) $closedItems->sum('qty');
        $closedCount = $closedOrders->count();

        return view('admin.statistics.index', [
            'filters' => [
                'from' => $validated['from'] ?? null,
                'to' => $validated['to'] ?? null,
                'group' => $group,
            ],
            'kpis' => [
                'revenue' => $totalRevenue,
                'units' => $totalUnits,
                'orders' => $closedCount,
                'average_order' => $closedCount ? $totalRevenue / $closedCount : 0,
                'average_units' => $closedCount ? $totalUnits / $closedCount : 0,
                'customers' => $closedOrders->pluck('customer_id')->unique()->count(),
                'maximum_order' => (float) ($closedOrders->max('total_price') ?? 0),
                'minimum_order' => (float) ($closedOrders->min('total_price') ?? 0),
            ],
            'statusSummary' => collect(['pending', 'closed', 'canceled'])->mapWithKeys(
                fn (string $status) => [$status => $orders->where('status', $status)->count()]
            ),
            'periods' => $periods,
            'bestPeriod' => $periods->sortByDesc('revenue')->first(),
            'categories' => $this->rankItems($closedItems, fn ($item) => [
                $item->tshirtImage?->category?->name ?? __('Custom / without category'),
                $item->sub_total,
                $item->qty,
            ]),
            'images' => $this->rankItems($closedItems, fn ($item) => [
                $item->tshirtImage?->name ?? __('Deleted image'),
                $item->sub_total,
                $item->qty,
            ]),
            'customers' => $this->rankOrders($closedOrders, fn (Order $order) => [
                $order->customer?->user?->name ?? __('Deleted customer'),
                $order->total_price,
            ]),
            'colors' => $this->rankItems($closedItems, fn ($item) => [
                $item->color?->name ?? $item->color_code,
                $item->sub_total,
                $item->qty,
            ]),
            'sizes' => $this->rankItems($closedItems, fn ($item) => [
                $item->size,
                $item->sub_total,
                $item->qty,
            ], 5),
        ]);
    }

    private function periods(Collection $orders, string $group): Collection
    {
        return $orders
            ->groupBy(fn (Order $order) => $group === 'year'
                ? $order->date->format('Y')
                : $order->date->format('Y-m'))
            ->map(function (Collection $periodOrders, string $label): array {
                $revenue = (float) $periodOrders->sum('total_price');
                $items = $periodOrders->flatMap->items;

                return [
                    'label' => $label,
                    'revenue' => $revenue,
                    'orders' => $periodOrders->count(),
                    'units' => (int) $items->sum('qty'),
                    'average' => $periodOrders->isEmpty() ? 0 : $revenue / $periodOrders->count(),
                ];
            })
            ->values();
    }

    private function rankItems(Collection $items, callable $extract, int $limit = 8): Collection
    {
        return $items
            ->map($extract)
            ->groupBy(fn (array $row) => $row[0])
            ->map(fn (Collection $rows, string $label) => [
                'label' => $label,
                'revenue' => (float) $rows->sum(fn (array $row) => $row[1]),
                'units' => (int) $rows->sum(fn (array $row) => $row[2]),
            ])
            ->sortByDesc('revenue')
            ->take($limit)
            ->values();
    }

    private function rankOrders(Collection $orders, callable $extract, int $limit = 8): Collection
    {
        return $orders
            ->map($extract)
            ->groupBy(fn (array $row) => $row[0])
            ->map(fn (Collection $rows, string $label) => [
                'label' => $label,
                'revenue' => (float) $rows->sum(fn (array $row) => $row[1]),
                'orders' => $rows->count(),
            ])
            ->sortByDesc('revenue')
            ->take($limit)
            ->values();
    }
}
