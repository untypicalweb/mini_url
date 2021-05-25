<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Classes\MiniURL;

class MiniURLTest extends TestCase
{

    /** @test */
    /*
    * Feature Test for MiniURL->verifyUrl
    * Expected true
    */
    public function verifyUrlTestIsTrue()
    {
        $response = (new MiniURL())->verifyURL("https://www.google.com");
        $this->assertEquals(true, $response);
    }

    /** @test */
    /*
    * Feature Test for MiniURL->verifyUrl
    * Expected false
    */
    public function verifyUrlTestIsFalse()
    {
        $response = (new MiniURL())->verifyURL("google.com");
        $this->assertEquals(false, $response);
    }

    /** @test */
    /*
    * Feature Test for MiniURL->verifyUrlResponse
    * Expected true
    */
    public function verifyUrlResponseIsTrue()
    {
        $response = (new MiniURL())->verifyUrlResponse("https://www.google.com");
        $this->assertEquals(true, $response);
    }

    /** @test */
    /*
    * Feature Test for MiniURL->verifyUrlResponse
    * Expected false
    */
    public function verifyUrlResponseIsFalse()
    {
        $response = (new MiniURL())->verifyUrlResponse("https://www.untypical.com/page-doesnt-exist");
        $this->assertEquals(false, $response);
    }

    /** @test */
    /*
    * Feature Test for MiniURL->setData
    * Expected true
    */
    public function storeUrlReturnsTrue()
    {
        $miniURL = new MiniURL();
        $response = $miniURL->setData(['url' => "https://www.google.com"])->store();
        $this->assertEquals(true, $response['status']);
    }

    /** @test */
    /*
    * Feature Test for MiniURL->find
    * Expected true
    */
    public function validShortLinkReturnsTrue()
    {
        $response = (new MiniURL())->find("https://short.link/bbd125");
        $this->assertEquals(true, $response['status']);
    }

    /** @test */
    /*
    * Feature Test for MiniURL->find
    * Expected false
    */
    public function expiredLinkReturnsFalse()
    {
        $response = (new MiniURL())->find("https://short.link/7e2b62");
        $this->assertEquals(false, $response['status']);
    }
}
