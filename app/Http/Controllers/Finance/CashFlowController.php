<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\CashTransaction;
use App\Services\Finance\CashFlowQueryService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CashFlowController extends Controller
{
    public function __construct(private readonly CashFlowQueryService $cashFlow)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only([
            'search',
            'type',
            'source',
            'status',
            'month',
            'per_page',
        ]);

        $transactions = $this->cashFlow->paginate($filters);
        $summary = $this->cashFlow->summary($filters);
        $months = $this->cashFlow->monthsWithData();

        return view('finance.cash-flow.index', [
            'transactions' => $transactions,
            'summary' => $summary,
            'months' => $months,
            'filters' => $filters,
        ]);
    }

    public function show(CashTransaction $cashTransaction): View

    {
        $cashTransaction->load(["bankAccount", "recordedBy"]);

        return view("finance.cash-flow.detail", [
            "tx" => $cashTransaction,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only([
            'search',
            'type',
            'source',
            'status',
            'month',
        ]);

        $query = $this->cashFlow->exportRows($filters);

        $filename = 'cash-flow-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $output = fopen('php://output', 'w');

            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'No. Transaksi',
                'Tanggal',
                'Jenis',
                'Sumber',
                'Kategori',
                'Keterangan',
                'Referensi',
                'Nominal (Rp)',
                'Status',
                'Dicatat Oleh',
                'Rekening Bank',
            ]);

            $query->chunkById(500, function ($rows) use ($output) {
                foreach ($rows as $row) {
                    $amount = (float) $row->amount;
                    $prefix = $amount > 0 ? '+' : 'âˆ’';
                    $formattedAmount = $prefix . ' ' . number_format(abs($amount), 2, ',', '.');

                    $ref = '';
                    if ($row->source_type === 'PAYMENT' && $row->payment_id) {
                        $ref = 'PAY-' . $row->payment_id;
                    } elseif ($row->source_type === 'PETTY_CASH' && $row->petty_cash_id) {
                        $ref = 'PC-' . $row->petty_cash_id;
                    }

                    fputcsv($output, [
                        $row->transaction_number,
                        $row->transaction_date?->format('d M Y') ?? '',
                        $row->transaction_type?->value ?? $row->transaction_type ?? '',
                        $row->source_type?->value ?? $row->source_type ?? '',
                        $row->category,
                        $row->description,
                        $ref,
                        $formattedAmount,
                        $row->status?->value ?? $row->status ?? '',
                        $row->recordedBy?->name ?? '',
                        $row->bankAccount?->account_code ?? '',
                    ]);
                }
            });

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}