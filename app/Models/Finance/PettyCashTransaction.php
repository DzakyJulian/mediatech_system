<?php

namespace App\Models\Finance;

use App\Enums\PettyCashStatus;
use App\Enums\PettyCashType;
use App\Models\Finance\BankAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PettyCashTransaction extends Model
{
    protected $table = 'petty_cash_transactions';

    protected $primaryKey = 'petty_cash_id';

    public $timestamps = false;

    protected $fillable = [
        'transaction_number',
        'transaction_date',
        'transaction_type',
        'category',
        'description',
        'amount',
        'payment_method',
        'bank_account_id',
        'proof_file_path',
        'recorded_by',
        'approved_by',
        'approved_at',
        'status',
        'created_at',
        'updated_at',
        'posted_at',
        'deleted_at',
        'deleted_by',
        'deletion_reason',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'datetime',
            'amount' => 'decimal:2',
            'transaction_type' => PettyCashType::class,
            'status' => PettyCashStatus::class,
            'approved_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'posted_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'bank_account_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by', 'user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by', 'user_id');
    }

    public function scopePosted(Builder $query): Builder
    {
        return $query->where('status', PettyCashStatus::POSTED->value);
    }

    public function scopeNotDeleted(Builder $query): Builder
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeDeleted(Builder $query): Builder
    {
        return $query->whereNotNull('deleted_at');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $query->where(function (Builder $q) use ($search) {
                $q->where('transaction_number', 'like', $search)
                    ->orWhere('description', 'like', $search)
                    ->orWhere('category', 'like', $search);
            });
        }

        if (!empty($filters['type'])) {
            $query->where('transaction_type', $filters['type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['month'])) {
            $query->whereYear('transaction_date', substr($filters['month'], 0, 4))
                ->whereMonth('transaction_date', substr($filters['month'], 5, 2));
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('transaction_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('transaction_date', '<=', $filters['date_to']);
        }

        return $query;
    }
}