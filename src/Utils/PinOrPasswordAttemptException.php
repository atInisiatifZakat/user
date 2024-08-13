<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Utils;

use Exception;

final class PinOrPasswordAttemptException extends Exception
{
    private string $errorType;

    private string $customMessage;

    public function __construct(string $errorType, string $customMessage, int $code = 422, ?Exception $previous = null)
    {
        $this->errorType = $errorType;
        $this->customMessage = $customMessage;
        $message = "Error Type: $errorType, Message: $customMessage";
        parent::__construct($message, $code, $previous);
    }

    public function getErrorType(): string
    {
        return $this->errorType;
    }

    public function getErrorMessage(): string
    {
        return $this->customMessage;
    }
}
