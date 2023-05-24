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
        'webhooks/stripe',
        '/csf-return-action'
    ];

    protected function tokensMatch($request)
    {
        $tokensMatch = parent::tokensMatch($request);
        // dd($tokensMatch);
        if ($tokensMatch) {
            $request->session()->regenerateToken();
        }

        return redirect('/');
    }
}
