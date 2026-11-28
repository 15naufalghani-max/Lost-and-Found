{{-- Input: $item (relasi claims dimuat). Untuk non-pemilik pada barang temuan yang masih open. --}}
@auth
    @if (auth()->id() !== $item->user_id
        && $item->type === \App\Models\Item::TYPE_FOUND
        && $item->status === \App\Models\Item::STATUS_OPEN)
        @php($mine = $item->claims->firstWhere('user_id', auth()->id()))
        @if ($mine && $mine->status === \App\Models\Claim::STATUS_PENDING)
            <p class="text-sm text-slate-600">Klaim Anda sedang menunggu verifikasi pemilik.</p>
            <form method="POST" action="{{ route('claims.cancel', $mine) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium">Batalkan klaim</button>
            </form>
        @else
            <a href="{{ route('claims.create', $item) }}" class="rounded-lg bg-sky-600 text-white px-4 py-2 text-sm font-medium">Ajukan Klaim</a>
        @endif
    @endif
@endauth