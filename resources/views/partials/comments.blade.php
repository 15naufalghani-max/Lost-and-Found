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

<section class="comment-wrapper">
    <div class="comment-card">
        {{-- Judul Bagian --}}
        <h3 class="comment-heading">Komentar ({{ count($comments ?? []) }})</h3>

        {{-- 2. FORM INPUT KOMENTAR --}}
        <form action="" method="POST" class="">
            @csrf
            <div class="comment_field">
                <label for="comment-body" class="comment-label"> Tulis Komentar
                </label>
                <textarea name="comment" id="comment" cols="30" rows="5" class="comment-textarea"
                    placeholder="Tulis komentar Anda di sini..."></textarea>
            </div>

            <div class="comment-actions">
                <button type="submit" class="comment-btn">Kirim</button>
            </div>
        </form>

        <hr class="comment-divider">

        {{-- 3. DAFTAR KOMENTAR YANG SUDAH MASUK --}}
        <div class="comment-list">
            @forelse ($comments as $comment)
                <div class="comment-item">
                    <div class="comment-item-header">
                        <strong class="comment-author">{{ $comment->user_name }}</strong>
                        <span class="comment-time">{{ $comment->created_at }}</span>
                    </div>
                    <p class="comment-body">{{ $comment->body }}</p>
                </div>

            @empty
                <p>Belum ada komentar. Jadilah yang pertama berkomentar!</p>
            @endforelse
        </div>
    </div>
</section>