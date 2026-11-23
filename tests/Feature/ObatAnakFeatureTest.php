<?php

use App\Models\AppSetting;
use App\Models\Category;
use App\Models\ObatAnak;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the medicine inventory', function () {
    $response = $this->get(route('obat-anak.index'));

    $response->assertOk();
    $response->assertSee('Pencatatan Obat Anak');
});

it('shows all requested medicine details in the dashboard inventory', function () {
    ObatAnak::factory()->create([
        'nama_obat' => 'Paracetamol Anak',
        'kategori' => 'Demam',
        'bentuk' => 'Sirup',
        'dosis' => '120 mg/5 ml',
        'stok' => 12,
        'satuan' => 'botol',
        'tanggal_kedaluwarsa' => '2027-12-31',
    ]);

    $response = $this->get(route('obat-anak.index'));

    $response->assertSeeText([
        'Kategori',
        'Bentuk',
        'Dosis/Konsentrasi',
        'Jumlah Stok',
        'Satuan',
        'Kedaluwarsa',
        'Demam',
        'Sirup',
        '120 mg/5 ml',
        'botol',
        '31 Dec 2027',
    ]);
});

it('prints a complete medicine report including medicines beyond the dashboard page', function () {
    ObatAnak::factory()->count(9)->create();
    $category = Category::factory()->create(['nama' => 'Batuk/Pilek']);
    ObatAnak::factory()->create([
        'nama_obat' => 'Obat di Luar Halaman',
        'category_id' => $category->id,
        'kategori' => $category->nama,
        'bentuk' => 'Sirup',
        'dosis' => '60 mg/5 ml',
        'stok' => 3,
        'satuan' => 'botol',
        'tanggal_kedaluwarsa' => '2028-01-15',
    ]);

    $response = $this->get(route('obat-anak.laporan'));

    $response->assertSeeText([
        'Laporan Data Obat',
        'Nama Obat',
        'Kategori',
        'Bentuk',
        'Dosis/Konsentrasi',
        'Jumlah Stok',
        'Satuan',
        'Tanggal Kedaluwarsa',
        'Obat di Luar Halaman',
        'Batuk/Pilek',
        '60 mg/5 ml',
        'botol',
        '15 Jan 2028',
        'Cetak Laporan',
    ]);
});

it('shows inventory, stock and expiry summaries on the dashboard', function () {
    $this->travelTo(now()->setDate(2026, 11, 23)->startOfDay());
    $category = Category::query()->where('nama', 'Obat Demam')->firstOrFail();
    ObatAnak::factory()->create(['category_id' => $category->id, 'kategori' => $category->nama, 'stok' => 3, 'stok_minimum' => 5, 'tanggal_kedaluwarsa' => '2026-12-01']);
    ObatAnak::factory()->create(['category_id' => $category->id, 'kategori' => $category->nama, 'stok' => 12, 'stok_minimum' => 5, 'tanggal_kedaluwarsa' => '2026-11-20']);

    $response = $this->get(route('obat-anak.index'));

    $response->assertSeeText([
        'Total Jenis Obat', 'Total Stok Tersedia', 'Obat Hampir Habis',
        'Segera Kedaluwarsa (30 Hari)', 'Obat Kedaluwarsa', 'Jumlah Jenis Obat per Kategori',
        'Jumlah Stok per Kategori', 'Obat Demam',
    ]);
    expect($response->getContent())
        ->toContain('>2<')
        ->toContain('>15<')
        ->toContain('>1<');
});

it('filters medicine inventory by category and shape and sorts by expiry date', function () {
    $category = Category::query()->where('nama', 'Obat Alergi')->firstOrFail();
    ObatAnak::factory()->create(['nama_obat' => 'Tablet Lama', 'category_id' => $category->id, 'kategori' => $category->nama, 'bentuk' => 'Tablet', 'tanggal_kedaluwarsa' => '2027-01-01']);
    ObatAnak::factory()->create(['nama_obat' => 'Sirup Baru', 'category_id' => $category->id, 'kategori' => $category->nama, 'bentuk' => 'Sirup', 'tanggal_kedaluwarsa' => '2027-02-01']);
    ObatAnak::factory()->create(['nama_obat' => 'Obat Tidak Cocok', 'bentuk' => 'Tablet']);

    $response = $this->get(route('obat-anak.index', [
        'kategori' => $category->id,
        'bentuk' => 'Tablet',
        'urut' => 'kedaluwarsa',
    ]));

    $response->assertSeeText('Tablet Lama');
    $response->assertViewHas('obatAnak', fn ($medicines) => $medicines->getCollection()->pluck('nama_obat')->all() === ['Tablet Lama']);
});

