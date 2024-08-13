<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Passport;

use Throwable;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Facades\Http;
use Inisiatif\Package\User\Passport;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\ConnectionException;

final class UserResolver
{
    public static function make(): self
    {
        return new self;
    }

    /**
     * @throws Throwable
     */
    public function resolve(Request $request): UserProfile
    {
        $accessToken = $this->createAccessToken($request);

        return $this->fetchPassportUser($accessToken);
    }

    /**
     * @throws ConnectionException
     */
    private function fetchPassportUser(string $token): UserProfile
    {
        $response = $this->getHttpClient()->asJson()->withToken($token)->get(Passport::profileUrl());

        return new UserProfile(
            $response->json('id'),
            $response->json('name'),
            $response->json('email'),
            $response->json('loginable_id'),
            $response->json('loginable_type'),
        );
    }

    /**
     * @throws Throwable
     */
    private function createAccessToken(Request $request): ?string
    {
        $state = $request->session()->pull('state');

        $codeVerifier = $request->session()->pull('code_verifier');

        \throw_unless($state !== '' && $state === $request->input('state'), InvalidArgumentException::class);

        $response = $this->getHttpClient()->asForm()->post(Passport::baseUrl().'/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => Passport::clientId(),
            'redirect_uri' => Passport::callbackUrl(),
            'code_verifier' => $codeVerifier,
            'code' => $request->input('code'),
        ]);

        return $response->json('access_token');
    }

    private function getHttpClient(): PendingRequest
    {
        $disableSsl = \config('user.disable_ssl_verify_passport', false);

        return $disableSsl ? Http::withoutVerifying() : Http::createPendingRequest();
    }
}
