<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('simple test', function () {
    expect(true)->toBeTrue();
});
