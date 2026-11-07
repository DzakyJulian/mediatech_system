@extends("admin.layouts.layout")

@section("title", "Tambah Petty Cash")
@section("page_title", "Catat Transaksi Petty Cash")
@section("page_breadcrumb", "Finance / Petty Cash / Tambah")

@section("content")
    <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-start">
        <div>
            <div class="mb-[7px] text-[12px] font-bold text-[#0d21a1]">Finance workspace</div>
            <h1 class="m-0 text-[31px] font-semibold leading-tight tracking-[-0.8px] text-[#171717] sm:text-[31px]">Catat Transaksi Petty Cash</h1>
            <p class="mt-[6px] max-w-2xl text-[14px] leading-relaxed text-[#737373]">Isi form di bawah untuk mencatat transaksi petty cash baru.</p>
        </div>
        <a href="{{ route("petty-cash.index") }}"
            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-neutral-200 bg-white px-[14px] py-[10px] text-[12px] font-bold text-neutral-600 transition hover:bg-neutral-50"><svg
                class="h-4 w-4 fill-none stroke-current" aria-hidden="true"><use href="#i-chev"></use></svg>Kembali</a>
    </div>

    <section class="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-[0_1px_2px_rgba(20,27,55,0.02)]">
        <form method="POST" action="{{ route("petty-cash.store") }}" class="p-[18px]" enctype="multipart/form-data">
            @csrf

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                {{-- Jenis Transaksi --}}
                <div class="md:col-span-1 lg:col-span-1">
                    <label class="block text-sm font-medium text-neutral-700">Jenis Transaksi <span class="text-red-500">*</span></label>
                    <select name="transaction_type" required
                        class="mt-1.5 block w-full rounded-lg border border-neutral-200 bg-white px-3 py-[10px] text-neutral-900 focus:border-primary-300 focus:ring-2 focus:ring-primary-50 focus:outline-none">
                        <option value="">Pilih jenis</option>
                        <option value="Pemasukan" @selected(old("transaction_type") === "Pemasukan")>Pemasukan</option>
                        <option value="Pengeluaran" @selected(old("transaction_type") === "Pengeluaran")>Pengeluaran</option>
                    </select>
                    @error("transaction_type")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div class="md:col-span-1 lg:col-span-1">
                    <label class="block text-sm font-medium text-neutral-700">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="category" value="{{ old("category") }}" required
                        placeholder="Contoh: Operasional kantor, Transportasi, Logistik, dll."
                        class="mt-1.5 block w-full rounded-lg border border-neutral-200 bg-white px-3 py-[10px] text-neutral-900 focus:border-primary-300 focus:ring-2 focus:ring-primary-50 focus:outline-none"
                        placeholder="Contoh: Operasional kantor, Transportasi, Logistik, dll.">
                    @error("category")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal --}}
                <div class="md:col-span-1 lg:col-span-1">
                    <label class="block text-sm font-medium text-neutral-700">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="transaction_date" value="{{ old("transaction_date", now()->format("Y-m-d")) }}" required
                        class="mt-1.5 block w-full rounded-lg border border-neutral-200 bg-white px-3 py-[10px] text-neutral-900 focus:border-primary-300 focus:ring-2 focus:ring-primary-50 focus:outline-none">
                    @error("transaction_date")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nominal --}}
                <div class="md:col-span-1 lg:col-span-1">
                    <label class="block text-sm font-medium text-neutral-700">Nominal (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" value="{{ old("amount") }}" required min="1" step="1"
                        placeholder="Contoh: 450000"
                        class="mt-1.5 block w-full rounded-lg border border-neutral-200 bg-white px-3 py-[10px] text-neutral-900 focus:border-primary-300 focus:ring-2 focus:ring-primary-50 focus:outline-none">
                    @error("amount")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div class="md:col-span-2 lg:col-span-2">
                    <label class="block text-sm font-medium text-neutral-700">Keterangan <span class="text-red-500">*</span></label>
                    <textarea name="description" required rows="3"
                        placeholder="Jelaskan tujuan pemasukan/pengeluaran..."
                        class="mt-1.5 block w-full rounded-lg border border-neutral-200 bg-white px-3 py-3 text-neutral-900 focus:border-primary-300 focus:ring-2 focus:ring-primary-50 focus:outline-none resize-none">{{ old("description") }}</textarea>
                    @error("description")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Metode Pembayaran --}}
                <div class="md:col-span-1 lg:col-span-1">
                    <label class="block text-sm font-medium text-neutral-700">Metode Pembayaran <span class="text-red-500">*</span></label>
                    <select name="payment_method" required
                        class="mt-1.5 block w-full rounded-lg border border-neutral-200 bg-white px-3 py-[10px] text-neutral-900 focus:border-primary-300 focus:ring-2 focus:ring-primary-50 focus:outline-none">
                        <option value="">Pilih metode</option>
                        <option value="Tunai" @selected(old("payment_method") === "Tunai")>Tunai</option>
                        <option value="Transfer bank" @selected(old("payment_method") === "Transfer bank")>Transfer bank</option>
                        <option value="Virtual account" @selected(old("payment_method") === "Virtual account")>Virtual account</option>
                        <option value="Lainnya" @selected(old("payment_method") === "Lainnya")>Lainnya</option>
                    </select>
                    @error("payment_method")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Rekening Bank --}}
                <div class="md:col-span-1 lg:col-span-1">
                    <label class="block text-sm font-medium text-neutral-700">Rekening Bank <span class="text-neutral-500">(Opsional)</span></label>
                    <select name="bank_account_id"
                        class="mt-1.5 block w-full rounded-lg border border-neutral-200 bg-white px-3 py-[10px] text-neutral-900 focus:border-primary-300 focus:ring-2 focus:ring-primary-50 focus:outline-none">
                        <option value="">Pilih rekening (opsional)</option>
                        @foreach($bankAccounts as $account)
                            <option value="{{ $account->bank_account_id }}" @selected(old("bank_account_id") == $account->bank_account_id)>{{ $account->account_code }} - {{ $account->bank_name }} ({{ $account->account_number }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Bukti Transaksi --}}
                <div class="md:col-span-2 lg:col-span-2">
                    <label class="block text-sm font-medium text-neutral-700">Bukti Transaksi <span class="text-neutral-500">(Opsional)</span></label>
                    <input type="file" name="proof" accept="image/*,.pdf"
                        class="mt-1.5 block w-full text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    @error("proof")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-neutral-500">Format: JPG, PNG, PDF. Maksimal 2MB.</p>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route("petty-cash.index") }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-neutral-200 bg-white px-[14px] py-[10px] text-[12px] font-bold text-neutral-600 transition hover:bg-neutral-50">Batal</a>
                <button type="submit"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-[#0d21a1] px-[14px] py-[10px] text-[12px] font-bold text-white transition hover:bg-[#091772]"><svg
                    class="h-4 w-4 fill-none stroke-current" aria-hidden="true">
                    <use href="#i-plus"></use>
                </svg>Simpan Transaksi</button>
            </div>
        </form>
    </section>
@endsection
