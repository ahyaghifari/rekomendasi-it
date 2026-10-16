<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuratRekomendasiRequest;
use App\Models\Departemen;
use App\Models\Perusahaan;
use App\Models\Petugas;
use App\Models\SuratRekomendasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Halaman publik Surat Rekomendasi IT: hanya daftar, buat baru, dan unduh PDF
 * (ubah/hapus tetap lewat panel Filament /surat-rekomendasi/surat).
 */
class SuratRekomendasiController extends Controller
{
    public function index(): View
    {
        $suratList = SuratRekomendasi::query()
            ->latest('tanggal_surat')
            ->latest('id')
            ->paginate(15);

        return view('surat-rekomendasi.index', compact('suratList'));
    }

    public function create(): View
    {
        return view('surat-rekomendasi.create', [
            'petugasList' => Petugas::orderBy('nama')->get(['id', 'nama', 'jabatan']),
            'perusahaanList' => Perusahaan::orderBy('nama')->get(['id', 'nama']),
            // Untuk mengisi ulang pilihan departemen saat validasi gagal
            'departemenList' => Departemen::where('perusahaan_id', old('perusahaan_id'))->orderBy('nama')->get(['id', 'nama']),
        ]);
    }

    public function store(StoreSuratRekomendasiRequest $request): RedirectResponse
    {
        $surat = new SuratRekomendasi($request->safe()->except('dokumentasi_perangkat'));

        if ($request->hasFile('dokumentasi_perangkat')) {
            $surat->dokumentasi_perangkat = $request->file('dokumentasi_perangkat')->store('surat-rekomendasi', 'public');
        }

        // Penandatangan selalu dari backend, tidak bisa diisi lewat form publik
        $surat->nama_penandatangan = 'Lutfi Salisa Setiawati, S.Kom';
        $surat->nip_penandatangan = '01122016017';
        $surat->save();

        return redirect()
            ->route('surat.index')
            ->with('success', "Surat {$surat->nomor_surat} berhasil dibuat.");
    }

    public function downloadPdf(SuratRekomendasi $surat): StreamedResponse
    {
        return $surat->downloadPdf();
    }

    // Dropdown bertingkat: daftar departemen milik satu perusahaan
    public function getDepartemenByPerusahaan(Perusahaan $perusahaan): JsonResponse
    {
        return response()->json(
            $perusahaan->departemen()->orderBy('nama')->get(['id', 'nama'])
        );
    }
}
