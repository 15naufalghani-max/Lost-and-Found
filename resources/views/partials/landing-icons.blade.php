{{--
    Kumpulan gambar SVG untuk landing page. Tidak tampil langsung.
    Cara pakai: <svg class="art" viewBox="0 0 120 120"><use href="#art-kunci" /></svg>
    Warna diatur lewat class .art-* di resources/css/app.css
--}}
<svg class="visually-hidden" width="0" height="0" aria-hidden="true" focusable="false">
    <defs>
        {{-- Ilustrasi barang --}}
        <symbol id="art-kunci" viewBox="0 0 120 120">
            <path class="art-shape art-fill--peach" d="M26 84C21 97 24 108 32 117L48 105C40 100 37 94 37 86Z" />
            <path class="art-shape art-fill--amber" d="M60 50H108Q115 50 115 57V61Q115 67 109 67H106V78H95V67H87V82H75V67H60Z" />
            <circle class="art-shape art-fill--amber" cx="36" cy="58" r="29" />
            <circle class="art-shape art-fill--beige" cx="36" cy="58" r="10" />
            <path class="art-line art-line--light" d="M16 54A20 20 0 0 1 29 39" />
        </symbol>

        <symbol id="art-earphone" viewBox="0 0 120 120">
            <rect class="art-shape art-fill--white" x="35" y="24" width="13" height="40" rx="6" transform="rotate(-8 41 44)" />
            <rect class="art-shape art-fill--white" x="72" y="24" width="13" height="40" rx="6" transform="rotate(8 79 44)" />
            <circle class="art-shape art-fill--white" cx="32" cy="24" r="14" />
            <circle class="art-shape art-fill--white" cx="88" cy="24" r="14" />
            <circle class="art-shape art-fill--peach" cx="34" cy="24" r="5" />
            <circle class="art-shape art-fill--peach" cx="86" cy="24" r="5" />
            <rect class="art-shape art-fill--white" x="14" y="54" width="92" height="58" rx="28" />
            <path class="art-line" d="M14 78H106" />
            <circle class="art-shape art-fill--olive" cx="60" cy="94" r="4" />
        </symbol>

        <symbol id="art-dompet" viewBox="0 0 120 120">
            <rect class="art-shape art-fill--olive" x="26" y="16" width="62" height="40" rx="6" transform="rotate(-9 57 36)" />
            <rect class="art-shape art-fill--chocolate" x="8" y="32" width="96" height="74" rx="14" />
            <rect class="art-line art-line--stitch" x="17" y="41" width="78" height="56" rx="8" />
            <rect class="art-shape art-fill--peach" x="78" y="56" width="38" height="28" rx="10" />
            <circle class="art-fill--ink" cx="94" cy="70" r="6" />
        </symbol>

        <symbol id="art-botol" viewBox="0 0 120 120">
            <rect class="art-shape art-fill--chocolate" x="47" y="6" width="26" height="18" rx="5" />
            <path class="art-shape art-fill--olive" d="M36 40H84L78 107Q77 115 69 115H51Q43 115 42 107Z" />
            <path class="art-shape art-fill--beige" d="M38 62H82L80.500 79H39.500Z" />
            <rect class="art-shape art-fill--peach" x="31" y="20" width="58" height="22" rx="8" />
            <path class="art-line art-line--light" d="M50 88L51.500 104" />
        </symbol>

        <symbol id="art-tas" viewBox="0 0 120 120">
            <path class="art-line" d="M44 32V24A16 16 0 0 1 76 24V32" />
            <path class="art-shape art-fill--peach" d="M20 58Q20 26 60 26Q100 26 100 58V96Q100 114 82 114H38Q20 114 20 96Z" />
            <path class="art-line" d="M20 52Q60 70 100 52" />
            <rect class="art-shape art-fill--chocolate" x="36" y="70" width="48" height="32" rx="9" />
            <path class="art-line art-line--thin" d="M36 83H84" />
            <rect class="art-shape art-fill--amber" x="55" y="77" width="10" height="13" rx="2" />
        </symbol>

        <symbol id="art-buku" viewBox="0 0 120 120">
            <rect class="art-shape art-fill--white" x="34" y="16" width="66" height="92" rx="7" />
            <path class="art-shape art-fill--chocolate" d="M66 96H82V118L74 111L66 118Z" />
            <rect class="art-shape art-fill--olive" x="20" y="8" width="72" height="94" rx="7" />
            <path class="art-line" d="M36 8V102" />
            <rect class="art-shape art-fill--beige" x="47" y="28" width="34" height="18" rx="3" />
        </symbol>

        <symbol id="art-payung" viewBox="0 0 120 120">
            <path class="art-line" d="M60 50V104A9 9 0 0 1 42 104" />
            <path class="art-shape art-fill--olive" d="M6 66C6 8 114 8 114 66Q103.200 55 92.400 66Q81.600 55 70.800 66Q60 55 49.200 66Q38.400 55 27.600 66Q16.800 55 6 66Z" />
            <path class="art-line art-line--thin" d="M60 23Q30 36 27.600 66M60 23Q48 40 49.200 66M60 23Q72 40 70.800 66M60 23Q90 36 92.400 66" />
            <path class="art-line" d="M60 22V10" />
        </symbol>

        <symbol id="art-laptop" viewBox="0 0 120 120">
            <rect class="art-fill--ink" x="20" y="16" width="80" height="64" rx="9" />
            <rect class="art-fill--peach" x="29" y="25" width="62" height="46" rx="3" />
            <path class="art-line art-line--thin art-line--light" d="M37 36H67M37 47H82M37 58H58" />
            <path class="art-shape art-fill--amber" d="M5 80H115L108 98Q107 102 103 102H17Q13 102 12 98Z" />
            <path class="art-line" d="M47 87H73" />
        </symbol>

        <symbol id="art-kartu" viewBox="0 0 120 120">
            <rect class="art-shape art-fill--white" x="4" y="20" width="112" height="82" rx="11" />
            <path class="art-shape art-fill--chocolate" d="M4 44V31Q4 20 15 20H105Q116 20 116 31V44Z" />
            <rect class="art-shape art-fill--beige" x="50" y="28" width="20" height="8" rx="4" />
            <rect class="art-shape art-fill--peach" x="15" y="54" width="30" height="36" rx="4" />
            <circle class="art-shape art-fill--white" cx="30" cy="66" r="6" />
            <path class="art-line art-line--thin" d="M19 89Q30 70 41 89" />
            <path class="art-line art-line--gray" d="M57 58H104M57 72H92M57 86H99" />
        </symbol>

        <symbol id="art-kilau" viewBox="0 0 40 40">
            <path d="M20 2Q22 18 38 20Q22 22 20 38Q18 22 2 20Q18 18 20 2Z" />
        </symbol>

        {{-- Ikon garis --}}
        <symbol id="icon-cari" viewBox="0 0 24 24">
            <circle cx="10" cy="10" r="6.500" />
            <path d="M15 15l6 6" />
        </symbol>
        <symbol id="icon-lokasi" viewBox="0 0 24 24">
            <path d="M12 22s7-6.500 7-12a7 7 0 0 0-14 0c0 5.500 7 12 7 12z" />
            <circle cx="12" cy="10" r="2.500" />
        </symbol>
        <symbol id="icon-lapor" viewBox="0 0 24 24">
            <path d="M12 22s7-6.500 7-12a7 7 0 0 0-14 0c0 5.500 7 12 7 12zM12 7v4M12 14v.1" />
        </symbol>
        <symbol id="icon-kamera" viewBox="0 0 24 24">
            <path d="M3 7h4l2-3h6l2 3h4v13H3z" />
            <circle cx="12" cy="13" r="3.500" />
        </symbol>
        <symbol id="icon-gembok" viewBox="0 0 24 24">
            <rect x="4" y="11" width="16" height="10" rx="2" />
            <path d="M8 11V7a4 4 0 0 1 8 0v4" />
        </symbol>
        <symbol id="icon-hp-off" viewBox="0 0 24 24">
            <rect x="6" y="2" width="12" height="20" rx="2" />
            <path d="M2 3l20 19" />
        </symbol>
        <symbol id="icon-perisai" viewBox="0 0 24 24">
            <path d="M12 2l8 3v6c0 5-3.500 9-8 11-4.500-2-8-6-8-11V5z" />
            <path d="M8.500 12l2.500 2.500 4.500-4.500" />
        </symbol>
        <symbol id="icon-catatan" viewBox="0 0 24 24">
            <rect x="4" y="4" width="16" height="18" rx="2" />
            <path d="M9 2h6v4H9zM8 11h8M8 15h8M8 19h4" />
        </symbol>
        <symbol id="icon-jam" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9" />
            <path d="M12 7v5l3.500 2" />
        </symbol>
        <symbol id="icon-cek" viewBox="0 0 24 24">
            <path d="M4 13l5 5L20 6" />
        </symbol>
        <symbol id="icon-info" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9" />
            <path d="M12 11v6M12 7v.1" />
        </symbol>
        <symbol id="icon-naik" viewBox="0 0 24 24">
            <path d="M3 18l6-7 4 4 8-9" />
        </symbol>
        <symbol id="icon-minus" viewBox="0 0 24 24">
            <path d="M4 12h16" />
        </symbol>
        <symbol id="icon-plus" viewBox="0 0 24 24">
            <path d="M12 3v18M3 12h18" />
        </symbol>
    </defs>
</svg>
