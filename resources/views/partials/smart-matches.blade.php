{{-- Input: $matches (Collection dari SmartMatchService::matchesFor) --}}
@if (isset($matches) && $matches->isNotEmpty())
    <div class="bg-white rounded-xl border border-emerald-200 p-6">
        <h2 class="font-semibold text-emerald-900">Rekomendasi Barang Cocok (Smart Matching)</h2>
        <div class="mt-3 space-y-2">
            @foreach ($matches as $m)
                @php($band = $m['score'] >= 70 ? 'Tinggi' : ($m['score'] >= 40 ? 'Sedang' : 'Rendah'))
                <a href="{{ route('items.show', $m['item']) }}" class="block rounded-lg border border-slate-200 p-3 hover:border-emerald-400">
                    <div class="flex justify-between text-sm font-medium">
                        <span>{{ $m['item']->title }}</span>
                        <span class="text-emerald-700">{{ $band }} ({{ $m['score'] }}%)</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ $m['reason'] }} · {{ $m['source'] === 'ai' ? 'dinilai AI' : 'kecocokan kata kunci' }}
                    </p>
                </a>
            @endforeach
        </div>
    </div>
@endif