<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed dummy UPNVJ student and lecturer accounts for development and frontend testing.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Kenny Tanna Haholongan Pandia',
                'nim' => '2510512027',
                'email' => '2510512027@mahasiswa.upnvj.ac.id',
            ],
            [
                'name' => 'Muhammad Naufal Ghani',
                'nim' => '2510512072',
                'email' => '2510512072@mahasiswa.upnvj.ac.id',
            ],
            [
                'name' => 'Firyal Humaira Asyaffa',
                'nim' => '2510512082',
                'email' => '2510512082@mahasiswa.upnvj.ac.id',
            ],
            [
                'name' => 'Kenzia Zahwa Eddyputri',
                'nim' => '2510512121',
                'email' => '2510512121@mahasiswa.upnvj.ac.id',
            ],
            [
                'name' => 'Tracy Gabriella Donda Sitorus',
                'nim' => '2510512122',
                'email' => '2510512122@mahasiswa.upnvj.ac.id',
            ],
            [
                'name' => 'Vrista Darin Nadira',
                'nim' => '2510512119',
                'email' => '2510512119@mahasiswa.upnvj.ac.id',
            ],
            [
                'name' => 'Dosen FIK UPNVJ',
                'nim' => null,
                'email' => 'dosen@dosen.upnvj.ac.id',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'nim' => $data['nim'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
