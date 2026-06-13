<x-layouts::app :title="__('Business statistics')">
    @php
        $money = fn (float|int|string $value) => number_format((float) $value, 2).' EUR';
        $maxPeriodRevenue = max(1, (float) $periods->max('revenue'));
        $maxCategoryRevenue = max(1, (float) $categories->max('revenue'));
        $maxImageRevenue = max(1, (float) $images->max('revenue'));
        $maxCustomerRevenue = max(1, (float) $customers->max('revenue'));
        $maxColorUnits = max(1, (int) $colors->max('units'));
        $maxSizeUnits = max(1, (int) $sizes->max('units'));
    @endphp

    <div class="shop-shell flex flex-col gap-8">
        <header class="statistics-header">
            <div>
                <div class="shop-kicker">{{ __('Administration') }}</div>
                <flux:heading size="xl">{{ __('Business statistics') }}</flux:heading>
                <flux:text>{{ __('Financial and operational performance based on completed orders.') }}</flux:text>
            </div>

            <form method="GET" action="{{ route('admin.statistics.index') }}" class="statistics-filter">
                <flux:input name="from" type="date" :label="__('From')" value="{{ $filters['from'] }}" />
                <flux:input name="to" type="date" :label="__('To')" value="{{ $filters['to'] }}" />
                <flux:select name="group" :label="__('Group by')">
                    <option value="month" @selected($filters['group'] === 'month')>{{ __('Month') }}</option>
                    <option value="year" @selected($filters['group'] === 'year')>{{ __('Year') }}</option>
                </flux:select>
                <div class="statistics-filter-actions">
                    <flux:button type="submit" variant="primary">{{ __('Apply') }}</flux:button>
                    <flux:button :href="route('admin.statistics.index')" variant="filled" wire:navigate>{{ __('Clear') }}</flux:button>
                </div>
            </form>
        </header>

        <section aria-labelledby="summary-heading">
            <div class="statistics-section-heading">
                <div>
                    <flux:heading id="summary-heading" size="lg">{{ __('Executive summary') }}</flux:heading>
                    <flux:text>{{ __('Only closed orders contribute to sales revenue.') }}</flux:text>
                </div>
                @if ($bestPeriod)
                    <div class="statistics-highlight">
                        <span>{{ __('Best period') }}</span>
                        <strong>{{ $bestPeriod['label'] }} · {{ $money($bestPeriod['revenue']) }}</strong>
                    </div>
                @endif
            </div>

            <div class="statistics-kpi-grid">
                <article class="statistics-kpi statistics-kpi-pink">
                    <span>{{ __('Total sales') }}</span>
                    <strong>{{ $money($kpis['revenue']) }}</strong>
                    <small>{{ $kpis['orders'] }} {{ __('closed orders') }}</small>
                </article>
                <article class="statistics-kpi statistics-kpi-green">
                    <span>{{ __('Products sold') }}</span>
                    <strong>{{ number_format($kpis['units']) }}</strong>
                    <small>{{ number_format($kpis['average_units'], 1) }} {{ __('per order') }}</small>
                </article>
                <article class="statistics-kpi statistics-kpi-blue">
                    <span>{{ __('Average order') }}</span>
                    <strong>{{ $money($kpis['average_order']) }}</strong>
                    <small>{{ $kpis['customers'] }} {{ __('active customers') }}</small>
                </article>
                <article class="statistics-kpi statistics-kpi-yellow">
                    <span>{{ __('Order range') }}</span>
                    <strong>{{ $money($kpis['maximum_order']) }}</strong>
                    <small>{{ __('Minimum') }}: {{ $money($kpis['minimum_order']) }}</small>
                </article>
            </div>
        </section>

        <section class="statistics-status-band" aria-labelledby="status-heading">
            <div>
                <flux:heading id="status-heading" size="lg">{{ __('Order status') }}</flux:heading>
                <flux:text>{{ __('Operational volume for the selected period.') }}</flux:text>
            </div>
            <div class="statistics-status-list">
                <div>
                    <span class="statistics-status-dot statistics-status-pending"></span>
                    <span>{{ __('Pending') }}</span>
                    <strong>{{ $statusSummary['pending'] }}</strong>
                </div>
                <div>
                    <span class="statistics-status-dot statistics-status-closed"></span>
                    <span>{{ __('Closed') }}</span>
                    <strong>{{ $statusSummary['closed'] }}</strong>
                </div>
                <div>
                    <span class="statistics-status-dot statistics-status-canceled"></span>
                    <span>{{ __('Canceled') }}</span>
                    <strong>{{ $statusSummary['canceled'] }}</strong>
                </div>
            </div>
        </section>

        <section aria-labelledby="evolution-heading">
            <div class="statistics-section-heading">
                <div>
                    <flux:heading id="evolution-heading" size="lg">{{ __('Sales evolution') }}</flux:heading>
                    <flux:text>{{ __('Revenue, orders and units by selected time interval.') }}</flux:text>
                </div>
            </div>

            <div class="statistics-chart">
                @forelse ($periods as $period)
                    <div class="statistics-chart-row">
                        <div class="statistics-chart-label">{{ $period['label'] }}</div>
                        <div class="statistics-chart-track">
                            <span style="width: {{ max(2, ($period['revenue'] / $maxPeriodRevenue) * 100) }}%"></span>
                        </div>
                        <div class="statistics-chart-value">
                            <strong>{{ $money($period['revenue']) }}</strong>
                            <small>{{ $period['orders'] }} {{ __('orders') }} · {{ $period['units'] }} {{ __('units') }}</small>
                        </div>
                    </div>
                @empty
                    <div class="statistics-empty">{{ __('No completed sales in the selected period.') }}</div>
                @endforelse
            </div>
        </section>

        <div class="statistics-ranking-grid">
            <section aria-labelledby="categories-heading">
                <div class="statistics-section-heading">
                    <flux:heading id="categories-heading" size="lg">{{ __('Top categories') }}</flux:heading>
                </div>
                <div class="statistics-ranking">
                    @forelse ($categories as $position => $category)
                        <div class="statistics-ranking-row">
                            <span class="statistics-rank">{{ $position + 1 }}</span>
                            <div class="statistics-ranking-content">
                                <div>
                                    <strong>{{ $category['label'] }}</strong>
                                    <span>{{ $category['units'] }} {{ __('units') }}</span>
                                </div>
                                <div class="statistics-mini-track">
                                    <span style="width: {{ max(2, ($category['revenue'] / $maxCategoryRevenue) * 100) }}%"></span>
                                </div>
                            </div>
                            <strong>{{ $money($category['revenue']) }}</strong>
                        </div>
                    @empty
                        <div class="statistics-empty">{{ __('No category sales available.') }}</div>
                    @endforelse
                </div>
            </section>

            <section aria-labelledby="images-heading">
                <div class="statistics-section-heading">
                    <flux:heading id="images-heading" size="lg">{{ __('Top designs') }}</flux:heading>
                </div>
                <div class="statistics-ranking">
                    @forelse ($images as $position => $image)
                        <div class="statistics-ranking-row">
                            <span class="statistics-rank">{{ $position + 1 }}</span>
                            <div class="statistics-ranking-content">
                                <div>
                                    <strong>{{ $image['label'] }}</strong>
                                    <span>{{ $image['units'] }} {{ __('units') }}</span>
                                </div>
                                <div class="statistics-mini-track statistics-mini-track-pink">
                                    <span style="width: {{ max(2, ($image['revenue'] / $maxImageRevenue) * 100) }}%"></span>
                                </div>
                            </div>
                            <strong>{{ $money($image['revenue']) }}</strong>
                        </div>
                    @empty
                        <div class="statistics-empty">{{ __('No design sales available.') }}</div>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="statistics-ranking-grid">
            <section aria-labelledby="customers-heading">
                <div class="statistics-section-heading">
                    <flux:heading id="customers-heading" size="lg">{{ __('Top customers') }}</flux:heading>
                </div>
                <div class="statistics-ranking">
                    @forelse ($customers as $position => $customer)
                        <div class="statistics-ranking-row">
                            <span class="statistics-rank">{{ $position + 1 }}</span>
                            <div class="statistics-ranking-content">
                                <div>
                                    <strong>{{ $customer['label'] }}</strong>
                                    <span>{{ $customer['orders'] }} {{ __('orders') }}</span>
                                </div>
                                <div class="statistics-mini-track statistics-mini-track-blue">
                                    <span style="width: {{ max(2, ($customer['revenue'] / $maxCustomerRevenue) * 100) }}%"></span>
                                </div>
                            </div>
                            <strong>{{ $money($customer['revenue']) }}</strong>
                        </div>
                    @empty
                        <div class="statistics-empty">{{ __('No customer sales available.') }}</div>
                    @endforelse
                </div>
            </section>

            <section aria-labelledby="preferences-heading">
                <div class="statistics-section-heading">
                    <flux:heading id="preferences-heading" size="lg">{{ __('Product preferences') }}</flux:heading>
                </div>
                <div class="statistics-preferences">
                    <div>
                        <h3>{{ __('Colors') }}</h3>
                        @forelse ($colors as $color)
                            <div class="statistics-preference-row">
                                <span>{{ $color['label'] }}</span>
                                <div class="statistics-preference-track">
                                    <span style="width: {{ max(3, ($color['units'] / $maxColorUnits) * 100) }}%"></span>
                                </div>
                                <strong>{{ $color['units'] }}</strong>
                            </div>
                        @empty
                            <div class="statistics-empty">{{ __('No color data available.') }}</div>
                        @endforelse
                    </div>
                    <div>
                        <h3>{{ __('Sizes') }}</h3>
                        @forelse ($sizes as $size)
                            <div class="statistics-preference-row">
                                <span>{{ $size['label'] }}</span>
                                <div class="statistics-preference-track statistics-preference-track-pink">
                                    <span style="width: {{ max(3, ($size['units'] / $maxSizeUnits) * 100) }}%"></span>
                                </div>
                                <strong>{{ $size['units'] }}</strong>
                            </div>
                        @empty
                            <div class="statistics-empty">{{ __('No size data available.') }}</div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-layouts::app>
