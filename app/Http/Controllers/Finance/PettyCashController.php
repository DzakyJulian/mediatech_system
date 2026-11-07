<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\StorePettyCashRequest;
use App\Http\Requests\Finance\UpdatePettyCashRequest;
use App\Http\Requests\Finance\PostPettyCashRequest;
use App\Models\Finance\PettyCashTransaction;
use App\Services\Finance\PettyCashQueryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PettyCashController extends Controller
{
    public function __construct(private readonly PettyCashQueryService $pettyCash)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only([
            'search',
            'type',
            'status',
            'month',
            'per_page',
        ]);

        $transactions = $this->pettyCash->paginate($filters);
        $summary = $this->pettyCash->summary($filters);
        $months = $this->pettyCash->monthsWithData();

        return view('finance.petty-cash.index', [
            'transactions' => $transactions,
            'summary' => $summary,
            'months' => $months,
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('finance.petty-cash.create');
    }

    public function store(StorePettyCashRequest $request): RedirectResponse
    {
        // Implementation will be added
        return redirect()->route('petty-cash.index')
            ->with('success', 'Transaksi petty cash berhasil dibuat.');
    }

    public function show(string $id): View
    {
        $tx = PettyCashTransaction::with(['bankAccount', 'recordedBy', 'approvedBy'])
            ->notDeleted()
            ->findOrFail($id);

        return view('finance.petty-cash.show', [
            'tx' => $tx,
        ]);
    }

    public function edit(string $id): View
    {
        $tx = PettyCashTransaction::notDeleted()->findOrFail($id);

        return view('finance.petty-cash.edit', [
            'tx' => $tx,
        ]);
    }

    public function update(UpdatePettyCashRequest $request, string $id): RedirectResponse
    {
        // Implementation will be added
        return redirect()->route('petty-cash.index')
            ->with('success', 'Transaksi petty cash berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $tx = PettyCashTransaction::notDeleted()->findOrFail($id);
        $tx->delete();

        return redirect()->route('petty-cash.index')
            ->with('success', 'Transaksi petty cash berhasil dihapus sementara.');
    }

    public function post(PostPettyCashRequest $request, string $id): RedirectResponse
    {
        // Implementation will be added
        return redirect()->route('petty-cash.index')
            ->with('success', 'Transaksi petty cash berhasil diposting.');
    }

    public function trash(Request $request): View
    {
        $filters = $request->only([
            'search',
            'per_page',
        ]);

        $transactions = $this->pettyCash->trashPaginate($filters);
        $summary = $this->pettyCash->trashSummary();

        return view('finance.petty-cash.trash', [
            'transactions' => $transactions,
            'summary' => $summary,
            'filters' => $filters,
        ]);
    }

    public function restore(string $id): RedirectResponse
    {
        $tx = PettyCashTransaction::onlyTrashed()->findOrFail($id);
        $tx->restore();

        return redirect()->route('petty-cash.index')
            ->with('success', 'Transaksi petty cash berhasil dipulihkan.');
    }

    public function permanentDelete(string $id): RedirectResponse
    {
        $tx = PettyCashTransaction::onlyTrashed()->findOrFail($id);
        $tx->forceDelete();

        return redirect()->route('petty-cash.trash')
            ->with('success', 'Transaksi petty cash berhasil dihapus permanen.');
    }
}