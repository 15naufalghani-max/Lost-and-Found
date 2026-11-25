<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaimRequest;
use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ClaimController extends Controller
{
    /**
     * Show the form to submit a claim for an open item.
     */
    public function create(Request $request, Item $item): RedirectResponse|View
    {
        if ($item->user_id === $request->user()->id) {
            return redirect()->route('items.show', $item)
                ->with('error', 'Anda tidak dapat mengklaim laporan milik sendiri.');
        }

        if ($item->status !== Item::STATUS_OPEN || $item->isExpired()) {
            return redirect()->route('items.show', $item)
                ->with('error', 'Barang ini sudah tidak menerima klaim.');
        }

        $view = view()->exists('claims.create') ? 'claims.create' : 'backend.claims.create';

        return view($view, [
            'item' => $item->load('user'),
        ]);
    }

    /**
     * Store a claim and verify against the secret answer when configured.
     */
    public function store(StoreClaimRequest $request, Item $item): RedirectResponse
    {
        $user = $request->user();

        if ($item->user_id === $user->id) {
            return back()->with('error', 'Anda tidak dapat mengklaim laporan milik sendiri.');
        }

        if ($item->status !== Item::STATUS_OPEN || $item->isExpired()) {
            return back()->with('error', 'Barang ini sudah selesai atau kadaluwarsa.');
        }

        $alreadyPending = $item->claims()
            ->where('user_id', $user->id)
            ->where('status', Claim::STATUS_PENDING)
            ->exists();

        if ($alreadyPending) {
            return back()->with('error', 'Anda sudah mengajukan klaim yang masih menunggu verifikasi untuk barang ini.');
        }

        $validated = $request->validated();
        $proofPath = $request->hasFile('proof_image')
            ? $request->file('proof_image')->store('claims', 'public')
            : null;

        $isSecretMatch = Claim::matchesSecretAnswer($validated['answer'], $item->verification_answer);

        DB::transaction(function () use ($item, $user, $validated, $proofPath, $isSecretMatch) {
            $claim = $item->claims()->create([
                'user_id' => $user->id,
                'answer' => $validated['answer'],
                'proof_image' => $proofPath,
                'status' => $isSecretMatch ? Claim::STATUS_APPROVED : Claim::STATUS_PENDING,
                'reviewed_at' => $isSecretMatch ? now() : null,
            ]);

            if ($isSecretMatch) {
                $this->resolveItemForClaim($item, $claim);
            }
        });

        $message = $isSecretMatch
            ? 'Klaim berhasil diverifikasi otomatis karena jawaban rahasia cocok! Barang ditandai selesai.'
            : 'Klaim berhasil diajukan dan menunggu verifikasi pemilik postingan.';

        return redirect()->route('items.show', $item)->with('status', $message);
    }

    /**
     * Approve a pending claim and resolve the item (owner only).
     */
    public function approve(Request $request, Claim $claim): RedirectResponse
    {
        $item = $claim->item;
        abort_if($item->user_id !== $request->user()->id, 403);

        if ($claim->status !== Claim::STATUS_PENDING) {
            return back()->with('error', 'Klaim ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($item, $claim) {
            $claim->update([
                'status' => Claim::STATUS_APPROVED,
                'reviewed_at' => now(),
            ]);

            $this->resolveItemForClaim($item, $claim);
        });

        return back()->with('status', 'Klaim disetujui dan status barang ditandai selesai.');
    }

    /**
     * Reject a pending claim (owner only).
     */
    public function reject(Request $request, Claim $claim): RedirectResponse
    {
        $item = $claim->item;
        abort_if($item->user_id !== $request->user()->id, 403);

        if ($claim->status !== Claim::STATUS_PENDING) {
            return back()->with('error', 'Klaim ini sudah diproses sebelumnya.');
        }

        $claim->update([
            'status' => Claim::STATUS_REJECTED,
            'reviewed_at' => now(),
        ]);

        return back()->with('status', 'Klaim ditolak.');
    }

    /**
     * Mark an item as found/resolved via the Found button (owner only).
     */
    public function markFound(Request $request, Item $item): RedirectResponse
    {
        abort_if($item->user_id !== $request->user()->id, 403);

        if ($item->status !== Item::STATUS_RESOLVED) {
            $item->update(['status' => Item::STATUS_RESOLVED]);
        }

        return back()->with('status', 'Barang berhasil ditandai sudah ditemukan / selesai.');
    }

    private function resolveItemForClaim(Item $item, Claim $approvedClaim): void
    {
        $item->update(['status' => Item::STATUS_RESOLVED]);

        $item->claims()
            ->where('id', '!=', $approvedClaim->id)
            ->where('status', Claim::STATUS_PENDING)
            ->update([
                'status' => Claim::STATUS_REJECTED,
                'reviewed_at' => now(),
            ]);
    }
}
