<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 flex w-[248px] -translate-x-full flex-col border-r border-neutral-200 bg-white transition-transform duration-200 ease-out peer-checked/sidebar-toggle:translate-x-0 min-[768px]:translate-x-0"
    aria-label="Navigasi utama">
    <a href="{{ route('dashboard') }}"
        class="flex shrink-0 items-center gap-[11px] border-b border-neutral-200 px-5 h-[68px] no-underline">
        <img src="{{ asset('images/meditech-logo.jpg') }}" alt="Logo Meditech Cipta Inovasi"
            class="h-[40px] w-[62px] shrink-0 rounded-md object-contain">
        <span class="min-w-0">
            <span class="block font-heading text-[20px] leading-7 font-bold tracking-[-0.5px] text-neutral-900"><span
                    class="text-secondary-500">Medi</span><span class="text-primary-500">Tech</span></span>
            <span class="mt-[2px] block text-[11px] font-bold text-neutral-500">Business System</span>
        </span>
    </a>

    @php
        $navigationGroups = [
            [
                'label' => 'Workspace',
                'items' => [
                    [
                        'label' => 'Dashboard',
                        'icon' => 'i-dashboard',
                        'permission' => 'dashboard.read',
                        'route' => 'dashboard',
                        'fallback' => '/dashboard',
                        'active' => ['dashboard'],
                    ],
                ],
            ],
            [
                'label' => 'Sales & finance',
                'items' => [
                    [
                        'label' => 'Quotation',
                        'icon' => 'i-quotation',
                        'permission' => 'quotations.read',
                        'route' => 'quotations.index',
                        'fallback' => '/quotations',
                        'active' => ['quotations.*'],
                        'count' => $pendingQuotationsCount ?? null,
                    ],
                    [
                        'label' => 'Sales Order',
                        'icon' => 'i-order',
                        'permission' => 'sales_orders.read',
                        'route' => 'sales-orders.index',
                        'fallback' => '/sales-orders',
                        'active' => ['sales-orders.*'],
                    ],
                    [
                        'label' => 'Invoice',
                        'icon' => 'i-invoice',
                        'permission' => 'invoices.read',
                        'route' => 'invoices.index',
                        'fallback' => '/invoices',
                        'active' => ['invoices.*'],
                    ],
                    [
                        'label' => 'Payment',
                        'icon' => 'i-payment',
                        'permission' => 'payments.read',
                        'route' => 'payments.index',
                        'fallback' => '/payments',
                        'active' => ['payments.*'],
                    ],
                    [
                        'label' => 'Cash Flow',
                        'icon' => 'i-swap',
                        'permission' => 'cash_flow.read',
                        'route' => 'cash-flow.index',
                        'fallback' => '/cash-flow',
                        'active' => ['cash-flow.*'],
                    ],
                    [
                        'label' => 'Petty Cash',
                        'icon' => 'i-payment',
                        'permission' => 'petty_cash.read',
                        'route' => 'petty-cash.index',
                        'fallback' => '/petty-cash',
                        'active' => ['petty-cash.*'],
                    ],
                    [
                        'label' => 'Profit',
                        'icon' => 'i-trend',
                        'permission' => 'profit.read',
                        'route' => 'profit.index',
                        'fallback' => '/profit',
                        'active' => ['profit.*'],
                    ],
                    [
                        'label' => 'Aturan Komisi',
                        'icon' => 'i-users',
                        'permission' => 'commissions.read',
                        'route' => 'commissions.index',
                        'fallback' => '/commissions',
                        'active' => ['commissions.*'],
                    ],
                ],
            ],
            [
                'label' => 'Operations',
                'items' => [
                    [
                        'label' => 'Inventory',
                        'icon' => 'i-inventory',
                        'permission' => 'inventory.read',
                        'route' => 'inventory.index',
                        'fallback' => '/inventory',
                        'active' => ['inventory.*'],
                    ],
                    [
                        'label' => 'Master Data',
                        'icon' => 'i-database',
                        'permission' => 'master_data.read',
                        'route' => 'master-data.index',
                        'fallback' => '/master-data',
                        'active' => ['master-data.*'],
                    ],
                ],
            ],
            [
                'label' => 'Administration',
                'items' => [
                    [
                        'label' => 'User Management',
                        'icon' => 'i-users',
                        'permission' => 'users.read',
                        'route' => 'admin.users.index',
                        'fallback' => '/admin/users',
                        'active' => ['admin.users.*'],
                    ],
                    [
                        'label' => 'Manajemen Role',
                        'icon' => 'i-users',
                        'permission' => 'role_management.read',
                        'route' => 'admin.roles.index',
                        'fallback' => '/admin/roles',
                        'active' => ['admin.roles.*'],
                    ],
                    [
                        'label' => 'CMS Content',
                        'icon' => 'i-cms',
                        'permission' => 'cms.read',
                        'route' => 'cms.pages.index',
                        'fallback' => '/cms',
                        'active' => ['cms.*'],
                    ],
                    [
                        'label' => 'Audit Log',
                        'icon' => 'i-audit',
                        'permission' => 'audit.read',
                        'route' => 'admin.audit-logs.index',
                        'fallback' => '/admin/audit-logs',
                        'active' => ['admin.audit-logs.*'],
                    ],
                ],
            ],
        ];
    @endphp

    <nav class="flex-1 overflow-y-auto px-3 py-3 [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-[#d4d4d4]"
        aria-label="Menu aplikasi">
        @foreach ($navigationGroups as $group)
            @php
                $visibleItems = collect($group['items'])->filter(
                    fn($item) => auth()->user()?->can($item['permission']),
                );
            @endphp
            @if ($visibleItems->isNotEmpty())
                <div class="px-[11px] pb-2 pt-[14px] text-[11px] font-bold text-[#a1a1a1]">{{ $group['label'] }}</div>
                @foreach ($visibleItems as $item)
                    @php
                        $fallbackPath = trim($item['fallback'], '/');
                        $isActive =
                            collect($item['active'])->contains(fn($pattern) => request()->routeIs($pattern)) ||
                            request()->is($fallbackPath) ||
                            request()->is($fallbackPath . '/*');
                        $href = \Illuminate\Support\Facades\Route::has($item['route'])
                            ? route($item['route'])
                            : url($item['fallback']);
                    @endphp
                    <a href="{{ $href }}" {{ $isActive ? 'aria-current=page' : '' }}
                        class="relative mb-[2px] flex items-center gap-3 rounded-lg px-[11px] py-[9px] text-[13px] transition-colors duration-150 {{ $isActive ? 'bg-[#e7e9f6] font-bold text-[#0d21a1] before:absolute before:-left-3 before:top-[9px] before:bottom-[9px] before:w-[3px] before:rounded-r before:bg-[#eec643]' : 'font-medium text-[#525252] hover:bg-[#f5f6fb] hover:text-[#0d21a1]' }}">
                        <span class="grid h-5 w-5 shrink-0 place-items-center"><svg
                                class="h-[19px] w-[19px] fill-none stroke-current [stroke-width:1.7] [stroke-linecap:round] [stroke-linejoin:round]">
                                <use href="#{{ $item['icon'] }}"></use>
                            </svg></span>
                        <span class="min-w-0 flex-1 truncate">{{ $item['label'] }}</span>
                        @if (!empty($item['count']))
                            <span
                                class="rounded-[5px] bg-[#eec643] px-[6px] py-[2px] text-[10px] font-extrabold text-[#262626]">{{ $item['count'] }}</span>
                        @endif
                    </a>
                @endforeach
            @endif
        @endforeach
    </nav>

    <div class="shrink-0 border-t border-[#f0f1f5] p-[14px]">
        <div class="flex items-center gap-[10px] rounded-lg p-2">
            <span
                class="grid h-[34px] w-[34px] shrink-0 place-items-center rounded-full bg-[#e7e9f6] text-[12px] font-extrabold text-[#0d21a1]">{{ strtoupper(collect(explode(' ', trim(auth()->user()->name ?? 'User')))->filter()->take(2)->map(fn($part) => mb_substr($part, 0, 1))->implode('')) }}</span>
            <span class="min-w-0 flex-1"><span
                    class="block truncate text-[12px] font-bold text-[#262626]">{{ auth()->user()->name ?? 'Pengguna' }}</span><span
                    class="mt-[2px] block truncate text-[11px] text-[#737373]">{{ auth()->user()?->roles?->pluck('role_name')->join(', ') ?: 'Pengguna' }}</span></span>
            <svg class="h-4 w-4 shrink-0 fill-none stroke-current text-[#a1a1a1] [stroke-width:1.7]" aria-hidden="true">
                <use href="#i-chev"></use>
            </svg>
        </div>
    </div>
</aside>
