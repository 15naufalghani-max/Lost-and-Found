<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Lost & Found UPNVJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h1 class="text-2xl font-bold text-slate-900">Masuk Lost &amp; Found</h1>
        <p class="text-sm text-slate-600 mt-1">Gunakan email resmi UPNVJ (@mahasiswa.upnvj.ac.id atau @dosen.upnvj.ac.id).</p>

        @if (session('status'))
            <div class="mt-4 rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email UPNVJ</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    placeholder="nama@mahasiswa.upnvj.ac.id"
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

            <div class="flex items-center justify-between text-sm">
                <label class="inline-flex items-center gap-2 text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 text-sm transition">
                Masuk
            </button>
        </form>

        <p class="mt-5 text-center text-sm text-slate-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-emerald-700 font-medium hover:underline">Daftar di sini</a>
        </p>
    </div>
</body>
</html>
