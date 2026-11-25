<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Lost & Found UPNVJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h1 class="text-2xl font-bold text-slate-900">Daftar Akun Mahasiswa</h1>
        <p class="text-sm text-slate-600 mt-1">Pendaftaran khusus civitas akademika UPNVJ (@mahasiswa.upnvj.ac.id / @dosen.upnvj.ac.id).</p>

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nim" class="block text-sm font-medium text-slate-700">NIM (Opsional)</label>
                <input id="nim" name="nim" type="text" value="{{ old('nim') }}" placeholder="2510512xxx"
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600">
                @error('nim')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email UPNVJ</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    placeholder="2510512xxx@mahasiswa.upnvj.ac.id atau nama@dosen.upnvj.ac.id"
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Kata Sandi</label>
                <input id="password" name="password" type="password" required
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600">
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600">
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 text-sm transition">
                Daftar
            </button>
        </form>

        <p class="mt-5 text-center text-sm text-slate-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-emerald-700 font-medium hover:underline">Masuk di sini</a>
        </p>
    </div>
</body>
</html>
