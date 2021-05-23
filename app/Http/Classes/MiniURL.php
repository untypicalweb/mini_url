<?php

namespace App\Http\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use stdClass;

// TODO - If no url found, ask if they would like to store as short url
// TODO - regex to make sure URL passed is correct
// TODO - expiry date on urls if passed
// TODO - unit test to check expiry date is in the future

class MiniURL
{

    private $jsonBin, $urlStructure, $url, $short_url, $miniUrl, $expiry;

    public function __construct()
    {
        // link used for short urls
        $this->miniUrl = "https://short.link/";
    }

    /**
     * Shortens URL
     * @returns string $url shortened URL
     */
    public function setData(array $data): MiniURL
    {
        // generate a random string
        $this->short_url = $this->generateUniqueId();
        $this->url = isset($data['url']) ? $data['url'] : false;
        $this->expiry = isset($data['expiry']) ? $data['expiry'] : false;
        return $this;
    }

    /**
     * Shortens URL
     * @returns string $url shortened URL
     */
    public function generateUniqueId(): string
    {
        return substr(md5(time().uniqid()), 0, 6);
    }

    /**
     * Validation for URL
     * @params string $url URL passed for verification
     * @return void $passed
     */
    public function verifyURL(string $url)
    {
        // TODO - decide validation tests, write unit test
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Verify URL response
     */
    public function verifyUrlResponse(string $url): bool
    {
        try {
            $response = Http::get($url);
            return $response->status() === 200;
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Store Short URL in database
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
            // retry the insertion if duplicate entry
            if($e->getCode() == "23000") {
                $this->generateUniqueId();
                return $this->store();
            } else {
                return ['status' => false, 'message' => $e->getMessage()];
            }
        }
    }

    /**
     * Find short url in the database
     * @params string $url short url to find in the db
     * @returns bool $response
     */
    public function find(string $url): array
    {
        try {
            return (array)DB::table('short_urls')
            ->select('short_url','url')
            ->where('short_url', '=', $url)
            ->get()
            ->first();
        } catch(QueryException $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Find short url in the database using unique id
     * @params string $id unique id in the database
     * @returns array $response
     */
    public function findById(string $id): array
    {
        try {
            return DB::table('short_urls')
            ->select('short_url','url')
            ->where('short_url', '=', $url)
            ->get()
            ->first() !== null;
        } catch(\Exception $e) {
            return (array)['status' => false, 'message' => $e->getMessage()];
        }
    }

}
