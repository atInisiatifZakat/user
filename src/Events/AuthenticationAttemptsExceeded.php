<?php

namespace Inisiatif\Package\User\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Inisiatif\Package\User\Models\User;

class AuthenticationAttemptsExceeded
{
    use Dispatchable, SerializesModels;

    /**
     * @var Model $user
     **/
    public $user;

    /**
     * @var Model $user
     **/
    public function __construct($user)
    {

        $this->user = $user;
    }
}
