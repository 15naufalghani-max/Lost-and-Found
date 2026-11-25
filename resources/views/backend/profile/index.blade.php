<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil & Riwayat — Lost & Found UPNVJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6 text-slate-800">
    <div class="max-w-4xl mx-auto space-y-6">
        <a href="{{ route('items.index') }}" class="text-sm text-emerald-700 hover:underline">&larr; Kembali ke Katalog</a>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h1 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h1>
            <p class="text-sm text-slate-600">{{ $user->email }} @if($user->nim) &bull; NIM {{ $user->nim }} @endif</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="font-semibold text-slate-900">Riwayat Laporan Saya ({{ $items->count() }})</h2>
            <div class="mt-3 space-y-2">
                @forelse ($items as $item)
                    <a href="{{ route('items.show', $item) }}" class="flex justify-between rounded-lg border p-3 text-sm hover:border-emerald-400">
                        <span>{{ $item->title }} ({{ strtoupper($item->type) }})</span>
                        <span class="text-slate-500">{{ $item->status }}</span>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Belum ada laporan.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="font-semibold text-slate-900">Riwayat Klaim Saya ({{ $claims->count() }})</h2>
            <div class="mt-3 space-y-2">
                @forelse ($claims as $claim)
                    <a href="{{ route('items.show', $claim->item) }}" class="flex justify-between rounded-lg border p-3 text-sm hover:border-emerald-400">
                        <span>{{ $claim->item->title }}</span>
                        <span class="text-slate-500">{{ $claim->status }}</span>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Belum ada klaim yang diajukan.</p>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>
