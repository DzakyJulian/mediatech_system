@extends('admin.layouts.layout')

@section('title', 'Cash Flow')
@section('page_title', 'Cash Flow')
@section('page_breadcrumb', 'Finance / Cash Flow')

@section('content')
    <div class="mb-6">
        <div class="mb-[7px] text-[12px] font-bold text-[#0d21a1]">Finance workspace</div>
        <h1 class="m-0 text-[31px] font-semibold leading-tight tracking-[-0.8px] text-[#171717] sm:text-[31px]">Cash Flow</h1>
        <p class="mt-[6px] max-w-2xl text-[14px] leading-relaxed text-[#737373]">Pencatatan sederhana kas masuk dan keluar — bukan sistem akuntansi penuh.</p>
    </div>

    <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
        <div class="rounded-xl border border-neutral-200 bg-white p-[18px] shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
            <div class="text-[11px] font-semibold text-neutral-500">Cash in tervalidasi</div>
            <div class="mt-3 text-[31px] font-semibold tracking-[-0.7px] text-success-500">{{ number_format($summary['cash_in'], 2, ',', '.') }}</div>
            <div class="mt-1 text-[10px] text-neutral-500">Bulan ini</div>
        </div>
        <div class="rounded-xl border border-neutral-200 bg-white p-[18px] shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
            <div class="text-[11px] font-semibold text-neutral-500">Cash out</div>
            <div class="mt-3 text-[31px] font-semibold tracking-[-0.7px] text-neutral-700">{{ number_format($summary['cash_out'], 2, ',', '.') }}</div>
            <div class="mt-1 text-[10px] text-neutral-500">Bulan ini</div>
        </div>
        <div class="rounded-xl border border-neutral-200 bg-white p-[18px] shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
            <div class="text-[11px] font-semibold text-neutral-500">Net cash flow</div>
            <div class="mt-3 text-[31px] font-semibold tracking-[-0.7px] {{ $summary['net'] >= 0 ? 'text-success-500' : 'text-error-500' }}">{{ number_format($summary['net'], 2, ',', '.') }}</div>
            <div class="mt-1 text-[10px] text-neutral-500">Kas masuk − kas keluar</div>
        </div>
    </div>

    <section class="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
        <form id="cash-filter-form" method="GET" action="{{ route('cash-flow.index') }}"
            class="flex flex-col gap-2 border-b border-neutral-100 p-[14px] sm:flex-row sm:flex-wrap sm:items-center sm:p-[16px]">
            <label class="relative block w-full sm:w-[230px]"><span class="sr-only">Cari transaksi</span><svg
                    class="absolute left-[11px] top-1/2 h-4 w-4 -translate-y-1/2 fill-none stroke-current text-neutral-400"
                    aria-hidden="true">
                    <use href="#i-search"></use>
                </svg><input id="cash-search" type="search" name="search" value="{{ request('search') }}"
                    placeholder="Cari no. transaksi / kategori / keterangan…"
                    class="h-[36px] w-full rounded-lg border border-neutral-200 bg-white pl-[34px] pr-3 text-[12px] outline-none focus:border-primary-300 focus:ring-2 focus:ring-primary-50"></label>

            <select id="cash-month-filter" name="month" aria-label="Filter bulan"
                class="h-[36px] w-full rounded-lg border border-neutral-200 bg-white px-3 text-[12px] text-neutral-600 sm:w-[160px]">
                <option value="">Semua bulan</option>
                @foreach ($months as $month)
                    <option value="{{ $month['value'] }}" @selected((string) request('month') === (string) $month['value'])>{{ $month['label'] }}</option>
                @endforeach
            </select>

            <select id="cash-type-filter" name="type" aria-label="Filter jenis"
                class="h-[36px] w-full rounded-lg border border-neutral-200 bg-white px-3 text-[12px] text-neutral-600 sm:w-[140px]">
                <option value="">Semua jenis</option>
                <option value="Cash In" @selected(request('type') === 'Cash In')>Cash In</option>
                <option value="Cash Out" @selected(request('type') === 'Cash Out')>Cash Out</option>
            </select>

            <select id="cash-source-filter" name="source" aria-label="Filter sumber"
                class="h-[36px] w-full rounded-lg border border-neutral-200 bg-white px-3 text-[12px] text-neutral-600 sm:w-[140px]">
                <option value="">Semua sumber</option>
                <option value="PAYMENT" @selected(request('source') === 'PAYMENT')>Payment</option>
                <option value="PETTY_CASH" @selected(request('source') === 'PETTY_CASH')>Petty Cash</option>
                <option value="MANUAL" @selected(request('source') === 'MANUAL')>Manual</option>
            </select>

