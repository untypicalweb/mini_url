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
        $this->url = $data['url'] ?? false;
        $this->expiry = $data['expiry'] ?? false;
        return $this;
    }

    /**
     * Shortens URL
     * @returns string $url shortened URL
     */
    public function generateUniqueId(): string
    {
        return substr(md5(time() . uniqid()), 0, 6);
    }

    /**
     * Set expiry date for URL
     * @returns string $url shortened URL
     */
    public function expiry(string $expiry)
    {
        $date = \DateTime::createFromFormat("Y-m-d", $expiry)->format('Y-m-d H:i:s');
        if ($date <= date('Y-m-d 23:59:59')) {
            return false;
        }
        if ($date) {
            $this->expiry = $expiry;
            return $this;
        } else {
            return false;
        }
    }

    /**
     * Validation for URL
     * @params string $url URL passed for verification
     * @return bool $passed
     */
    public function verifyURL(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Verify URL response
     */
    public function verifyUrlResponse(string $url): bool
    {
        try {
            // simple http request to get the response status
            $response = Http::get($url);
            return $response->status() === 200;
        } catch (\Exception $e) {
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
            // add the expiry date if it has been set
            if ($this->expiry) {
                $data['expiry'] = $this->expiry;
            }
            DB::table('short_urls')->insert($data);
            return ['status' => true, 'data' => $data];
        } catch (\Exception $e) {
            // retry the insertion if duplicate entry
            if ($e->getCode() == "23000") {
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
            $record = (array)DB::table('short_urls')
                ->select('short_url', 'url', 'expiry')
                ->where('short_url', '=', $url)
                ->get()
                ->first();
            // check to see if a record exists
            if ($record) {
                // if the record has expired, let the user know
                if ($record['expiry'] && $record['expiry'] <= date('Y-m-d H:i:s')) {
                    return ['status' => false, 'message' => 'This expired on ' . $record['expiry'] . '.'];
                }
                $record['status'] = true;
                return $record;
            } else {
                // if there were no matching records let the user know
                return ['status' => false, 'message' => 'There are no urls matching ' . $url . '.'];
            }
        } catch (QueryException $e) {
            // catch errors and return response
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
                    ->select('short_url', 'url')
                    ->where('short_url', '=', $url)
                    ->get()
                    ->first() !== null;
        } catch (\Exception $e) {
            return (array)['status' => false, 'message' => $e->getMessage()];
        }
    }

}
