<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Classes\MiniURL;

class MiniURLTest extends TestCase
{
    public function verifyURLTest()
    {
        $response = (new MiniURL()->verifyURL("http://www.google.com"));
        $this->assertEquals(true, $response->status);
    }
}
