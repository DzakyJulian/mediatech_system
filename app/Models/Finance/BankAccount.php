<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{
    protected $table = 'bank_accounts';

    protected $primaryKey = 'bank_account_id';

    public $timestamps = false;

    protected $fillable = [
        'account_code',
        'bank_name',
        'account_holder',
        'account_number',
        'purpose',
        'is_active',
        'deleted_at',
        'deleted_by',
        'deletion_reason',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by', 'user_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}