@extends('layouts.app')

@section('title', 'Beranda')

@php
    // Data dummy untuk tampilan. Belum terhubung ke database.

    $pita = ['Cari Barang', 'Lapor Kehilangan', 'Lapor Temuan', 'Klaim & Verifikasi', 'Serah Terima'];

    $selotip = ['', 'tape--amber', 'tape--olive', ''];

    $alur = [
        'hilang' => [
            'langkah' => [
                ['judul' => 'Cek katalog dulu', 'teks' => 'Cari dengan kata kunci, lalu saring berdasarkan kategori, lokasi, dan tanggal. Siapa tahu barangmu sudah ditemukan.'],
                ['judul' => 'Buat pengumuman kehilangan', 'teks' => 'Isi lokasi terakhir, waktu hilang, dan ciri barang. Detail unik dan nomor WhatsApp-mu tidak ditampilkan ke publik.', 'catatan' => 'Detail unik privat'],
                ['judul' => 'Ajukan klaim', 'teks' => 'Ketemu di katalog atau di rekomendasi Smart Matching? Sebutkan ciri yang hanya pemilik tahu, lalu PJ atau penemu memverifikasinya.'],
                ['judul' => 'Ambil barangmu', 'teks' => 'Setelah klaim disetujui, ambil barang sebelum batas waktu. Serah terima dicatat lengkap dengan identitas pengambil.'],
            ],
            'penutup' => 'Barang ditemukan atau sudah kembali lewat cara lain? Pengumuman bisa ditutup kapan saja dari halaman Aktivitas Saya.',
        ],
        'temuan' => [
            'langkah' => [
                ['judul' => 'Laporkan temuanmu', 'teks' => 'Unggah foto, lokasi, dan waktu ditemukan. Atau tekan “Saya Menemukan Barang Ini” pada pengumuman kehilangan yang cocok.'],
                ['judul' => 'Pegang atau titipkan ke PJ', 'teks' => 'Pilih apakah barang masih kamu pegang atau sudah dititipkan ke PJ. Posisinya bisa diperbarui nanti.'],
                ['judul' => 'Pemilik dicocokkan dulu', 'teks' => 'Kepemilikan dicek dengan ciri yang hanya pemilik tahu, oleh PJ atau olehmu bila barang masih kamu pegang.', 'catatan' => 'Ciri pembeda tetap rahasia'],
                ['judul' => 'Serahkan dengan tenang', 'teks' => 'Serah terima dicatat dengan foto dan identitas pengambil, jadi kamu terlindungi kalau ada pertanyaan nanti.'],
            ],
            'penutup' => 'Kontakmu tidak tampil publik. Pemilik hanya menerima informasi yang kamu kirim secara privat.',
        ],
    ];

    $kategori = [
        ['nama' => 'Elektronik', 'gambar' => 'art-laptop'],
        ['nama' => 'Earphone & aksesori', 'gambar' => 'art-earphone'],
        ['nama' => 'Dompet & kartu', 'gambar' => 'art-dompet'],
        ['nama' => 'Kunci', 'gambar' => 'art-kunci'],
        ['nama' => 'Botol & tumbler', 'gambar' => 'art-botol'],
        ['nama' => 'Tas', 'gambar' => 'art-tas'],
        ['nama' => 'Buku & alat tulis', 'gambar' => 'art-buku'],
        ['nama' => 'Payung', 'gambar' => 'art-payung'],
    ];

    $poinMatching = [
        'Label kecocokan Tinggi atau Sedang, lengkap dengan alasan singkatnya.',
        'Notifikasi di aplikasi dan email saat ada barang temuan yang sangat mirip.',
        'Bukan barangmu? Tandai “Tidak sesuai” dan rekomendasinya disembunyikan.',
    ];

    $privasi = [
        ['ikon' => 'icon-gembok', 'warna' => 'icon-box--amber', 'judul' => 'Detail rahasia tetap rahasia', 'teks' => 'Ciri unik barang hanya dilihat pemilik laporan, verifikator, PJ, dan Admin. Tidak pernah muncul di katalog maupun hasil pencarian.'],
        ['ikon' => 'icon-hp-off', 'warna' => 'icon-box--peach', 'judul' => 'Nomor WhatsApp tidak disebar', 'teks' => 'Kontakmu tidak tampil publik. Kontak hanya dibagikan ke pihak yang terlibat setelah ada konfirmasi.'],
        ['ikon' => 'icon-perisai', 'warna' => 'icon-box--olive', 'judul' => 'Diverifikasi sebelum diserahkan', 'teks' => 'Bukti pemohon dicocokkan dengan detail barang. Klaim bisa disetujui, ditolak dengan alasan, atau dimintai info tambahan.'],
        ['ikon' => 'icon-catatan', 'warna' => '', 'judul' => 'Setiap serah terima tercatat', 'teks' => 'Identitas pengambil, foto serah terima, dan riwayat status disimpan sebagai bukti yang bisa dipertanggungjawabkan.'],
    ];

    // TODO tim backend: ganti null dengan route('...') setelah route pendaftaran ada.
    $urlDaftar = null;
