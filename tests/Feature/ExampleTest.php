<?php

test('the application redirects to the medicine dashboard', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('obat-anak.index'));
});
