<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'site/receive', // Add your route URI here
        'site/kassa', // Add your route URI here
        '/vkauth',
        '/yandexauth/callback',


    ];
}
