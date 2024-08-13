<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Passport;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inisiatif\Package\User\Passport;
use Illuminate\Http\RedirectResponse;

final class Redirector
{
    private readonly string $codeChallenge;

    private function __construct(
        private readonly string $state,
        private readonly string $codeVerifier
    ) {
        $this->codeChallenge = $this->createCodeChallenge();
    }

    public static function make(): self
    {
        return new self(
            Str::random(40),
            Str::random(128)
        );
    }

    public function redirect(Request $request): RedirectResponse
    {
        $request->session()->put('state', $this->state);

        $request->session()->put('code_verifier', $this->codeVerifier);

        return redirect()->to(
            Passport::redirectUrl(
                $this->buildRedirectQuery()
            )
        );
    }

    private function createCodeChallenge(): string
    {
        return strtr(rtrim(base64_encode(hash('sha256', $this->codeVerifier, true)), '='), '+/', '-_');
    }

    private function buildRedirectQuery(): string
    {
        return \http_build_query([
            'client_id' => Passport::clientId(),
            'redirect_uri' => Passport::baseUrl(),
            'response_type' => 'code',
            'state' => $this->state,
            'code_challenge' => $this->codeChallenge,
            'code_challenge_method' => 'S256',
        ]);
    }
}
