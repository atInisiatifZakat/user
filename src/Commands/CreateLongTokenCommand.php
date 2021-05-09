<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Commands;

use Tymon\JWTAuth\JWTGuard;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Events\Dispatcher;
use Inisiatif\Package\User\Events\TokenWasGenerated;
use Inisiatif\Package\Contract\User\Repository\UserRepositoryInterface;

final class CreateLongTokenCommand extends Command
{
    protected $signature = 'account:token {user : User ID for create token}';

    protected $description = 'Create long expired token';

    /**
     * @psalm-suppress PossiblyInvalidCast
     * @psalm-suppress InvalidArgument
     */
    public function handle(Factory $auth, Dispatcher $event, UserRepositoryInterface $repository): int
    {
        $userId = (string) $this->argument('user');

        $user = $repository->findUsingId($userId);

        if ($user === null) {
            $this->error('User not exists');

            return self::FAILURE;
        }

        /** @var JWTGuard $jwt */
        $jwt = $auth->guard('api');

        $expired = Carbon::now()->copy()->addYears(999);

        $ttl = Carbon::now()->diffInMinutes($expired);

        $token = $token = $jwt->setTTL($ttl)->login($user);

        $this->info('Token : ' . $token);

        $event->dispatch(new TokenWasGenerated($user, $token));

        return 0;
    }
}
