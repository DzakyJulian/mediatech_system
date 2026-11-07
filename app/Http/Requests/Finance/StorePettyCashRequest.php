<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePettyCashRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('petty_cash.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'transaction_type' => ['required', Rule::in(['Pemasukan', 'Pengeluaran'])],
            'category' => ['required', 'string', 'max:191'],
            'transaction_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['required', 'string', 'max:1000'],
            'payment_method' => ['required', Rule::in(['Tunai', 'Transfer bank', 'Virtual account', 'Lainnya'])],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,bank_account_id'],
            'proof' => ['nullable', 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
        ];
    }

    public function messages(): array
    {
        return [
            'transaction_type.required' => 'Jenis transaksi wajib dipilih.',
            'transaction_type.in' => 'Jenis transaksi tidak valid.',
            'category.required' => 'Kategori wajib diisi.',
            'category.max' => 'Kategori maksimal 191 karakter.',
            'transaction_date.required' => 'Tanggal wajib diisi.',
            'transaction_date.date' => 'Format tanggal tidak valid.',
            'amount.required' => 'Nominal wajib diisi.',
            'amount.numeric' => 'Nominal harus berupa angka.',
            'amount.min' => 'Nominal minimal 1.',
            'description.required' => 'Keterangan wajib diisi.',
            'description.max' => 'Keterangan maksimal 1000 karakter.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'bank_account_id.exists' => 'Rekening bank tidak valid.',
            'proof.file' => 'Bukti transaksi harus berupa file.',
            'proof.max' => 'Ukuran file maksimal 2MB.',
            'proof.mimes' => 'Format file harus JPG, PNG, atau PDF.',
        ];
    }
}