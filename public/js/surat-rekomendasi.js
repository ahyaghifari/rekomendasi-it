// Form publik Surat Rekomendasi IT: jabatan otomatis & dropdown departemen bertingkat

const petugasSelect = document.getElementById('petugas_id');
const jabatanInput = document.getElementById('jabatan_petugas');
const perusahaanSelect = document.getElementById('perusahaan_id');
const departemenSelect = document.getElementById('departemen_id');

// Jabatan diambil dari atribut data-jabatan pada opsi petugas
function syncJabatan() {
    jabatanInput.value = petugasSelect.selectedOptions[0]?.dataset.jabatan || '';
}

function setDepartemenOptions(placeholder, items = []) {
    departemenSelect.innerHTML = '';
    departemenSelect.add(new Option(placeholder, ''));
    items.forEach(d => departemenSelect.add(new Option(d.nama, d.id)));
    departemenSelect.disabled = items.length === 0;
}

// Ambil departemen milik perusahaan terpilih tanpa reload halaman
async function loadDepartemen() {
    const perusahaanId = perusahaanSelect.value;
    if (!perusahaanId) {
        setDepartemenOptions('Pilih perusahaan terlebih dahulu');
        return;
    }

    setDepartemenOptions('Memuat departemen...');
    try {
        const url = perusahaanSelect.dataset.departemenUrl.replace('__ID__', encodeURIComponent(perusahaanId));
        const res = await fetch(url, { headers: { Accept: 'application/json' } });
        if (!res.ok) throw new Error();
        const items = await res.json();
        setDepartemenOptions(items.length ? 'Pilih departemen' : 'Belum ada departemen untuk perusahaan ini', items);
    } catch {
        setDepartemenOptions('Gagal memuat departemen, pilih ulang perusahaan');
    }
}

petugasSelect.addEventListener('change', syncJabatan);
perusahaanSelect.addEventListener('change', loadDepartemen);
syncJabatan(); // Isi ulang jabatan saat form kembali karena validasi gagal
