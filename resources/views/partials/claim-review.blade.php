{{-- Input: $item (relasi claims.user dimuat). Hanya untuk pemilik barang. --}}
@auth
    @if (auth()->id() === $item->user_id && $item->claims->isNotEmpty())
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="font-semibold text-slate-900">Daftar Klaim Masuk ({{ $item->claims->count() }})</h2>
            <div class="mt-3 space-y-3">
                @foreach ($item->claims as $claim)
                    <div class="border-t border-slate-100 pt-3 flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium">
                                {{ $claim->user->name }}
                                <span class="text-xs text-slate-500">(NIM {{ $claim->user->nim ?? '-' }} · {{ $claim->status }})</span>
                            </p>
                            <p class="text-sm text-slate-700 mt-1">{{ $claim->answer }}</p>
                            @if (\App\Models\Claim::matchesSecretAnswer($claim->answer, $item->verification_answer))
                                <span class="text-xs text-emerald-700">Jawaban cocok dengan jawaban rahasia</span>
                            @endif
                            @if ($claim->proof_image)
                                <a href="{{ route('claims.proof', $claim) }}" class="text-xs text-sky-700 underline ml-2">Unduh bukti</a>
                            @endif
                        </div>
                        @if ($claim->status === \App\Models\Claim::STATUS_PENDING && $item->status === \App\Models\Item::STATUS_OPEN)
                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('claims.approve', $claim) }}">@csrf
                                    <button class="rounded bg-emerald-600 text-white px-3 py-1 text-xs">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('claims.reject', $claim) }}">@csrf
                                    <button class="rounded bg-red-600 text-white px-3 py-1 text-xs">Tolak</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endauth