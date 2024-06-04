<?php

use App\Models\User;

it('returns a successful response', function () {
    $user = User::factory()->ready()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200);
});
