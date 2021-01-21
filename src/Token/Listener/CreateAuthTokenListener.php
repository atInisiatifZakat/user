<?php declare(strict_types=1);

namespace Inisiatif\Package\User\Token\Listener;

use Tymon\JWTAuth\Token;
use Tymon\JWTAuth\Manager;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Inisiatif\Package\User\Models\AuthToken;
use Inisiatif\Package\User\Abstracts\UserEvent;
use Inisiatif\Package\User\Actions\CreateAuthTokenAction;
use Inisiatif\Package\Contract\User\Model\TokenAwareInterface;
use Inisiatif\Package\Contract\Common\Event\ModelEventInterface;

final class CreateAuthTokenListener implements ShouldQueue
{
    private CreateAuthTokenAction $action;

    private Manager $manager;

    public function __construct(CreateAuthTokenAction $action, Manager $manager)
    {
        $this->action = $action;
        $this->manager = $manager;
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function handle(UserEvent $event): void
    {
        if ($event instanceof TokenAwareInterface && $event instanceof ModelEventInterface) {
            $payload = $this->manager->decode(new Token($event->getToken()));

            $expiredDate = Carbon::createFromTimestamp($payload->get('exp'));

            $token = AuthToken::fromArray([
                'user_id' => $event->getModel()->getId(),
                'key' => $payload->get('jti'),
                'token' => $event->getToken(),
                'expired_at' => $expiredDate,
            ]);

            $this->action->handle($token);
        }
    }
}
