<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows visitors to use the medicine management pages without logging in', function () {
    foreach ([
        route('obat-anak.index'),
        route('kategori-obat.index'),
        route('stok-obat.index'),
        route('obat-anak.kedaluwarsa'),
        route('obat-anak.laporan'),
        route('pengaturan.index'),
    ] as $url) {
        $this->get($url)->assertOk();
    }
});

it('does not expose login or logout endpoints', function () {
    $this->get('/login')->assertNotFound();
    $this->post('/login')->assertNotFound();
    $this->post('/logout')->assertNotFound();
});
