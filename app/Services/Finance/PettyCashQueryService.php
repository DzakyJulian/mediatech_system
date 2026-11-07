<?php

namespace App\Services\Finance;

use App\Models\Finance\PettyCashTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PettyCashQueryService
{
    private const VALID_PER_PAGE = [5, 10, 25, 50];

    public function normalizedFilters(array $filters): array
    {
        $perPage = (int) ($filters['per_page'] ?? 10);
        if (!in_array($perPage, self::VALID_PER_PAGE, true)) {
            $perPage = 10;
        }

        $month = $filters['month'] ?? null;
        if ($month !== null && !preg_match('/^\d{4}-\d{2}$/', (string) $month)) {
            $month = null;
        }

        if ($month !== null && $month > now()->format('Y-m')) {
            $month = null;
        }

        return [
            'search' => trim((string) ($filters['search'] ?? '')),
            'type' => $filters['type'] ?? null,
            'status' => $filters['status'] ?? null,
            'month' => $month,
            'date_from' => $filters['date_from'] ?? null,
            'date_to' => $filters['date_to'] ?? null,
            'per_page' => $perPage,
        ];
    }

    public function paginate(array $filters): LengthAwarePaginator
    {
        $filters = $this->normalizedFilters($filters);

        return PettyCashTransaction::query()
            ->with(['bankAccount', 'recordedBy'])
            ->notDeleted()
            ->filter($filters)
            ->orderByDesc('transaction_date')
            ->orderByDesc('petty_cash_id')
            ->paginate($filters['per_page'])
            ->withQueryString();
    }

    public function summary(array $filters): array
    {
        $base = PettyCashTransaction::query()->notDeleted()->filter($filters)->posted();

        $pemasukan = (clone $base)->where('transaction_type', 'Pemasukan')->sum('amount');
        $pengeluaran = (clone $base)->where('transaction_type', 'Pengeluaran')->sum('amount');

        return [
            'pemasukan' => (float) $pemasukan,
            'pengeluaran' => (float) $pengeluaran,
            'net' => (float) $pemasukan - (float) $pengeluaran,
            'count' => (clone $base)->count(),
        ];
    }

    public function monthsWithData(): Collection
    {
        $currentMonth = now()->format('Y-m');

        $dateFormat = DB::getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', transaction_date)"
            : "DATE_FORMAT(transaction_date, '%Y-%m')";

        return PettyCashTransaction::query()
            ->notDeleted()
            ->selectRaw("{$dateFormat} as month")
            ->distinct()
            ->orderByDesc('month')
            ->pluck('month')
            ->filter(fn ($month) => $month !== null && $month <= $currentMonth)
            ->values()
            ->map(fn ($month) => [
                'value' => $month,
                'label' => Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y'),
            ]);
    }

    public function exportRows(array $filters): Builder
    {
        $filters = $this->normalizedFilters($filters);

        return PettyCashTransaction::query()
            ->with(['bankAccount', 'recordedBy'])
            ->notDeleted()
            ->filter($filters)
            ->posted()
            ->orderByDesc('transaction_date')
            ->orderByDesc('petty_cash_id');
    }

    public function trashPaginate(array $filters): LengthAwarePaginator
    {
        $filters = $this->normalizedFilters($filters);

        return PettyCashTransaction::query()
            ->with(['bankAccount', 'recordedBy', 'deletedBy'])
            ->deleted()
            ->filter($filters)
            ->orderByDesc('deleted_at')
            ->paginate($filters['per_page'])
            ->withQueryString();
    }

    public function trashSummary(): array
    {
        $base = PettyCashTransaction::query()->deleted();

        return [
            'count' => (clone $base)->count(),
        ];
    }
}