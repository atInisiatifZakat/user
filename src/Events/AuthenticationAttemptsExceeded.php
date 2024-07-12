<?php

namespace Inisiatif\Package\User\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuthenticationAttemptsExceeded
{
    use Dispatchable, SerializesModels;

    public string $action;

    /**
     * @var Model $user
     **/
    public $user;

    /**
     * @var Model $user
     **/
    public function __construct($user, $action)
    {

        $this->user = $user;
        $this->action = $action;
    }
}
