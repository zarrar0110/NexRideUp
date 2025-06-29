<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // global middleware
    ];

    protected $middlewareGroups = [
        'web' => [
            // web middleware
        ],
        'api' => [
            // api middleware
        ],
    ];

    protected $routeMiddleware = [
        // ... other middleware ...
        'admin.session' => \App\Http\Middleware\AdminSession::class,
    ];
}