it('creates and updates categories and prevents deleting a category used by medicines', function () {
    $response = $this->post(route('kategori-obat.store'), ['nama' => 'Obat Mata', 'keterangan' => 'Kategori tambahan']);
    $response->assertRedirect(route('kategori-obat.index'));
    $category = Category::query()->where('nama', 'Obat Mata')->firstOrFail();
    $this->put(route('kategori-obat.update', $category), ['nama' => 'Obat Mata Anak', 'keterangan' => 'Kategori diperbarui'])
        ->assertRedirect(route('kategori-obat.index'));
    ObatAnak::factory()->create(['category_id' => $category->id]);

    $this->delete(route('kategori-obat.destroy', $category))
        ->assertSessionHas('error', 'Kategori masih digunakan obat dan tidak dapat dihapus.');
    expect($category->fresh()->nama)->toBe('Obat Mata Anak');
});

it('changes medicine stock and records its history', function () {
    $medicine = ObatAnak::factory()->create(['stok' => 8]);

    $response = $this->post(route('obat-anak.stok.update', $medicine), [
        'jenis' => 'keluar',
        'jumlah' => 3,
        'keterangan' => 'Penjualan',
    ]);

    $response->assertRedirect(route('stok-obat.index'));
    expect($medicine->fresh()->stok)->toBe(5);
    $this->assertDatabaseHas('stock_histories', [
        'obat_anak_id' => $medicine->id,
        'jenis' => 'keluar',
        'jumlah' => 3,
        'stok_sebelum' => 8,
        'stok_sesudah' => 5,
    ]);
});

it('rejects stock changes that would make inventory negative', function () {
    $medicine = ObatAnak::factory()->create(['stok' => 2]);

    $this->from(route('stok-obat.index'))
        ->post(route('obat-anak.stok.update', $medicine), ['jenis' => 'keluar', 'jumlah' => 3])
        ->assertSessionHasErrors(['jumlah']);

    expect($medicine->fresh()->stok)->toBe(2);
    $this->assertDatabaseMissing('stock_histories', ['obat_anak_id' => $medicine->id, 'jenis' => 'keluar']);
});

it('lists medicines expiring within thirty days and already expired', function () {
    $this->travelTo(now()->setDate(2026, 11, 23)->startOfDay());
    ObatAnak::factory()->create(['nama_obat' => 'Hampir Kedaluwarsa', 'tanggal_kedaluwarsa' => '2026-12-20']);
    ObatAnak::factory()->create(['nama_obat' => 'Sudah Kedaluwarsa', 'tanggal_kedaluwarsa' => '2026-11-22']);
    ObatAnak::factory()->create(['nama_obat' => 'Masih Aman', 'tanggal_kedaluwarsa' => '2027-01-01']);

    $response = $this->get(route('obat-anak.kedaluwarsa'));

    $response->assertSeeText('Hampir Kedaluwarsa')
        ->assertSeeText('Sudah Kedaluwarsa')
        ->assertDontSeeText('Masih Aman');
});

it('filters and exports reports as an Excel-compatible CSV', function () {
    $category = Category::query()->where('nama', 'Obat Pilek')->firstOrFail();
    ObatAnak::factory()->create(['nama_obat' => 'Stok Rendah', 'category_id' => $category->id, 'kategori' => $category->nama, 'stok' => 2, 'stok_minimum' => 5]);
    ObatAnak::factory()->create(['nama_obat' => 'Stok Habis', 'category_id' => $category->id, 'kategori' => $category->nama, 'stok' => 0, 'stok_minimum' => 5]);
    ObatAnak::factory()->create(['nama_obat' => 'Stok Aman', 'category_id' => $category->id, 'kategori' => $category->nama, 'stok' => 10, 'stok_minimum' => 5]);

    $this->get(route('obat-anak.laporan', ['jenis_laporan' => 'hampir_habis']))
        ->assertSeeText('Stok Rendah')
        ->assertSeeText('Stok Habis')
        ->assertDontSeeText('Stok Aman');

    $this->get(route('obat-anak.laporan.export', ['jenis_laporan' => 'stok', 'category_id' => $category->id]))
        ->assertDownload('laporan-obat-stok-'.now()->format('Ymd-His').'.csv');
});

