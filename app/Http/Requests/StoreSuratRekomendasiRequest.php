<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSuratRekomendasiRequest extends FormRequest
{
    /**
     * Form publik: siapa pun boleh mengajukan surat (dibatasi throttle di route).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'nomor_surat' => ['required', 'string', 'max:255', 'unique:surat_rekomendasi,nomor_surat'],
            'petugas_id' => ['required', 'integer', 'exists:petugas,id'],
            'perusahaan_id' => ['required', 'integer', 'exists:perusahaan,id'],
            // Departemen wajib milik perusahaan yang dipilih
            'departemen_id' => [
                'required',
                'integer',
                Rule::exists('departemen', 'id')->where('perusahaan_id', $this->integer('perusahaan_id')),
            ],
            'nama_pengguna' => ['required', 'string', 'max:255'],
            'jenis_perangkat' => ['required', 'string', 'max:255'],
            'merk_perangkat' => ['required', 'string', 'max:255'],
            'model_perangkat' => ['nullable', 'string', 'max:255'],
            'spesifikasi_perangkat' => ['nullable', 'string', 'max:5000'],
            'keterangan_rekomendasi' => ['required', 'string', 'max:5000'],
            'dokumentasi_perangkat' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nomor_surat' => 'Nomor Surat',
            'petugas_id' => 'Nama Petugas',
            'perusahaan_id' => 'Perusahaan',
            'departemen_id' => 'Instalasi / Departemen',
            'nama_pengguna' => 'Nama Pengguna',
            'jenis_perangkat' => 'Jenis Perangkat',
            'merk_perangkat' => 'Merk Perangkat',
            'model_perangkat' => 'Model Perangkat',
            'spesifikasi_perangkat' => 'Spesifikasi Perangkat',
            'keterangan_rekomendasi' => 'Keterangan Rekomendasi',
            'dokumentasi_perangkat' => 'Dokumentasi Perangkat',
        ];
    }
}
