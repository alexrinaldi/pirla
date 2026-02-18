<?php

it('can access the application', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
})->group('feature');
