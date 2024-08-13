<?php

declare(strict_types=1);

namespace Inisiatif\Package\User;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inisiatif\Package\User\Passport\Redirector;
use Inisiatif\Package\User\Passport\UserProfile;
use Inisiatif\Package\User\Passport\UserResolver;

final class Passport
{
    public static function baseUrl(): string
    {
        /** @var string */
        return \config('services.passport.base_url');
    }

    public static function redirectUrl(string $query): string
    {
        return self::baseUrl().'/oauth/authorize?'.$query;
    }

    public static function profileUrl(): string
    {
        return self::baseUrl().'/api/oauth/user-profile';
    }

    public static function clientId(): string
    {
        /** @var string */
        return \config('services.passport.client_id');
    }

    public static function callbackUrl(): string
    {
        /** @var string */
        return \config('services.passport.callback_url');
    }

    public static function redirect(Request $request): RedirectResponse
    {
        return Redirector::make()->redirect($request);
    }

    /**
     * @throws Throwable
     */
    public static function callback(Request $request): UserProfile
    {
        return UserResolver::make()->resolve($request);
    }
}
