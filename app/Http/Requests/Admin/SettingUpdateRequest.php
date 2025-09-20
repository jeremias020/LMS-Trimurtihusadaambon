<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'site_name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (trim($value) === '') {
                        $fail('Nama situs tidak boleh kosong atau hanya berisi spasi.');
                    }
                }
            ],
            'contact_email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    $disposableDomains = [
                        'tempmail.com', '10minutemail.com', 'guerrillamail.com',
                        'mailinator.com', 'yopmail.com', 'fakeinbox.com'
                    ];
                    $domain = substr(strrchr($value, "@"), 1);
                    if (in_array($domain, $disposableDomains)) {
                        $fail('Email tidak boleh menggunakan domain sementara.');
                    }
                }
            ],
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'about' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'site_name.required' => 'Nama situs wajib diisi.',
            'site_name.string' => 'Nama situs harus berupa teks.',
            'site_name.max' => 'Nama situs tidak boleh lebih dari 255 karakter.',

            'contact_email.required' => 'Email kontak wajib diisi.',
            'contact_email.email' => 'Format email kontak tidak valid.',
            'contact_email.max' => 'Email kontak tidak boleh lebih dari 255 karakter.',

            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.string' => 'Nomor telepon harus berupa teks.',
            'phone_number.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',

            'address.string' => 'Alamat harus berupa teks.',
            'address.max' => 'Alamat tidak boleh lebih dari 500 karakter.',

            'about.string' => 'Tentang harus berupa teks.',
            'about.max' => 'Tentang tidak boleh lebih dari 1000 karakter.',

            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus dalam format: jpeg, png, jpg, gif, svg, webp.',
            'logo.max' => 'Ukuran logo tidak boleh lebih dari 2MB (2048 KB).',

            'favicon.image' => 'Favicon harus berupa gambar.',
            'favicon.mimes' => 'Favicon harus dalam format: ico, png.',
            'favicon.max' => 'Ukuran favicon tidak boleh lebih dari 1MB (1024 KB).',

            'facebook_url.url' => 'URL Facebook harus valid.',
            'facebook_url.max' => 'URL Facebook tidak boleh lebih dari 255 karakter.',

            'twitter_url.url' => 'URL Twitter harus valid.',
            'twitter_url.max' => 'URL Twitter tidak boleh lebih dari 255 karakter.',

            'instagram_url.url' => 'URL Instagram harus valid.',
            'instagram_url.max' => 'URL Instagram tidak boleh lebih dari 255 karakter.',

            'youtube_url.url' => 'URL YouTube harus valid.',
            'youtube_url.max' => 'URL YouTube tidak boleh lebih dari 255 karakter.',

            'meta_title.string' => 'Meta title harus berupa teks.',
            'meta_title.max' => 'Meta title tidak boleh lebih dari 255 karakter.',

            'meta_description.string' => 'Meta description harus berupa teks.',
            'meta_description.max' => 'Meta description tidak boleh lebih dari 500 karakter.',

            'meta_keywords.string' => 'Meta keywords harus berupa teks.',
            'meta_keywords.max' => 'Meta keywords tidak boleh lebih dari 500 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'site_name' => 'nama situs',
            'contact_email' => 'email kontak',
            'phone_number' => 'nomor telepon',
            'address' => 'alamat',
            'about' => 'tentang',
            'logo' => 'logo',
            'favicon' => 'favicon',
            'facebook_url' => 'URL Facebook',
            'twitter_url' => 'URL Twitter',
            'instagram_url' => 'URL Instagram',
            'youtube_url' => 'URL YouTube',
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
            'meta_keywords' => 'meta keywords',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Bersihkan nomor telepon
        if ($this->has('phone_number')) {
            $this->merge([
                'phone_number' => preg_replace('/[^0-9]/', '', $this->phone_number),
            ]);
        }

        // Sanitasi URL
        $urlFields = ['facebook_url', 'twitter_url', 'instagram_url', 'youtube_url'];
        foreach ($urlFields as $field) {
            if ($this->has($field) && !empty($this->$field)) {
                $this->merge([$field => rtrim($this->$field, '/')]);
            }
        }

        // Set nilai null untuk field yang kosong
        $nullableFields = [
            'address', 'about', 'facebook_url', 'twitter_url',
            'instagram_url', 'youtube_url', 'meta_title',
            'meta_description', 'meta_keywords'
        ];

        foreach ($nullableFields as $field) {
            if ($this->has($field) && empty($this->$field)) {
                $this->merge([$field => null]);
            }
        }
    }

    /**
     * Get the validated data from the request.
     */
    public function getValidatedData(): array
    {
        $validated = $this->validated();

        // Hapus file fields dari data yang akan disimpan
        unset($validated['logo'], $validated['favicon']);

        return $validated;
    }

    /**
     * Get logo file if exists.
     */
    public function getLogo()
    {
        return $this->file('logo');
    }

    /**
     * Get favicon file if exists.
     */
    public function getFavicon()
    {
        return $this->file('favicon');
    }
}