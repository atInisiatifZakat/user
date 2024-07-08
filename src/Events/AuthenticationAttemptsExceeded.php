<?php

namespace Inisiatif\Package\User\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Inisiatif\Package\User\Models\User;

class AuthenticationAttemptsExceeded
{
    use Dispatchable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
