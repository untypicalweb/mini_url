<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MiniURL as Mini;
use Illuminate\Support\Facades\DB;

class MiniURL extends Controller
{

    /**
     * Function to accept a full URL and return a short version
     * @params string $url url to be shortened
     * @params object $verify
     */
    public function short(array $data): array
    {
        $miniUrl = new \App\Http\Classes\MiniURL();

        // verify the url if specified
        if (isset($data['verify_url']) && $data['verify_url'] == 1) {
            $passed = $miniUrl->verifyURL($data['url']);
            if (!$passed) {
                return [
                    "status" => false,
                    "message" => "Failed URL regex check."
                ];
            }
        }

        // verify the url returns 200
        if (isset($data['verify_response']) && $data['verify_response'] == 1) {
            $passed = $miniUrl->verifyURLResponse($data['url']);
            if (!$passed) {
                return [
                    "status" => false,
                    "message" => "Failed URL Http response check."
                ];
            }
        }

        // set the expiry date if the user specifies one
        if (isset($data['expiry']) && !empty($data['expiry'])) {
            $passed = $miniUrl->expiry($data['expiry']);
            if (!$passed) {
                return [
                    "status" => false,
                    "message" => "Failed to set expiry date, please make sure it's the correct format and in the future."
                ];
            }
        }

        return $miniUrl->setData($data)->store();
    }

    /**
     * Function to accept a short URL and return the original url
     * @params string $url short url
     * @params object $verify
     */
    public function find(string $url): array
    {
        $miniUrl = new \App\Http\Classes\MiniURL();
        return $miniUrl->find($url);
    }

}
