<?php

namespace App\Http\Requests;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class ItemFormRequest extends FormRequest
{
    /**
     * Shared validation rules for creating and updating an item report.
     *
     * @return array<string, mixed>
     */
    protected function itemRules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'type' => ['required', 'string', Rule::in(Item::TYPES)],
            'category' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'event_at' => ['nullable', 'date', 'before_or_equal:now'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'verification_question' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Nama barang wajib diisi.',
            'title.min' => 'Nama barang minimal 3 karakter.',
            'type.required' => 'Jenis laporan (hilang/ditemukan) wajib dipilih.',
            'type.in' => 'Jenis laporan harus lost atau found.',
            'category.required' => 'Kategori barang wajib diisi.',
            'location.required' => 'Lokasi kejadian wajib diisi.',
            'description.required' => 'Deskripsi barang wajib diisi.',
            'description.min' => 'Deskripsi barang minimal 10 karakter.',
            'event_at.date' => 'Format tanggal kejadian tidak valid.',
            'event_at.before_or_equal' => 'Tanggal kejadian tidak boleh di masa depan.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.',
        ];
    }
}
