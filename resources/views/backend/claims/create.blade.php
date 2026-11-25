<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Klaim — {{ $item->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6 text-slate-800">
    <div class="max-w-xl mx-auto bg-white rounded-xl border border-slate-200 p-6">
        <a href="{{ route('items.show', $item) }}" class="text-sm text-emerald-700 hover:underline">&larr; Kembali</a>
        <h1 class="text-xl font-bold text-slate-900 mt-2">Ajukan Klaim: {{ $item->title }}</h1>

        @if ($item->verification_question)
            <div class="mt-4 rounded-lg bg-sky-50 border border-sky-200 p-3 text-sm text-sky-900">
                <span class="font-medium">Pertanyaan Verifikasi:</span> {{ $item->verification_question }}
            </div>
        @endif

        <form method="POST" action="{{ route('claims.store', $item) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">Jawaban Verifikasi / Ciri Kepemilikan</label>
                <textarea name="answer" rows="4" required class="mt-1 w-full rounded-lg border px-3 py-2 text-sm">{{ old('answer') }}</textarea>
                @error('answer') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Foto Bukti (Opsional)</label>
                <input type="file" name="proof_image" accept="image/*" class="mt-1 w-full text-sm">
                @error('proof_image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="rounded-lg bg-emerald-600 text-white px-4 py-2 text-sm font-medium">Kirim Klaim</button>
        </form>
    </div>
</body>
</html>
