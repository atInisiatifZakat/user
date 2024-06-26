<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use DateTimeInterface;
use Illuminate\Support\Arr;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Contracts\Auth\Factory;

final class NewUserToken
{
    public function __construct(
        private readonly Factory $auth
    ) {
    }

    /**
     * @param  array<string, string>  $credentials
     * @param  array<string>  $abilities
     */
    public function handle(array $credentials, string $tokenName, array $abilities = ['*'], DateTimeInterface $expiresAt = null): ?NewAccessToken
    {
        $guard = $this->auth->guard();

        if (\config('user.hashing_password_before_attempt', true)) {
            $plainPassword = Arr::get($credentials, 'password');

            Arr::set($credentials, 'password', \md5($plainPassword));
        }

        $guard->attempt($credentials, true);

        /** @var mixed $user */
        $user = $guard->user();

        return $user?->createToken($tokenName, $abilities, $expiresAt);
    }

    public function fromRequest(Request $request): ?NewAccessToken
    {
        $agent = new Agent($request->headers->all(), $request->userAgent());

        /** @var string $device */
        $device = $agent->device();

        /** @var string $platform */
        $platform = $agent->platform();

        /** @var string $browser */
        $browser = $agent->browser();

        $tokenName = \sprintf('%s|%s|%s|%s', $device, $platform, $browser, $request->ip() ?? '-');

        return $this->handle(
            $request->only(['email', 'password']), $tokenName
        );
    }
}
