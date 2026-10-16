@extends('surat-rekomendasi.layout')

@section('title', 'Daftar Surat')

@section('content')
    <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Daftar Surat Rekomendasi</h1>
            <p class="mt-1 text-sm text-slate-500">Surat rekomendasi terkait upgrade atau pergantian perangkat IT</p>
        </div>
        <a href="{{ route('surat.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Surat Baru
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th scope="col" class="px-4 py-3">Nomor Surat</th>
                    <th scope="col" class="px-4 py-3">Tanggal</th>
                    <th scope="col" class="px-4 py-3">Pemohon / Departemen</th>
                    <th scope="col" class="px-4 py-3">Perangkat</th>
                    <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($suratList as $surat)
                    <tr class="align-top hover:bg-slate-50">
                        <td class="whitespace-nowrap px-4 py-3 font-semibold text-slate-900">{{ $surat->nomor_surat }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-800">{{ $surat->nama_pengguna }}</div>
                            <div class="text-xs text-slate-500">{{ $surat->nama_departemen }} · {{ $surat->nama_perusahaan }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-slate-800">{{ $surat->jenis_perangkat }}</div>
                            <div class="text-xs text-slate-500">{{ $surat->merk_perangkat }} {{ $surat->model_perangkat !== '-' ? $surat->model_perangkat : '' }}</div>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <a href="{{ route('surat.pdf', $surat) }}"
                               class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200 transition hover:bg-emerald-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                Download PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada surat rekomendasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $suratList->links() }}
    </div>
@endsection
