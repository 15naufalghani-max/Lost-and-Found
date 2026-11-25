<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $item->title }} — Lost & Found UPNVJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6 text-slate-800">
    <div class="max-w-xl mx-auto bg-white rounded-xl border border-slate-200 p-6">
        <h1 class="text-xl font-bold text-slate-900">Edit Laporan Barang</h1>
        <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium">Nama Barang</label>
                <input name="title" value="{{ old('title', $item->title) }}" required class="mt-1 w-full rounded-lg border px-3 py-2 text-sm">
                @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Jenis</label>
                <select name="type" class="mt-1 w-full rounded-lg border px-3 py-2 text-sm">
                    @foreach ($types as $type)
                        <option value="{{ $type }}" @selected(old('type', $item->type) === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Kategori</label>
                <select name="category" class="mt-1 w-full rounded-lg border px-3 py-2 text-sm">
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(old('category', $item->category) === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Lokasi</label>
                <select name="location" class="mt-1 w-full rounded-lg border px-3 py-2 text-sm">
                    @foreach ($locations as $location)
                        <option value="{{ $location }}" @selected(old('location', $item->location) === $location)>{{ $location }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Deskripsi</label>
                <textarea name="description" rows="4" required class="mt-1 w-full rounded-lg border px-3 py-2 text-sm">{{ old('description', $item->description) }}</textarea>
            </div>
            <button type="submit" class="rounded-lg bg-emerald-600 text-white px-4 py-2 text-sm font-medium">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