<select id="cash-status-filter" name="status" aria-label="Filter status"
                class="h-[36px] w-full rounded-lg border border-neutral-200 bg-white px-3 text-[12px] text-neutral-600 sm:w-[140px]">
                <option value="">Semua status</option>
                <option value="Posted" @selected(request('status') === 'Posted')>Posted</option>
                <option value="Reversed" @selected(request('status') === 'Reversed')>Reversed</option>
            </select>

            <a href="{{ route('cash-flow.index') }}"
                class="inline-flex h-[36px] items-center justify-center rounded-lg border border-neutral-200 bg-white px-3 text-[11px] font-semibold text-neutral-600 hover:bg-neutral-50">Reset</a>

            @can('cash_flow.export')
                <span class="ml-auto flex gap-2">
                    <a href="{{ route('cash-flow.export', request()->query()) }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 bg-white px-3 h-[36px] text-[11px] font-semibold text-neutral-600 transition hover:bg-neutral-50">
                        <svg class="h-4 w-4 fill-none stroke-current" aria-hidden="true" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v11m0 0 4-4m-4 4-4-4M5 20h14"/></svg>Ekspor Excel / CSV</a>
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 bg-white px-3 h-[36px] text-[11px] font-semibold text-neutral-600 transition hover:bg-neutral-50"><svg
                            class="h-4 w-4 fill-none stroke-current" aria-hidden="true" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>Cetak / Simpan PDF</button>
                </span>
            @endcan
        </form>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[960px] border-collapse text-left">
                <thead>
                    <tr class="bg-neutral-50">
                        <th class="border-b border-neutral-200 px-4 py-3 text-[11px] font-bold text-neutral-500">No. transaksi</th>
                        <th class="border-b border-neutral-200 px-4 py-3 text-[11px] font-bold text-neutral-500">Tanggal</th>
                        <th class="border-b border-neutral-200 px-4 py-3 text-[11px] font-bold text-neutral-500">Jenis</th>
                        <th class="border-b border-neutral-200 px-4 py-3 text-[11px] font-bold text-neutral-500">Kategori</th>
                        <th class="border-b border-neutral-200 px-4 py-3 text-[11px] font-bold text-neutral-500">Keterangan</th>
                        <th class="border-b border-neutral-200 px-4 py-3 text-[11px] font-bold text-neutral-500">Nominal</th>
                        <th class="border-b border-neutral-200 px-4 py-3 text-[11px] font-bold text-neutral-500">Status</th>
                        <th class="border-b border-neutral-200 px-4 py-3 text-[11px] font-bold text-neutral-500">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $tx)
                        <tr class="transition hover:bg-[#fcfcfe]">
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px] font-sans font-bold text-primary-500">{{ $tx->transaction_number }}</td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px] text-neutral-600">{{ $tx->transaction_date?->format('d M Y') ?? '' }}</td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px] font-medium">
                                @php $type = $tx->transaction_type?->value ?? $tx->transaction_type ?? '' @endphp
                                <span class="inline-flex items-center gap-[5px] rounded-[5px] px-2 py-1 text-[10px] font-semibold {{ $type === 'Cash In' ? 'bg-success-50 text-success-500' : 'bg-neutral-50 text-neutral-700' }}"><span
                                        class="h-[5px] w-[5px] rounded-full bg-current"></span>{{ $type }}</span>
                            </td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px] text-neutral-600 max-w-[180px] truncate">{{ $tx->category }}</td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px] text-neutral-600 max-w-[200px] truncate" title="{{ $tx->description }}">{{ $tx->description }}</td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px] font-sans {{ $tx->amount > 0 ? 'text-success-500' : 'text-neutral-700' }}">{{ $tx->amount > 0 ? '+' : '−' }} {{ number_format(abs((float) $tx->amount), 2, ',', '.') }}</td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px]">
                                @php
                                    $status = $tx->status?->value ?? $tx->status ?? '';
                                    $statusClass = match ($status) {
                                        'Posted' => 'bg-success-50 text-success-500',
                                        'Reversed' => 'bg-error-50 text-error-500',
                                        default => 'bg-neutral-50 text-neutral-500',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-[5px] rounded-[5px] px-2 py-1 text-[10px] font-semibold {{ $statusClass }}"><span
                                        class="h-[5px] w-[5px] rounded-full bg-current"></span>{{ $status }}</span>
                            </td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px]">
                                <a href="{{ route('cash-flow.show', $tx->cash_transaction_id) }}"
                                    class="text-[11px] font-semibold text-[#0d21a1] hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <p class="text-[12px] font-bold text-neutral-500">Tidak ada transaksi kas.</p>
                                <p class="mt-1 text-[11px] text-neutral-400">Ubah kata kunci atau filter untuk melihat data lain.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-neutral-100 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap items-center gap-4 text-[10px] text-neutral-500"><span>Menampilkan
                    {{ $transactions->firstItem() ?? 0 }}–{{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }}</span><label
                    class="flex items-center gap-2">Tampilkan <select id="cash-per-page" name="per_page"
                        form="cash-filter-form"
                        class="h-8 rounded-md border border-neutral-200 bg-white px-2 text-[10px] text-neutral-600">
                        <option value="5" @selected((int) ($filters['per_page'] ?? 10) === 5)>5</option>
                        <option value="10" @selected((int) ($filters['per_page'] ?? 10) === 10)>10</option>
                        <option value="25" @selected((int) ($filters['per_page'] ?? 10) === 25)>25</option>
                        <option value="50" @selected((int) ($filters['per_page'] ?? 10) === 50)>50</option>
                    </select></label></div>
            <div class="flex items-center justify-end gap-2 text-[11px]">
                <a href="{{ $transactions->previousPageUrl() ?? '#' }}"
                    @if (!$transactions->previousPageUrl()) aria-disabled="true" tabindex="-1" @endif
                    class="rounded-md border border-neutral-200 px-3 py-2 font-semibold {{ $transactions->onFirstPage() ? 'pointer-events-none opacity-40' : 'text-neutral-600 hover:bg-neutral-50' }}">Sebelumnya</a>
                <span class="px-1 text-neutral-500">Halaman
                    {{ $transactions->currentPage() }}/{{ max(1, $transactions->lastPage()) }}</span>
                <a href="{{ $transactions->nextPageUrl() ?? '#' }}"
                    @if (!$transactions->hasMorePages()) aria-disabled="true" tabindex="-1" @endif
                    class="rounded-md border border-neutral-200 px-3 py-2 font-semibold {{ $transactions->hasMorePages() ? 'text-neutral-600 hover:bg-neutral-50' : 'pointer-events-none opacity-40' }}">Berikutnya</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const form = document.getElementById('cash-filter-form');
            if (!form) return;
            const search = document.getElementById('cash-search');
            const month = document.getElementById('cash-month-filter');
            const type = document.getElementById('cash-type-filter');
            const source = document.getElementById('cash-source-filter');
            const status = document.getElementById('cash-status-filter');
            const perPage = document.getElementById('cash-per-page');
            let searchTimer;
            [month, type, source, status, perPage].forEach((field) => field?.addEventListener('change', () => form.requestSubmit()));
            search?.addEventListener('input', () => {
                window.clearTimeout(searchTimer);
                searchTimer = window.setTimeout(() => form.requestSubmit(), 350);
            });
        })();
    </script>
@endpush


