<?php

namespace App\Http\Classes;

use App\Http\Classes\JsonBin;
use stdClass;

class MiniURL
{

    private $jsonBin, $passed, $url;

    public function __construct()
    {
        $this->jsonBin = new JsonBin();
    }

    /**
     * Shortens URL
     * @returns string $url shortened URL
     */
    public function short(): MiniURL
    {
        return $this;
    }

    /**
     * Validation for URL
     * @params string $url URL passed for verification
     * @return MiniURL $passed
     */
    public function validation(string $url): MiniURL
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Verify URL response
     * @returns bool $passed
     */
    public function verifyURLResponse(): MiniURL
    {
        return $this;
    }

    /**
     * Store Short URL in JSON BIN
     * @params string $url string to check response
     * @returns bool $response
     */
    public function store(): array
    {
        return $this->jsonBin->store(['url' => $this->url]);
    }


}
