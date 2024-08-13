<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Http\Controllers\Passport;

use Illuminate\Http\Request;
use Inisiatif\Package\User\Passport;
use Illuminate\Http\RedirectResponse;

final class RedirectController
{
    public function create(Request $request): RedirectResponse
    {
        return Passport::redirect($request);
    }
}
