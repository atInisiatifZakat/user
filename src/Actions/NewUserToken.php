<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use DateTimeInterface;
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

    public function handle(array $credentials, string $tokenName, array $abilities = ['*'], DateTimeInterface $expiresAt = null): ?NewAccessToken
    {
        $guard = $this->auth->guard();

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
