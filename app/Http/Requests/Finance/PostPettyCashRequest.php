<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class PostPettyCashRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('petty_cash.post') ?? false;
    }

    public function rules(): array
    {
        return [];
    }
}