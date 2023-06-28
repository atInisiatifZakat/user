<?php

declare(strict_types=1);

namespace Inisiatif\Package\User;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Inisiatif\Package\User\Http\Controllers\ProfileController;
use Inisiatif\Package\User\Http\Controllers\TokenAuthController;
use Inisiatif\Package\User\Http\Controllers\UserTokenController;

final class Routes
{
    public static function authToken(): void
    {
        Route::post('/auth/token', [TokenAuthController::class, 'store']);
        Route::delete('/auth/token', [TokenAuthController::class, 'destroy'])->middleware('auth:sanctum');
    }

    public static function userProfile(): void
    {
        Route::middleware('auth:sanctum')
            ->get('/user-information', [ProfileController::class, 'show']);
    }

    public static function userToken(): void
    {
        Route::middleware('auth:sanctum')->group(function (Router $router): void {
            $router->get('/token', [UserTokenController::class, 'index']);
            $router->delete('/token/{tokenId}', [UserTokenController::class, 'destroy']);
        });
    }
}
