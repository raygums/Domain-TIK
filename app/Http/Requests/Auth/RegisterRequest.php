<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * RegisterRequest - Validation untuk Registrasi User Mandiri
 * 
 * Purpose:
 * - Memvalidasi input user saat registrasi mandiri
 * - Memastikan data integrity sebelum masuk ke database
 * - Mendukung upload KTP/KTM untuk verifikasi
 * 
 * Security Considerations:
 * - Password harus min 8 karakter dan confirmed
 * - Username dan email harus unique di tabel akun.pengguna
 * - File upload dibatasi hanya image dengan max 2MB
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * Catatan: Registrasi adalah public endpoint, semua orang boleh akses
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * 
     * Penjelasan Rules:
     * - nm: Nama lengkap, max 125 char sesuai skema database
     * - usn: Username, unique check dengan explicit table name (schema.table)
     * - email: Valid email dan unique
     * - kata_sandi: Min 8 char, wajib confirmed (kata_sandi_confirmation)
     * - file_ktp_ktm: Optional file image untuk verifikasi identitas
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nm' => [
                'required',
                'string',
                'max:125',
                'regex:/^[a-zA-Z\s.,\'-]+$/', // Hanya huruf, spasi, dan karakter nama umum
            ],
            
            'peran' => [
                'required',
                'in:mahasiswa,dosen,karyawan',
            ],
            
            'usn' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z0-9._]+$/', // Lowercase, angka, dot, underscore
                function ($attribute, $value, $fail) {
                    if (User::where('usn', $value)->exists()) {
                        $fail('Username sudah digunakan.');
                    }
                },
            ],
            
            'email' => [
                'required',
                'email:rfc,dns',
                'max:125',
                function ($attribute, $value, $fail) {
                    if (User::where('email', $value)->exists()) {
                        $fail('Email sudah digunakan.');
                    }
                },
            ],
            
            'kata_sandi' => [
                'required',
                'confirmed', // Butuh field kata_sandi_confirmation
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            
            'nomor_identitas' => [
                'required',
                'string',
                'max:50', // Support NIK (16), NPM, NIP, NIDN
            ],
            
            'tgl_lahir' => [
                'required',
                'date',
                'before:today',
                'after:1900-01-01',
            ],
            
            'file_ktp_ktm' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048', // Max 2MB
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     * 
     * Purpose: User-friendly field names di error messages
     */
    public function attributes(): array
    {
        return [
            'nm' => 'nama lengkap',
            'peran' => 'peran',
            'usn' => 'username',
            'nomor_identitas' => 'nomor identitas',
            'kata_sandi' => 'password',
            'file_ktp_ktm' => 'file KTP/KTM',
            'tgl_lahir' => 'tanggal lahir',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nm.regex' => 'Nama hanya boleh mengandung huruf, spasi, dan tanda baca umum.',
            'peran.required' => 'Silakan pilih peran Anda.',
            'peran.in' => 'Peran yang dipilih tidak valid.',
            'usn.regex' => 'Username hanya boleh mengandung huruf kecil, angka, titik, dan underscore.',
            'usn.unique' => 'Username sudah digunakan oleh pengguna lain.',
            'email.unique' => 'Email sudah terdaftar dalam sistem.',
            'kata_sandi.confirmed' => 'Konfirmasi password tidak cocok.',
            'file_ktp_ktm.max' => 'Ukuran file maksimal 2MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     * 
     * Purpose: Normalize data sebelum validasi
     * - Lowercase username untuk konsistensi
     * - Trim whitespace di nama
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'usn' => strtolower($this->usn ?? ''),
            'nm' => trim($this->nm ?? ''),
        ]);
    }
}
