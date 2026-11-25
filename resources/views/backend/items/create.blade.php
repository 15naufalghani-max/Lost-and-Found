<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Barang — Lost & Found UPNVJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6 text-slate-800">
    <div class="max-w-xl mx-auto bg-white rounded-xl border border-slate-200 p-6">
        <h1 class="text-xl font-bold text-slate-900">Buat Laporan Barang</h1>
        <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">Nama Barang</label>
                <input name="title" value="{{ old('title') }}" required class="mt-1 w-full rounded-lg border px-3 py-2 text-sm">
                @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Jenis</label>
                <select name="type" class="mt-1 w-full rounded-lg border px-3 py-2 text-sm">
                    @foreach ($types as $type)
                        <option value="{{ $type }}" @selected(old('type') === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
                @error('type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Kategori</label>
                <select name="category" class="mt-1 w-full rounded-lg border px-3 py-2 text-sm">
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                @error('category') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Lokasi</label>
                <select name="location" class="mt-1 w-full rounded-lg border px-3 py-2 text-sm">
                    @foreach ($locations as $location)
                        <option value="{{ $location }}" @selected(old('location') === $location)>{{ $location }}</option>
                    @endforeach
                </select>
                @error('location') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Deskripsi</label>
                <textarea name="description" rows="4" required class="mt-1 w-full rounded-lg border px-3 py-2 text-sm">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Foto (Opsional)</label>
                <input type="file" name="image" accept="image/*" class="mt-1 w-full text-sm">
                @error('image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="rounded-lg bg-emerald-600 text-white px-4 py-2 text-sm font-medium">Kirim Laporan</button>
        </form>
    </div>
</body>
</html>
