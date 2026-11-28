<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->title }} — Lost & Found UPNVJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6 text-slate-800">
    <div class="max-w-3xl mx-auto space-y-6">
        <a href="{{ route('items.index') }}" class="text-sm text-emerald-700 hover:underline">&larr; Kembali ke Katalog</a>

        @if (session('status'))
            <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center justify-between text-xs mb-2">
                <span class="px-2 py-0.5 rounded-full bg-slate-100 font-medium">{{ strtoupper($item->type) }}</span>
                <span class="text-slate-500">Status: {{ $item->status }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $item->title }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $item->category }} &bull; {{ $item->location }} &bull; {{ $item->user->name }}</p>
            <p class="mt-4 text-slate-700 whitespace-pre-line">{{ $item->description }}</p>

            @auth
                <div class="mt-6 flex flex-wrap gap-3">
                    @if (auth()->id() === $item->user_id && $item->status === 'open')
                        ... (form Found dan tautan Edit, jangan diubah) ...
                    @endif
                     @if (auth()->id() === $item->user_id && $item->status === 'open')
                        ... (tidak berubah) ...
                    @endif
                    @include('partials.claim-action', ['item' => $item])
                </div>
            @endauth
        </div>

        @include('partials.smart-matches', ['matches' => $matches])
        @include('partials.claim-review', ['item' => $item])

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="font-semibold text-slate-900">Komentar ({{ $item->comments->count() }})</h2>
            @auth
                <form method="POST" action="{{ route('comments.store', $item) }}" class="mt-3 space-y-2">
                    @csrf
                    <textarea name="body" rows="3" required placeholder="Tulis komentar..." class="w-full rounded-lg border px-3 py-2 text-sm"></textarea>
                    @error('body') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    <button type="submit" class="rounded-lg bg-emerald-600 text-white px-4 py-1.5 text-sm font-medium">Kirim Komentar</button>
                </form>
            @endauth

            <div class="mt-4 space-y-3">
                @forelse ($item->comments as $comment)
                    <div class="border-t border-slate-100 pt-3 flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-900">{{ $comment->user->name }}</p>
                            <p class="text-sm text-slate-700 mt-0.5">{{ $comment->body }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                        @if (auth()->id() === $comment->user_id || auth()->id() === $item->user_id)
                            <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada komentar.</p>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>
