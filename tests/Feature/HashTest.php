<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HashTest extends TestCase
{
    public function testHash()
    {
        $password = 'rahasia';
        $hashed = Hash::make($password);

        self::assertTrue(Hash::check('rahasia', $hashed));
    }
}
