<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'answer' => ['required', 'string', 'min:2', 'max:1000'],
            'proof_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'answer.required' => 'Jawaban verifikasi wajib diisi.',
            'answer.min' => 'Jawaban verifikasi minimal 2 karakter.',
            'proof_image.image' => 'Bukti harus berupa gambar.',
            'proof_image.mimes' => 'Format gambar bukti harus jpg, jpeg, png, atau webp.',
            'proof_image.max' => 'Ukuran gambar bukti maksimal 2 MB.',
        ];
    }
}
