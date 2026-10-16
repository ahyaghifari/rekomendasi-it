<?php

namespace Tests\Feature;

use App\Models\Departemen;
use App\Models\Perusahaan;
use App\Models\Petugas;
use App\Models\SuratRekomendasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuratRekomendasiPublicTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function validPayload(Departemen $departemen): array
    {
        return [
            'nomor_surat' => '010/IT-SGG/X/2026',
            'petugas_id' => Petugas::factory()->create()->id,
            'perusahaan_id' => $departemen->perusahaan_id,
            'departemen_id' => $departemen->id,
            'nama_pengguna' => 'Siti Aminah',
            'jenis_perangkat' => 'Laptop',
            'merk_perangkat' => 'Lenovo',
            'keterangan_rekomendasi' => 'Perangkat lama rusak dan perlu diganti.',
        ];
    }

    public function test_guest_can_see_list_with_pdf_button_only(): void
    {
        $surat = SuratRekomendasi::factory()->create(['nomor_surat' => '001/IT-SGG/X/2026']);

        $this->get(route('surat.index'))
            ->assertOk()
            ->assertSee('001/IT-SGG/X/2026')
            ->assertSee(route('surat.pdf', $surat))
            ->assertDontSee('/edit');
    }

    public function test_public_and_panel_urls_share_surat_rekomendasi_prefix(): void
    {
        $this->get('/surat-rekomendasi')->assertOk()->assertSee('Daftar Surat Rekomendasi');
        $this->get('/surat-rekomendasi/buat')->assertOk();
        $this->get('/surat-rekomendasi/login')->assertOk();
        $this->get('/surat-rekomendasi/surat')->assertRedirect('/surat-rekomendasi/login');
    }

    public function test_guest_can_open_create_form_without_signatory_fields(): void
    {
        $this->get(route('surat.create'))
            ->assertOk()
            ->assertSee('name="petugas_id"', false)
            ->assertDontSee('nama_penandatangan')
            ->assertDontSee('nip_penandatangan');
    }

    public function test_store_saves_letter_with_upload_and_backend_signatory(): void
    {
        Storage::fake('public');
        $departemen = Departemen::factory()->create();

        $this->post(route('surat.store'), [
            ...$this->validPayload($departemen),
            'dokumentasi_perangkat' => UploadedFile::fake()->image('foto.jpg'),
            // Diabaikan: penandatangan hanya diatur backend
            'nama_penandatangan' => 'Orang Lain',
        ])->assertRedirect(route('surat.index'))
            ->assertSessionHas('success');

        $surat = SuratRekomendasi::firstOrFail();
        $this->assertSame('Lutfi Salisa Setiawati, S.Kom', $surat->nama_penandatangan);
        $this->assertSame('01122016017', $surat->nip_penandatangan);
        $this->assertSame($departemen->nama, $surat->nama_departemen);
        Storage::disk('public')->assertExists($surat->dokumentasi_perangkat);
    }

    public function test_store_rejects_departemen_from_other_perusahaan(): void
    {
        $departemen = Departemen::factory()->create();

        $this->post(route('surat.store'), [
            ...$this->validPayload($departemen),
            'perusahaan_id' => Perusahaan::factory()->create()->id,
        ])->assertSessionHasErrors('departemen_id');

        $this->assertDatabaseCount('surat_rekomendasi', 0);
    }

    public function test_store_rejects_non_image_upload(): void
    {
        $departemen = Departemen::factory()->create();

        $this->post(route('surat.store'), [
            ...$this->validPayload($departemen),
            'dokumentasi_perangkat' => UploadedFile::fake()->create('script.php', 10, 'text/x-php'),
        ])->assertSessionHasErrors('dokumentasi_perangkat');

        $this->assertDatabaseCount('surat_rekomendasi', 0);
    }

    public function test_departemen_endpoint_returns_only_departemen_of_perusahaan(): void
    {
        $departemen = Departemen::factory()->create();
        Departemen::factory()->create();

        $this->getJson(route('surat.departemen', $departemen->perusahaan_id))
            ->assertOk()
            ->assertExactJson([['id' => $departemen->id, 'nama' => $departemen->nama]]);
    }

    public function test_guest_can_download_pdf(): void
    {
        $surat = SuratRekomendasi::factory()->create(['nomor_surat' => '003/IT-SGG/X/2026']);

        $this->get(route('surat.pdf', $surat))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertDownload('surat-rekomendasi-003-it-sgg-x-2026.pdf');
    }
}
