<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaimRequest;
use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        $attempts = $item->claims()->where('user_id', $user->id)->count();
        if ($attempts >= Claim::MAX_ATTEMPTS) {
            return back()->with('error', 'Batas pengajuan klaim untuk barang ini sudah tercapai.');
        }

        $validated = $request->validated();
        $proofPath = $request->hasFile('proof_image')
            ? $request->file('proof_image')->store('claims', 'public')
            : null;

        // Klaim selalu menunggu keputusan pemilik. Kecocokan jawaban hanya petunjuk untuk pemilik.
        $user->claimedItems()->attach($item->id, [
            'answer'      => $validated['answer'],
            'proof_image' => $proofPath,
            'status'      => Claim::STATUS_PENDING,
        ]);

        return redirect()->route('items.show', $item)
            ->with('status', 'Klaim berhasil diajukan dan menunggu verifikasi pemilik postingan.');
    }

    /**
     * Approve a pending claim and resolve the item (owner only).
     */
    public function approve(Request $request, Claim $claim): RedirectResponse
    {
        abort_if($claim->item->user_id !== $request->user()->id, 403);

        $approved = DB::transaction(function () use ($claim) {
            $item  = Item::whereKey($claim->item_id)->lockForUpdate()->firstOrFail();
            $fresh = Claim::whereKey($claim->id)->lockForUpdate()->firstOrFail();

            if ($fresh->status !== Claim::STATUS_PENDING || $item->status !== Item::STATUS_OPEN) {
                return false;
            }

            $fresh->update(['status' => Claim::STATUS_APPROVED, 'reviewed_at' => now()]);
            $this->resolveItemForClaim($item, $fresh);

            return true;
        });

        return $approved
            ? back()->with('status', 'Klaim disetujui dan status barang ditandai selesai.')
            : back()->with('error', 'Klaim ini sudah diproses atau barang sudah selesai.');
    }

    public function cancel(Request $request, Claim $claim): RedirectResponse
    {
        abort_if($claim->user_id !== $request->user()->id, 403);

        if ($claim->status !== Claim::STATUS_PENDING) {
            return back()->with('error', 'Hanya klaim yang masih menunggu yang dapat dibatalkan.');
        }

        if ($claim->proof_image) {
            Storage::disk('public')->delete($claim->proof_image);
        }

        $request->user()->claimedItems()
            ->wherePivot('status', Claim::STATUS_PENDING)
            ->detach($claim->item_id);

        return back()->with('status', 'Klaim dibatalkan.');
    }

    public function proof(Request $request, Claim $claim): StreamedResponse
    {
        $userId = $request->user()->id;
        abort_unless($userId === $claim->user_id || $userId === $claim->item->user_id, 403);
        abort_if(! $claim->proof_image || ! Storage::disk('public')->exists($claim->proof_image), 404);

        return Storage::disk('public')->download(
            $claim->proof_image,
            'bukti-klaim-'.$claim->id.'.'.pathinfo($claim->proof_image, PATHINFO_EXTENSION)
        );
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
