@extends('admin.layouts.layout')

@section('title', 'Detail Cash Flow')
@section('page_title', 'Detail Cash Flow')
@section('page_breadcrumb', 'Finance / Cash Flow / Detail')

@section('content')
    <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-start">
        <div>
            <div class="mb-[7px] text-[12px] font-bold text-[#0d21a1]">Finance workspace</div>
            <h1 class="m-0 text-[31px] font-semibold leading-tight tracking-[-0.8px] text-[#171717] sm:text-[31px]">Detail Cash Flow</h1>
            <p class="mt-[6px] max-w-2xl text-[14px] leading-relaxed text-[#737373]">Detail transaksi kas #{{ $tx->transaction_number }}</p>
        </div>
        <a href="{{ route('cash-flow.index') }}"
            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-neutral-200 bg-white px-[14px] py-[10px] text-[12px] font-bold text-neutral-600 transition hover:bg-neutral-50"><svg
                class="h-4 w-4 fill-none stroke-current" aria-hidden="true"><use href="#i-chev"></use></svg>Kembali</a>
    </div>

    <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
        <div class="border-b border-neutral-100 p-[18px]">
            <div class="flex items-center gap-3">
                <h2 class="text-[22px] font-semibold text-neutral-900">Detail Transaksi Kas</h2>
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
            </div>
        </div>

        <div class="p-[18px]">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                {{-- Informasi Utama --}}
                <div class="md:col-span-2 lg:col-span-1 space-y-5">
                    <div>
                        <h3 class="font-heading text-lg font-semibold text-neutral-900 mb-3">Informasi Utama</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">No. Transaksi</dt>
                                <dd class="mt-1 text-lg font-sans font-bold text-primary-500">{{ $tx->transaction_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Tanggal</dt>
                                <dd class="mt-1 text-lg text-neutral-900">{{ $tx->transaction_date?->format('d M Y H:i') ?? '' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Jenis</dt>
                                <dd class="mt-1">
                                    @php $type = $tx->transaction_type?->value ?? $tx->transaction_type ?? '' @endphp
                                    <span class="inline-flex items-center gap-[5px] rounded-[5px] px-2 py-1 text-[10px] font-semibold {{ $type === 'Cash In' ? 'bg-success-50 text-success-500' : 'bg-neutral-50 text-neutral-700' }}"><span
                                        class="h-[5px] w-[5px] rounded-full bg-current"></span>{{ $type }}</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Sumber</dt>
                                <dd class="mt-1 text-lg text-neutral-900">{{ $tx->source_type?->label() ?? $tx->source_type ?? '' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Kategori</dt>
                                <dd class="mt-1 text-lg text-neutral-900">{{ $tx->category }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Keterangan</dt>
                                <dd class="mt-1 text-lg text-neutral-900">{{ $tx->description ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="font-heading text-lg font-semibold text-neutral-900 mb-3">Nominal & Status</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Nominal</dt>
                                <dd class="mt-1 text-xl font-sans font-bold {{ $tx->amount > 0 ? 'text-success-500' : 'text-neutral-700' }}">
                                    {{ $tx->amount > 0 ? '+' : '−' }} {{ number_format(abs((float) $tx->amount), 2, ',', '.') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Status</dt>
                                <dd class="mt-1">
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
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Referensi & Sumber --}}
                <div class="space-y-5">
                    <div>
                        <h3 class="font-heading text-lg font-semibold text-neutral-900 mb-3">Referensi & Sumber</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Referensi</dt>
                                <dd class="mt-1 text-lg font-sans text-neutral-900">
                                    @if ($tx->source_type?->value === 'PAYMENT' && $tx->payment_id)
                                        PAY-{{ $tx->payment_id }}
                                    @elseif ($tx->source_type?->value === 'PETTY_CASH' && $tx->petty_cash_id)
                                        PC-{{ $tx->petty_cash_id }}
                                    @else
                                        MANUAL
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Sumber Transaksi</dt>
                                <dd class="mt-1 text-lg text-neutral-900">{{ $tx->source_type?->label() ?? $tx->source_type ?? '' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="font-heading text-lg font-semibold text-neutral-900 mb-3">Rekening Bank</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Rekening</dt>
                                <dd class="mt-1 text-lg font-sans text-neutral-900">{{ $tx->bankAccount?->account_code ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Bank</dt>
                                <dd class="mt-1 text-lg text-neutral-900">{{ $tx->bankAccount?->bank_name ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Pemilik Rekening</dt>
                                <dd class="mt-1 text-lg text-neutral-900">{{ $tx->bankAccount?->account_holder ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">No. Rekening</dt>
                                <dd class="mt-1 text-lg font-sans text-neutral-900">{{ $tx->bankAccount?->account_number ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="font-heading text-lg font-semibold text-neutral-900 mb-3">Dicatat Oleh</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Nama</dt>
                                <dd class="mt-1 text-lg text-neutral-900">{{ $tx->recordedBy?->name ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-neutral-500">Email</dt>
                                <dd class="mt-1 text-lg text-neutral-900">{{ $tx->recordedBy?->email ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


