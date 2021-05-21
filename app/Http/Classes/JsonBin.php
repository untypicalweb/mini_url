<?php

namespace App\Http\Classes;

use Illuminate\Support\Facades\Http;

class JsonBin
{

    private $base_url, $collection, $key;

    public function __construct()
    {
        $this->base_url = "https://api.jsonbin.io/v3/b";
        $this->key = env('JSON_BIN_KEY');
        $this->collection = env('JSON_BIN_STORE');
    }

    public function store(array $data): array
    {
        return Http::withHeaders([
            'X-Master-Key' => $this->key,
            'Content-Type' => "application/json",
            'X-Collection-Id' => $this->collection,
            'X-Bin-Name' => uniqid(),
            'X-Bin-Private' => true
        ])->post($this->base_url, [
            $data
        ])->json();
    }

    public function get()
    {

    }

    public function delete()
    {

    }

}
