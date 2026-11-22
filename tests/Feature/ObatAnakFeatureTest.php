<?php

use App\Models\ObatAnak;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the medicine inventory', function () {
    $response = $this->get(route('obat-anak.index'));

    $response->assertOk();
    $response->assertSee('Pencatatan Obat Anak');
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
});

it('rejects a medicine with missing required fields', function () {
    $response = $this->post(route('obat-anak.store'), []);

    $response->assertSessionHasErrors(['nama_obat', 'kategori', 'bentuk', 'stok', 'satuan']);
});

it('updates a child medicine', function () {
    $obat = ObatAnak::factory()->create();

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
});

it('deletes a child medicine', function () {
    $obat = ObatAnak::factory()->create();

    $response = $this->delete(route('obat-anak.destroy', $obat));

    $response->assertRedirect(route('obat-anak.index'));
    $this->assertDatabaseMissing('obat_anaks', ['id' => $obat->id]);
});
