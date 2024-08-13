<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Http\Controllers\Passport;

use Throwable;
use Illuminate\Http\Request;
use Inisiatif\Package\User\Passport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Auth\Factory;
use Inisiatif\Package\User\ModelRegistrar;

final class CallbackController
{
    /**
     * @throws Throwable
     */
    public function store(Request $request, Factory $auth): RedirectResponse
    {
        $profile = Passport::callback($request);

        /** @var mixed $user */
        $user = ModelRegistrar::getUserModel()::query()
            ->where('id', $profile->id)
            ->where('loginable_id', $profile->loginableId)
            ->first();

        \abort_if(\is_null($user), '401');

        $auth->guard()->login($user, true);

        return redirect()->intended();
    }
}