it('exports formula-like medicine values as plain text in CSV', function () {
    ObatAnak::factory()->create(['nama_obat' => '=HYPERLINK("https://example.test","open")']);

    $response = $this->get(route('obat-anak.laporan.export'));

    $response->assertDownload();
    expect($response->streamedContent())->toContain("'=HYPERLINK");
});

it('limits the expired medicine report to medicines already past their expiry date', function () {
    $this->travelTo(now()->setDate(2026, 11, 23)->startOfDay());
    ObatAnak::factory()->create(['nama_obat' => 'Sudah Lewat', 'tanggal_kedaluwarsa' => '2026-11-22']);
    ObatAnak::factory()->create(['nama_obat' => 'Hampir Lewat', 'tanggal_kedaluwarsa' => '2026-12-01']);

    $this->get(route('obat-anak.laporan', ['jenis_laporan' => 'kedaluwarsa']))
        ->assertSeeText('Sudah Lewat')
        ->assertDontSeeText('Hampir Lewat');
});

it('updates the default minimum stock setting', function () {
    $response = $this->put(route('pengaturan.update'), ['batas_stok_minimum' => 7]);

    $response->assertRedirect(route('pengaturan.index'));
    expect(AppSetting::query()->where('key', 'batas_stok_minimum')->value('value'))->toBe('7');
});

it('creates a child medicine', function () {
    $response = $this->post(route('obat-anak.store'), [
        'nama_obat' => 'Paracetamol Anak',
        'kategori' => 'Demam',
        'bentuk' => 'Sirup',
        'dosis' => '120 mg/5 ml',
        'stok' => 12,
        'satuan' => 'botol',
        'tanggal_kedaluwarsa' => '2027-12-31',
        'keterangan' => 'Obat demam anak',
    ]);

    $response->assertRedirect(route('obat-anak.index'));
    $this->assertDatabaseHas('obat_anaks', [
        'nama_obat' => 'Paracetamol Anak',
        'stok' => 12,
    ]);
    $this->assertDatabaseHas('stock_histories', [
        'jenis' => 'masuk',
        'jumlah' => 12,
        'stok_sebelum' => 0,
        'stok_sesudah' => 12,
    ]);
});

it('rejects a medicine with missing required fields', function () {
    $response = $this->post(route('obat-anak.store'), []);

    $response->assertSessionHasErrors(['nama_obat', 'kategori', 'bentuk', 'stok', 'satuan']);
});

it('displays the expiry date validation message when the date is invalid', function () {
    $response = $this->from(route('obat-anak.create'))->post(route('obat-anak.store'), [
        'nama_obat' => 'Obat Uji',
        'kategori' => 'Demam',
        'bentuk' => 'Sirup',
        'stok' => 1,
        'satuan' => 'botol',
        'tanggal_kedaluwarsa' => 'bukan-tanggal',
    ]);

    $response->assertSessionHasErrors(['tanggal_kedaluwarsa']);
    $this->get(route('obat-anak.create'))
        ->assertSee('The tanggal kedaluwarsa field must be a valid date.');
});

it('updates a child medicine', function () {
    $obat = ObatAnak::factory()->create(['stok' => 8]);

    $response = $this->put(route('obat-anak.update', $obat), [
        'nama_obat' => 'Amoxicillin Kering',
        'kategori' => 'Antiinfeksi',
        'bentuk' => 'Suspensi',
        'dosis' => '125 mg/5 ml',
        'stok' => 20,
        'satuan' => 'botol',
        'tanggal_kedaluwarsa' => '2028-01-01',
        'keterangan' => null,
    ]);

    $response->assertRedirect(route('obat-anak.index'));
    expect($obat->fresh()->nama_obat)->toBe('Amoxicillin Kering')
        ->and($obat->fresh()->stok)->toBe(20);
    $this->assertDatabaseHas('stock_histories', [
        'obat_anak_id' => $obat->id,
        'jenis' => 'masuk',
        'jumlah' => 12,
        'stok_sebelum' => 8,
        'stok_sesudah' => 20,
    ]);
});

it('deletes a child medicine', function () {
    $obat = ObatAnak::factory()->create();

    $response = $this->delete(route('obat-anak.destroy', $obat));

    $response->assertRedirect(route('obat-anak.index'));
    $this->assertDatabaseMissing('obat_anaks', ['id' => $obat->id]);
});
