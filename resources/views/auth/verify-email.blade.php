<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email — Lost & Found UPNVJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h1 class="text-2xl font-bold text-slate-900">Verifikasi Email Kampus</h1>
        <p class="text-sm text-slate-600 mt-2">
            Sebelum melapor atau berkomentar, silakan klik link verifikasi yang telah kami kirim ke email
            <span class="font-medium text-slate-800">{{ auth()->user()?->email }}</span>.
        </p>

        @if (session('status'))
            <div class="mt-4 rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 flex items-center justify-between gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2 text-sm transition">
                    Kirim Ulang Link Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-slate-600 hover:text-slate-900 underline">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</body>
</html>
