<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MiniURL as Mini;

class MiniURL extends Controller
{

    /**
     * Function to accept a full URL and return a short version
     * @params string $url url to be shortened
     * @params object $verify
     */
    public function short(string $url): array
    {
        $miniUrl = new \App\Http\Classes\MiniURL();
        return $miniUrl->validation($url)->verifyURLResponse()->short()->store();
    }

}
