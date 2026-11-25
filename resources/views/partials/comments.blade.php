<!--Komentar-->

@php
    $comments = $comments ?? [
        (object) [
            'user_name' => 'Rafi Pratama',
            'created_at' => '10 menit lalu',
            'body' => 'Kayaknya tadi pagi gue lihat barang ini dititipin di pos satpam FIK deh.'
        ],
        (object) [
            'user_name' => 'Aulia Zahra',
            'created_at' => '2 menit lalu',
            'body' => 'Siap makasih infonya, gue cek ke pos satpam sekarang!'
        ]
    ];
@endphp

<section>
    {{-- Judul Bagian --}}
    <h3>Komentar ({{ count($comments ?? [] ) }})</h3>

    {{-- 2. FORM INPUT KOMENTAR --}}
    <form action="" method="POST" class="">
        @csrf
        <div>
            <label>
                <textarea name="comment" id="comment" cols="30" rows="5" 
                class="btn btn--plain btn--wide flow__switch"
                    placeholder="Tulis komentar Anda di sini..."></textarea>
            </label>
        </div>

        <br>
        <button type="submit" class="btn btn--olive">Kirim</button>
    </form>

    {{-- 3. DAFTAR KOMENTAR YANG SUDAH MASUK --}}
    <div>
        @forelse ($comments as $comment)
            <div>
                <p>
                    <strong>{{ $comment->user_name }}</strong> 
                    <small>• {{ $comment->created_at }}</small>
                </p>
                <p>{{ $comment->body }}</p>
            </div>
            <hr>
        @empty
            <p>Belum ada komentar. Jadilah yang pertama berkomentar!</p>
        @endforelse
    </div>


</section>