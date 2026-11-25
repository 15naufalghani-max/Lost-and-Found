<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Platform lost & found untuk warga Fakultas Ilmu Komputer UPNVJ.')">
    <title>@yield('title', 'Beranda') | FIK Lost &amp; Found</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@600;700;800&family=Poppins:wght@400;500;600;700&display=swap">

    @vite(['resources/css/app.css'])
</head>
<body class="site-body">
    <a class="skip-link" href="#konten">Langsung ke konten</a>

    <header class="site-header">
        <div class="site-container site-nav">
            <a class="brand" href="{{ url('/') }}">
                <span class="brand__mark" aria-hidden="true">
                    <svg class="icon" viewBox="0 0 24 24">
                        <circle cx="10" cy="10" r="6.5" />
                        <path d="M15 15l6 6M7.5 10h5" />
                    </svg>
                </span>
                <span>SEEKLY</span>
            </a>

            {{-- Menu desktop --}}
            <nav class="site-nav__main" aria-label="Menu utama">
                <ul class="site-nav__links">
                    @foreach ($menu as $item)
                        <li><a class="site-nav__link" href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </nav>

            <a class="btn btn--primary btn--small site-nav__cta"
                @if ($halaman['masuk']) href="{{ $halaman['masuk'] }}" @else role="link" aria-disabled="true" title="Segera hadir" @endif>Masuk</a>

            {{-- Menu tablet dan mobile --}}
            <details class="mobile-menu">
                <summary class="mobile-menu__toggle">
                    <span class="visually-hidden">Buka atau tutup menu</span>
                    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 6h18M3 12h18M3 18h18" />
                    </svg>
                </summary>
                <nav class="mobile-menu__panel" aria-label="Menu mobile">
                    <ul class="mobile-menu__links">
                        @foreach ($menu as $item)
                            <li><a class="mobile-menu__link" href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                        @endforeach
                    </ul>
                    <a class="btn btn--primary"
                        @if ($halaman['masuk']) href="{{ $halaman['masuk'] }}" @else role="link" aria-disabled="true" title="Segera hadir" @endif>Masuk</a>
                </nav>
            </details>
        </div>
    </header>

    <main id="konten">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="site-container">
            <div class="site-footer__top">
                <div class="site-footer__about">
                    <p class="site-footer__brand">SEEKLY</p>
                    <p class="site-footer__desc">Platform lost &amp; found untuk warga Fakultas Ilmu Komputer, Universitas Pembangunan Nasional “Veteran” Jakarta.</p>
                </div>

                <nav class="site-footer__nav" aria-label="Menu footer">
                    @foreach ($footer as $judul => $links)
                        <div>
                            <h2 class="site-footer__heading">{{ $judul }}</h2>
                            <ul class="site-footer__links">
                                @foreach ($links as $link)
                                    <li>
                                        <a @if ($link['url']) href="{{ $link['url'] }}" @else role="link" aria-disabled="true" title="Segera hadir" @endif>{{ $link['label'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </nav>
            </div>

            <div class="site-footer__bottom">
                <p>© {{ date('Y') }} FIK Lost &amp; Found · Fakultas Ilmu Komputer UPNVJ</p>
                <p>Waktu ditampilkan dalam WIB</p>
            </div>
        </div>
    </footer>
</body>
</html>
