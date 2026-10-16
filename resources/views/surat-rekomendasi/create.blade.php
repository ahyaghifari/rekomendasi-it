@extends('surat-rekomendasi.layout')

@section('title', 'Buat Surat')

@php
    $labelClass = 'mb-1.5 block text-sm font-semibold text-slate-700';
    $inputClass = 'block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 disabled:bg-slate-100 disabled:text-slate-400';
    $errorClass = 'mt-1 text-xs text-red-600';
@endphp

@section('content')
    <div class="mx-auto max-w-4xl">
        <a href="{{ route('surat.index') }}" class="mb-4 inline-flex items-center gap-1 text-sm font-medium text-slate-500 hover:text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            Kembali ke daftar
        </a>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">SURAT REKOMENDASI IT</h1>
            <p class="mt-1 text-sm text-slate-500">Surat rekomendasi terkait upgrade atau pergantian perangkat</p>

            @if($errors->any())
                <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
                    Mohon periksa kembali isian yang ditandai merah.
                </div>
            @endif

            <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data" class="mt-6">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="nomor_surat" class="{{ $labelClass }}">Nomor Surat</label>
                        <input type="text" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat') }}" required maxlength="255"
                               placeholder="Contoh: 001/IT-SGG/X/2026" class="{{ $inputClass }}">
                        @error('nomor_surat') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>

                    {{-- Petugas & jabatan (jabatan terisi otomatis dari data petugas) --}}
                    <div>
                        <label for="petugas_id" class="{{ $labelClass }}">Nama Petugas</label>
                        <select id="petugas_id" name="petugas_id" required class="{{ $inputClass }}">
                            <option value="">Pilih petugas</option>
                            @foreach($petugasList as $petugas)
                                <option value="{{ $petugas->id }}" data-jabatan="{{ $petugas->jabatan }}" @selected(old('petugas_id') == $petugas->id)>{{ $petugas->nama }}</option>
                            @endforeach
                        </select>
                        @error('petugas_id') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="jabatan_petugas" class="{{ $labelClass }}">Jabatan</label>
                        <input type="text" id="jabatan_petugas" readonly tabindex="-1"
                               placeholder="Terisi otomatis setelah memilih petugas" class="{{ $inputClass }} bg-slate-50">
                    </div>

                    {{-- Perusahaan & departemen (departemen tersaring sesuai perusahaan) --}}
                    <div>
                        <label for="perusahaan_id" class="{{ $labelClass }}">Perusahaan</label>
                        <select id="perusahaan_id" name="perusahaan_id" required class="{{ $inputClass }}"
                                data-departemen-url="{{ url('api/perusahaan/__ID__/departemen') }}">
                            <option value="">Pilih perusahaan</option>
                            @foreach($perusahaanList as $perusahaan)
                                <option value="{{ $perusahaan->id }}" @selected(old('perusahaan_id') == $perusahaan->id)>{{ $perusahaan->nama }}</option>
                            @endforeach
                        </select>
                        @error('perusahaan_id') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="departemen_id" class="{{ $labelClass }}">Instalasi / Departemen</label>
                        <select id="departemen_id" name="departemen_id" required class="{{ $inputClass }}" @disabled($departemenList->isEmpty())>
                            <option value="">{{ $departemenList->isEmpty() ? 'Pilih perusahaan terlebih dahulu' : 'Pilih departemen' }}</option>
                            @foreach($departemenList as $departemen)
                                <option value="{{ $departemen->id }}" @selected(old('departemen_id') == $departemen->id)>{{ $departemen->nama }}</option>
                            @endforeach
                        </select>
                        @error('departemen_id') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="nama_pengguna" class="{{ $labelClass }}">Nama Pengguna</label>
                        <input type="text" id="nama_pengguna" name="nama_pengguna" value="{{ old('nama_pengguna') }}" required maxlength="255" class="{{ $inputClass }}">
                        @error('nama_pengguna') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="jenis_perangkat" class="{{ $labelClass }}">Jenis Perangkat</label>
                        <input type="text" id="jenis_perangkat" name="jenis_perangkat" value="{{ old('jenis_perangkat') }}" required maxlength="255"
                               placeholder="Contoh: Laptop" class="{{ $inputClass }}">
                        @error('jenis_perangkat') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="merk_perangkat" class="{{ $labelClass }}">Merk Perangkat</label>
                        <input type="text" id="merk_perangkat" name="merk_perangkat" value="{{ old('merk_perangkat') }}" required maxlength="255" class="{{ $inputClass }}">
                        @error('merk_perangkat') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="model_perangkat" class="{{ $labelClass }}">Model Perangkat <span class="font-normal text-slate-400">(opsional)</span></label>
                        <input type="text" id="model_perangkat" name="model_perangkat" value="{{ old('model_perangkat') }}" maxlength="255" class="{{ $inputClass }}">
                        @error('model_perangkat') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label for="spesifikasi_perangkat" class="{{ $labelClass }}">Spesifikasi Perangkat <span class="font-normal text-slate-400">(opsional)</span></label>
                        <textarea id="spesifikasi_perangkat" name="spesifikasi_perangkat" rows="3" class="{{ $inputClass }}">{{ old('spesifikasi_perangkat') }}</textarea>
                        @error('spesifikasi_perangkat') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label for="keterangan_rekomendasi" class="{{ $labelClass }}">Keterangan Rekomendasi</label>
                        <textarea id="keterangan_rekomendasi" name="keterangan_rekomendasi" rows="4" required class="{{ $inputClass }}">{{ old('keterangan_rekomendasi') }}</textarea>
                        @error('keterangan_rekomendasi') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label for="dokumentasi_perangkat" class="{{ $labelClass }}">Dokumentasi Perangkat <span class="font-normal text-slate-400">(opsional, gambar maks. 2 MB)</span></label>
                        <input type="file" id="dokumentasi_perangkat" name="dokumentasi_perangkat" accept="image/*"
                               class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100">
                        @error('dokumentasi_perangkat') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="w-full rounded-xl bg-emerald-600 px-6 py-3 text-center text-base font-medium text-white transition hover:bg-emerald-700 md:w-auto">
                        Simpan Surat
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/surat-rekomendasi.js') }}"></script>
@endpush