@endphp

@section('content')
    @include('partials.landing-icons')

    {{-- HERO --}}
    <section class="hero" aria-labelledby="judul-hero">
        <div class="hero__decor" aria-hidden="true">
            <span class="hero__bubble">kunci siapa nih?</span>
            <svg class="art hero__art hero__art--key" viewBox="0 0 120 120"><use href="#art-kunci" /></svg>
            <svg class="art hero__art hero__art--earphone" viewBox="0 0 120 120"><use href="#art-earphone" /></svg>
            <svg class="art hero__art hero__art--tumbler" viewBox="0 0 120 120"><use href="#art-botol" /></svg>
            <svg class="art hero__art hero__art--card" viewBox="0 0 120 120"><use href="#art-kartu" /></svg>
            <svg class="sparkle hero__sparkle--one" viewBox="0 0 40 40"><use href="#art-kilau" /></svg>
            <svg class="sparkle hero__sparkle--two" viewBox="0 0 40 40"><use href="#art-kilau" /></svg>
            <svg class="sparkle hero__sparkle--three" viewBox="0 0 40 40"><use href="#art-kilau" /></svg>
        </div>

        <div class="site-container hero__content">
            <p class="hero__badge">Untuk mahasiswa, dosen &amp; tendik FIK UPNVJ</p>
            <h1 class="hero__title" id="judul-hero">
                <span class="hero__line">Hilang di FIK,</span>
                <span class="hero__line"><span class="hero__mark">ketemu</span> di sini.</span>
            </h1>
            <p class="hero__text">Satu tempat untuk melaporkan barang hilang, melaporkan barang temuan, dan mengembalikannya ke pemilik yang sah, tanpa harus menyebar info di banyak grup chat.</p>

            {{-- TODO tim backend: sementara menuju section di halaman ini, ganti dengan route('...') --}}
            <div class="hero__actions">
                <a class="btn btn--primary" href="#kategori">
                    <svg class="icon" aria-hidden="true"><use href="#icon-cari" /></svg>
                    Cari Barang
                </a>
                <a class="btn btn--secondary" href="#cara-kerja">
                    <svg class="icon" aria-hidden="true"><use href="#icon-lapor" /></svg>
                    Lapor Kehilangan
                </a>
                <a class="btn btn--olive" href="#layanan-pj">
                    <svg class="icon" aria-hidden="true"><use href="#icon-kamera" /></svg>
                    Lapor Temuan
                </a>
            </div>

            <p class="hero__note">
                <svg class="icon icon--small" aria-hidden="true"><use href="#icon-gembok" /></svg>
                Masuk dengan email institusi UPNVJ untuk membuka katalog dan membuat laporan.
            </p>
        </div>
    </section>

    {{-- PITA --}}
    <div class="ribbon" aria-hidden="true">
        <div class="ribbon__list">
            @for ($i = 0; $i < 4; $i++)
                @foreach ($pita as $teks)
                    <span class="ribbon__item">
                        {{ $teks }}
                        <svg class="ribbon__sparkle" viewBox="0 0 40 40"><use href="#art-kilau" /></svg>
                    </span>
                @endforeach
            @endfor
        </div>
    </div>

    {{-- CARA KERJA --}}
    <section class="section" id="cara-kerja" aria-labelledby="judul-cara-kerja">
        <div class="site-container">
            <div class="flow">
                <input class="visually-hidden" type="radio" name="alur" id="alur-hilang" autocomplete="off" checked>
                <input class="visually-hidden" type="radio" name="alur" id="alur-temuan" autocomplete="off">

                <div class="flow__head">
                    <div class="flow__heading">
                        <span class="tag">Cara kerja</span>
                        <h2 class="section-title" id="judul-cara-kerja">Dua jalan, satu tujuan: barang kembali ke pemiliknya.</h2>
                    </div>
                    <div class="flow__switch">
                        <label class="flow__option" for="alur-hilang">Saya kehilangan barang</label>
                        <label class="flow__option" for="alur-temuan">Saya menemukan barang</label>
                    </div>
                </div>

                @foreach ($alur as $nama => $isi)
                    <div class="flow__panel flow__panel--{{ $nama }}">
                        <ol class="steps">
                            @foreach ($isi['langkah'] as $langkah)
                                <li class="card step">
                                    <span class="tape {{ $selotip[$loop->index] }}" aria-hidden="true"></span>
                                    <p class="step__number" aria-hidden="true">0{{ $loop->iteration }}</p>
                                    <h3 class="step__title">{{ $langkah['judul'] }}</h3>
                                    <p class="step__text">{{ $langkah['teks'] }}</p>
                                    @isset($langkah['catatan'])
                                        <p class="step__note">
                                            <svg class="icon icon--small" aria-hidden="true"><use href="#icon-gembok" /></svg>
                                            {{ $langkah['catatan'] }}
                                        </p>
                                    @endisset
                                </li>
                            @endforeach
                        </ol>
                        <p class="flow__footnote">{{ $isi['penutup'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- KATEGORI --}}
    <section class="section section--peach" id="kategori" aria-labelledby="judul-kategori">
        <div class="site-container category">
            <div class="category__intro">
                <h2 class="section-title" id="judul-kategori">Dari kunci sampai laptop, cari sesuai jenisnya.</h2>
                <p class="section-text">Katalog berisi dua tab, Barang Temuan dan Pengumuman Kehilangan, yang bisa kamu saring berdasarkan kategori, lokasi, dan tanggal kejadian.</p>
            </div>

            <ul class="category__grid">
                @foreach ($kategori as $item)
                    <li class="card category-card">
                        <svg class="art" viewBox="0 0 120 120" aria-hidden="true"><use href="#{{ $item['gambar'] }}" /></svg>
                        {{ $item['nama'] }}
                    </li>
                @endforeach
                <li class="card category-card category-card--other">
                    <span class="category-card__plus" aria-hidden="true">
                        <svg class="icon"><use href="#icon-plus" /></svg>
                    </span>
                    Lainnya
                </li>
            </ul>
        </div>
    </section>

    {{-- SMART MATCHING --}}
    <section class="section" id="smart-matching" aria-labelledby="judul-matching">
        <div class="site-container matching">
            <div class="matching__intro">
                <span class="tag tag--olive">Smart Matching</span>
                <h2 class="section-title" id="judul-matching">Nggak perlu ngecek katalog terus-terusan.</h2>
                <p class="section-text">Begitu kamu membuat pengumuman kehilangan, sistem membandingkannya dengan barang temuan yang ada berdasarkan kategori, lokasi, waktu, dan deskripsi. Rekomendasi yang paling mirip muncul di laporanmu.</p>

                <ul class="checklist">
                    @foreach ($poinMatching as $poin)
                        <li class="checklist__item">
                            <span class="checklist__mark" aria-hidden="true">
                                <svg class="icon"><use href="#icon-cek" /></svg>
                            </span>
                            {{ $poin }}
                        </li>
                    @endforeach
                </ul>

                <div class="notice">
                    <svg class="icon" aria-hidden="true"><use href="#icon-info" /></svg>
                    <p><strong>Rekomendasi otomatis, bukan penentu kepemilikan.</strong> Barang tetap baru diserahkan setelah kepemilikan diverifikasi.</p>
                </div>
            </div>

            <div class="report">
                <div class="card report__card">
                    <p class="report__label">Contoh tampilan</p>

                    <div class="report__head">
                        <div>
                            <p class="eyebrow">Laporan kehilanganmu</p>
                            <h3 class="report__title">Earphone nirkabel warna putih</h3>
                        </div>
                        <span class="status status--open">Masih dicari</span>
                    </div>

                    <p class="report__info">
                        <svg class="icon icon--small" aria-hidden="true"><use href="#icon-info" /></svg>
                        Rekomendasi otomatis, bukan penentu kepemilikan.
                    </p>

                    <div class="match">
                        <span class="match__thumb" aria-hidden="true">
                            <svg class="art" viewBox="0 0 120 120"><use href="#art-earphone" /></svg>
                        </span>
                        <div>
                            <div class="match__head">
                                <h4 class="match__name">Earphone TWS putih</h4>
                                <span class="status status--high">
                                    <svg class="icon" aria-hidden="true"><use href="#icon-naik" /></svg>
                                    Kecocokan Tinggi
                                </span>
                            </div>
                            <ul class="match__reasons">
                                <li class="match__reason">Kategori sama</li>
                                <li class="match__reason">Lokasi sama</li>
                                <li class="match__reason">Waktu berdekatan</li>
                            </ul>
                        </div>
                    </div>

                    <div class="match match--medium">
                        <span class="match__thumb" aria-hidden="true">
                            <svg class="art" viewBox="0 0 120 120"><use href="#art-earphone" /></svg>
                        </span>
                        <div>
                            <div class="match__head">
                                <h4 class="match__name">Case earphone</h4>
                                <span class="status status--medium">
                                    <svg class="icon" aria-hidden="true"><use href="#icon-minus" /></svg>
                                    Kecocokan Sedang
                                </span>
                            </div>
                            <ul class="match__reasons">
                                <li class="match__reason">Kategori sama</li>
                                <li class="match__reason">Gedung sama</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <svg class="sparkle report__sparkle" viewBox="0 0 40 40" aria-hidden="true"><use href="#art-kilau" /></svg>
            </div>
        </div>
    </section>

    {{-- PRIVASI DAN KEAMANAN --}}
    <section class="section section--olive" id="privasi" aria-labelledby="judul-privasi">
        <div class="site-container">
            <div class="section-head">
                <span class="tag">Privasi &amp; keamanan</span>
                <h2 class="section-title" id="judul-privasi">Barangnya aman, data pribadimu juga.</h2>
                <p class="section-text">Hanya warga FIK dengan email institusi UPNVJ yang sudah diverifikasi yang bisa memakai layanan ini.</p>
            </div>

            <ul class="privacy">
                @foreach ($privasi as $item)
                    <li class="card privacy-card">
                        <span class="icon-box {{ $item['warna'] }}" aria-hidden="true">
                            <svg class="icon"><use href="#{{ $item['ikon'] }}" /></svg>
                        </span>
                        <h3 class="privacy-card__title">{{ $item['judul'] }}</h3>
                        <p class="privacy-card__text">{{ $item['teks'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- LAYANAN PJ / PENITIPAN BARANG --}}
    <section class="section" id="layanan-pj" aria-labelledby="judul-layanan">
        <div class="site-container">
            <div class="card deposit">
                <span class="tape" aria-hidden="true"></span>
                <span class="tape tape--olive" aria-hidden="true"></span>

                <div class="deposit__main">
                    <svg class="art deposit__art" viewBox="0 0 120 120" aria-hidden="true"><use href="#art-tas" /></svg>
                    <div>
                        <h2 class="deposit__title" id="judul-layanan">Nggak mau nyimpen barang temuan? Titipkan ke Resepsionis.</h2>
                        <p class="deposit__text">Serahkan barang kepada resepsionis untuk disimpan sementara hingga pemiliknya ditemukan.</p>
                    </div>
                </div>

                <div class="deposit__side">
                    <dl class="deposit__list">
                        <div class="deposit__row">
                            <span class="icon-box icon-box--small icon-box--peach" aria-hidden="true">
                                <svg class="icon"><use href="#icon-lokasi" /></svg>
                            </span>
                            <div>
                                <dt class="eyebrow">Lokasi</dt>
                                <dd class="deposit__value">Resepsionis, Gedung Ki Hadjar</dd>
                            </div>
                        </div>
                        <div class="deposit__row">
                            <span class="icon-box icon-box--small icon-box--amber" aria-hidden="true">
                                <svg class="icon"><use href="#icon-jam" /></svg>
                            </span>
                            <div>
                                <dt class="eyebrow">Jam layanan</dt>
                                <dd class="deposit__value">Senin–Jumat, 08.00–16.00 WIB</dd>
                            </div>
                        </div>
                    </dl>
                    <p class="deposit__sample">Contoh — nilai final diambil dari konfigurasi</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="section cta" id="mulai" aria-labelledby="judul-cta">
        <div class="cta__decor" aria-hidden="true">
            <svg class="art cta__art cta__art--umbrella" viewBox="0 0 120 120"><use href="#art-payung" /></svg>
            <svg class="art cta__art cta__art--wallet" viewBox="0 0 120 120"><use href="#art-dompet" /></svg>
            <svg class="art cta__art cta__art--book" viewBox="0 0 120 120"><use href="#art-buku" /></svg>
            <svg class="sparkle cta__sparkle" viewBox="0 0 40 40"><use href="#art-kilau" /></svg>
        </div>

        <div class="site-container cta__content">
            <h2 class="cta__title" id="judul-cta">
                <span class="cta__line">Ada yang hilang?</span>
                <span class="cta__line">Atau baru nemu sesuatu?</span>
            </h2>
            <p class="cta__text">Mulai dari sini. Setiap langkahnya tercatat, dan datamu tetap terlindungi.</p>
            <a class="btn btn--plain btn--wide"
                @if ($urlDaftar) href="{{ $urlDaftar }}" @else role="link" aria-disabled="true" title="Segera hadir" @endif>Mulai</a>
        </div>
    </section>
@endsection
