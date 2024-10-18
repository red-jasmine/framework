<?php


test('example', function () {
    
    $this->get('/')->assertOk();
    expect(true)->toBeTrue();
});
