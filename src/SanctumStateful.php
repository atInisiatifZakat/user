<?php

namespace Inisiatif\Package\User;

use Illuminate\Support\Arr;

final class SanctumStateful
{
    private static array $ports = [
        '3000', '3001', '3002', '3003', '3005',
    ];

    public static function domains(array|string $subdomains): array
    {
        $subdomainWithPorts = Arr::flatten(\array_map(static function (string $domain) {
            return \array_map(static fn(string $port) => \sprintf('%s:%s', $domain, $port), self::$ports);
        }, Arr::wrap($subdomains)));

        return \array_merge($subdomains, $subdomainWithPorts);
    }
}
