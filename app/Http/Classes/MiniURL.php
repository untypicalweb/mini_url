<?php

namespace App\Http\Classes;

use App\Http\Classes\JsonBin;
use Illuminate\Support\Facades\DB;
use stdClass;

class MiniURL
{

    private $jsonBin, $passed, $url, $short_url, $miniUrl;

    public function __construct()
    {
        $this->miniUrl = "https://miniurl.untypical.co.uk/";
        $this->jsonBin = new JsonBin();
    }

    /**
     * Shortens URL
     * @returns string $url shortened URL
     */
    public function short(): MiniURL
    {
        $this->short_url = substr(uniqid(), 0, 6);
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
        try {
            $data = [
                'unique_id' => $this->short_url,
                'short_url' => $this->miniUrl . $this->short_url,
                'url' => $this->url
            ];
            DB::table('short_urls')->insert($data);
            return ['status' => true, 'data' => $data];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }


}
