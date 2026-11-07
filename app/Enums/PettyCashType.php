<?php

namespace App\Enums;

enum PettyCashType: string
{
    case PEMASUKAN = 'Pemasukan';
    case PENGELUARAN = 'Pengeluaran';

    public function label(): string
    {
        return $this->value;
    }
}