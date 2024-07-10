<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

final class AuthenticationAttemptsExceeded
{
    use Dispatchable, SerializesModels;

    /**
     * @var Model
     **/
    public $user;

    /**
     * @var Model
     **/
    public function __construct($user)
    {

        $this->user = $user;
    }
}
