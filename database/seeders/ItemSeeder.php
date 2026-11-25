<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Seed realistic Lost & Found items around UPNVJ FIK.
     */
    public function run(): void
    {
        $users = User::orderBy('id')->get();
        if ($users->isEmpty()) {
            return;
        }

        $items = [
            [
                'user_id' => $users[0]->id,
                'title' => 'Dompet Kulit Hitam Polos',
                'type' => Item::TYPE_LOST,
                'category' => 'Dompet & Tas',
                'location' => 'Lab Komputer FIK',
                'description' => 'Dompet kulit warna hitam merk Eiger berisi KTM FIK dan uang tunai secukupnya. Hilang setelah praktikum Basis Data.',
                'status' => Item::STATUS_OPEN,
                'event_at' => now()->subDays(2),
                'expires_at' => now()->addDays(28),
                'verification_question' => null,
            ],
            [
                'user_id' => $users[1 % $users->count()]->id,
                'title' => 'Tumbler Corkcicle Biru Dongker',
                'type' => Item::TYPE_FOUND,
                'category' => 'Lainnya',
                'location' => 'Perpustakaan',
                'description' => 'Ditemukan tumbler biru dongker di meja baca lantai 2 Perpustakaan UPNVJ dengan beberapa stiker di bagian bawah.',
                'status' => Item::STATUS_OPEN,
                'event_at' => now()->subDay(),
                'expires_at' => now()->addDays(29),
                'verification_question' => 'Sebutkan gambar stiker yang menempel di bagian bawah tumbler?',
            ],
            [
                'user_id' => $users[2 % $users->count()]->id,
                'title' => 'Charger Laptop Lenovo Type-C 65W',
                'type' => Item::TYPE_LOST,
                'category' => 'Elektronik',
                'location' => 'Ruang Kelas',
                'description' => 'Tertinggal di Ruang FIK 302 setelah jam kuliah Pemrograman Web. Ada selotip kecil warna kuning di dekat konektor.',
                'status' => Item::STATUS_OPEN,
                'event_at' => now()->subHours(18),
                'expires_at' => now()->addDays(29),
                'verification_question' => null,
            ],
            [
                'user_id' => $users[3 % $users->count()]->id,
                'title' => 'Kunci Motor Honda Vario + Gantungan Bebek',
                'type' => Item::TYPE_FOUND,
                'category' => 'Kunci',
                'location' => 'Parkiran',
                'description' => 'Ditemukan kunci motor Honda lengkap dengan remote dan gantungan boneka bebek di area parkiran motor dekat pos satpam.',
                'status' => Item::STATUS_OPEN,
                'event_at' => now()->subHours(8),
                'expires_at' => now()->addDays(30),
                'verification_question' => 'Apa warna gantungan kunci atau tulisan pada tag kuncinya?',
            ],
            [
                'user_id' => $users[4 % $users->count()]->id,
                'title' => 'KTM Mahasiswa Sistem Informasi 2025',
                'type' => Item::TYPE_FOUND,
                'category' => 'Dokumen & KTM',
                'location' => 'Kantin',
                'description' => 'Ditemukan kartu tanda mahasiswa di meja kantin dekat penjual soto. Diamankan sementara di ruang BEM FIK.',
                'status' => Item::STATUS_OPEN,
                'event_at' => now()->subDays(3),
                'expires_at' => now()->addDays(27),
                'verification_question' => 'Sebutkan 4 digit terakhir NIM yang tertera pada KTM?',
            ],
            [
                'user_id' => $users[5 % $users->count()]->id,
                'title' => 'Earphone TWS Putih Baseus',
                'type' => Item::TYPE_FOUND,
                'category' => 'Elektronik',
                'location' => 'Masjid',
                'description' => 'Casing TWS warna putih dengan goresan halus di tutupnya. Sudah berhasil dikembalikan kepada pemiliknya.',
                'status' => Item::STATUS_RESOLVED,
                'event_at' => now()->subDays(6),
                'expires_at' => now()->addDays(24),
                'verification_question' => null,
            ],
        ];

        foreach ($items as $data) {
            Item::firstOrCreate(
                ['title' => $data['title'], 'user_id' => $data['user_id']],
                $data
            );
        }
    }
}
