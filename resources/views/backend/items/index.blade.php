<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Barang — Lost & Found UPNVJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800">
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('items.index') }}" class="font-bold text-lg text-emerald-700">Lost &amp; Found UPNVJ</a>
            <div class="flex items-center gap-3 text-sm">
                @auth
                    <a href="{{ route('profile.index') }}" class="text-slate-600 hover:underline">{{ auth()->user()->name }}</a>
                    <a href="{{ route('items.create') }}" class="rounded-lg bg-emerald-600 text-white px-3 py-1.5 font-medium">+ Lapor Barang</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-600 hover:underline">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-700 hover:underline">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-emerald-600 text-white px-3 py-1.5 font-medium">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8 space-y-6">
        @if (session('status'))
            <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="GET" action="{{ route('items.index') }}" class="bg-white rounded-xl border border-slate-200 p-4 grid grid-cols-1 md:grid-cols-6 gap-3">
            <input type="text" name="search" value="{{ $filters['search'] ?? $filters['q'] ?? '' }}" placeholder="Cari barang, lokasi..."
                class="md:col-span-2 rounded-lg border border-slate-300 px-3 py-2 text-sm">
            <select name="type" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Jenis</option>
                <option value="lost" @selected(($filters['type'] ?? '') === 'lost')>Lost (Hilang)</option>
                <option value="found" @selected(($filters['type'] ?? '') === 'found')>Found (Ditemukan)</option>
            </select>
            <select name="category" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" @selected(($filters['category'] ?? '') === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                @foreach ($statuses as $st)
                    <option value="{{ $st }}" @selected(($filters['status'] ?? '') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-emerald-600 text-white px-4 py-2 text-sm font-medium">Filter</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($items as $item)
                <a href="{{ route('items.show', $item) }}" class="block bg-white rounded-xl border border-slate-200 p-4 hover:border-emerald-400 transition">
                    <div class="flex items-center justify-between text-xs mb-2">
                        <span class="px-2 py-0.5 rounded-full font-medium {{ $item->type === 'lost' ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-800' }}">
                            {{ strtoupper($item->type) }}
                        </span>
                        <span class="text-slate-500">{{ $item->status }}</span>
                    </div>
                    <h2 class="font-semibold text-slate-900">{{ $item->title }}</h2>
                    <p class="text-xs text-slate-500 mt-1">{{ $item->category }} &bull; {{ $item->location }}</p>
                    <p class="text-sm text-slate-600 mt-2 line-clamp-2">{{ $item->description }}</p>
                    <p class="text-xs text-slate-400 mt-3">{{ $item->user->name }} &bull; {{ $item->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <p class="text-slate-500">Belum ada laporan barang.</p>
            @endforelse
        </div>

        <div>
            {{ $items->links() }}
        </div>
    </main>
</body>
</html>
