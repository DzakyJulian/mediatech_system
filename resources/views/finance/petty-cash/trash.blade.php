@extends('admin.layouts.layout')

@section('title', 'Petty Cash - Sampah')
@section('page_title', 'Petty Cash')
@section('page_breadcrumb', 'Finance / Petty Cash / Sampah')

@section('content')
    <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-start">
        <div>
            <div class="mb-[7px] text-[12px] font-bold text-[#0d21a1]">Finance workspace</div>
            <h1 class="m-0 text-[31px] font-semibold leading-tight tracking-[-0.8px] text-[#171717] sm:text-[31px]">Sampah Petty Cash</h1>
            <p class="mt-[6px] max-w-2xl text-[14px] leading-relaxed text-[#737373]">Transaksi yang dihapus sementara. Dapat dipulihkan dalam 10 hari.</p>
        </div>
        <a href="{{ route('petty-cash.index') }}"
            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-neutral-200 bg-white px-[14px] py-[10px] text-[12px] font-bold text-neutral-600 transition hover:bg-neutral-50"><svg
                class="h-4 w-4 fill-none stroke-current" aria-hidden="true"><use href="#i-chev"></use></svg>Kembali</a>
    </div>

    <section class="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
        <form id="petty-trash-filter-form" method="GET" action="{{ route('petty-cash.trash') }}"
            class="flex flex-col gap-2 border-b border-neutral-100 p-[14px] sm:flex-row sm:flex-wrap sm:items-center sm:p-[16px]">
            <label class="relative block w-full sm:w-[230px]"><span class="sr-only">Cari transaksi</span><svg
                    class="absolute left-[11px] top-1/2 h-4 w-4 -translate-y-1/2 fill-none stroke-current text-neutral-400"
                    aria-hidden="true"><use href="#i-search"></use>
                </svg><input id="petty-trash-search" type="search" name="search" value="{{ request('search') }}"
                    placeholder="Cari no. transaksi / kategori / keterangan…"
                    class="h-[36px] w-full rounded-lg border border-neutral-200 bg-white pl-[34px] pr-3 text-[12px] outline-none focus:border-primary-300 focus:ring-2 focus:ring-primary-50"></label>

            <a href="{{ route('petty-cash.trash') }}"
                class="inline-flex h-[36px] items-center justify-center rounded-lg border border-neutral-200 bg-white px-3 text-[11px] font-semibold text-neutral-600 hover:bg-neutral-50">Reset</a>
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
                        <th class="border-b border-neutral-200 px-4 py-3 text-[11px] font-bold text-neutral-500">Dicatat oleh</th>
                        <th class="border-b border-neutral-200 px-4 py-3 text-[11px] font-bold text-neutral-500">Status</th>
                        <th class="border-b border-neutral-200 px-4 py-3 text-[11px] font-bold text-neutral-500">Dihapus pada</th>
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
                                <span class="inline-flex items-center gap-[5px] rounded-[5px] px-2 py-1 text-[10px] font-semibold {{ $type === 'Pemasukan' ? 'bg-success-50 text-success-500' : 'bg-neutral-50 text-neutral-700' }}"><span
                                        class="h-[5px] w-[5px] rounded-full bg-current"></span>{{ $type }}</span>
                            </td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px] text-neutral-600 max-w-[180px] truncate">{{ $tx->category }}</td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px] text-neutral-600 max-w-[200px] truncate" title="{{ $tx->description }}">{{ $tx->description }}</td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px] font-sans {{ $tx->amount > 0 ? 'text-success-500' : 'text-neutral-700' }}">{{ $tx->amount > 0 ? '+' : '−' }} {{ number_format(abs((float) $tx->amount), 2, ',', '.') }}</td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px] text-neutral-600">{{ $tx->recordedBy?->name ?? '' }}</td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px]">
                                @php
                                    $status = $tx->status?->value ?? $tx->status ?? '';
                                    $statusClass = match ($status) {
                                        'Posted' => 'bg-success-50 text-success-500',
                                        'Pending Approval' => 'bg-warning-50 text-warning-500',
                                        'Draft' => 'bg-neutral-50 text-neutral-500',
                                        'Reversed' => 'bg-error-50 text-error-500',
                                        'Deleted' => 'bg-neutral-50 text-neutral-500',
                                        default => 'bg-neutral-50 text-neutral-500',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-[5px] rounded-[5px] px-2 py-1 text-[10px] font-semibold {{ $statusClass }}"><span
                                        class="h-[5px] w-[5px] rounded-full bg-current"></span>{{ $status }}</span>
                            </td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px] text-neutral-600">{{ $tx->deleted_at?->format('d M Y H:i') ?? '' }}</td>
                            <td class="border-b border-neutral-100 px-4 py-[13px] text-[12px]">
                                <a href="{{ route('petty-cash.restore', $tx->petty_cash_id) }}"
                                    class="text-[11px] font-semibold text-[#0d21a1] hover:underline">Pulihkan</a>
                                <form action="{{ route('petty-cash.permanent-delete', $tx->petty_cash_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-[11px] font-semibold text-[#b54740] hover:underline"
                                        onclick="return confirm('Yakin ingin menghapus permanen? Tindakan ini tidak dapat dibatalkan.')">Hapus permanen</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-12 text-center">
                                <p class="text-[12px] font-bold text-neutral-500">Tidak ada transaksi di sampah.</p>
                                <p class="mt-1 text-[11px] text-neutral-400">Transaksi yang dihapus sementara akan muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-neutral-100 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap items-center gap-4 text-[10px] text-neutral-500"><span>Menampilkan
                    {{ $transactions->firstItem() ?? 0 }}–{{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }}</span><label
                    class="flex items-center gap-2">Tampilkan <select id="trash-per-page" name="per_page"
                        form="petty-trash-filter-form"
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
            const form = document.getElementById('petty-trash-filter-form');
            if (!form) return;
            const search = document.getElementById('petty-trash-search');
            const perPage = document.getElementById('trash-per-page');
            let searchTimer;
            [perPage].forEach((field) => field?.addEventListener('change', () => form.requestSubmit()));
            search?.addEventListener('input', () => {
                window.clearTimeout(searchTimer);
                searchTimer = window.setTimeout(() => form.requestSubmit(), 350);
            });
        })();
    </script>
@endpush


