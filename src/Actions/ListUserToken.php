<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Actions;

use Illuminate\Http\Request;
use Inisiatif\Package\User\ModelRegistrar;
use Illuminate\Contracts\Pagination\CursorPaginator;

final class ListUserToken
{
    public function handle(mixed $user): CursorPaginator
    {
        $modelClass = ModelRegistrar::getUserModel()::class;

        if (! $user instanceof $modelClass) {
            throw new \RuntimeException(
                'Parameter $user must be instanceof '.ModelRegistrar::getUserModelClass()
            );
        }

        return $user->tokens()->cursorPaginate();
    }

    public function fromRequest(Request $request): CursorPaginator
    {
        return $this->handle(
            $request->user()
        );
    }
}
