<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Builders;

use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Webmozart\Assert\Assert;
use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\User\Models\AuthToken;
use Inisiatif\Package\Common\Abstracts\ModelBuilder;
use Inisiatif\Package\Contract\User\Model\AuthTokenInterface;

final class AuthTokenBuilder extends ModelBuilder
{
    public static function makeFromRequest(Model $model, Request $request): AuthTokenInterface
    {
        return self::makeFromArray($model, $request->input());
    }

    public static function makeFromArray(Model $model, array $attributes): AuthTokenInterface
    {
        Assert::isInstanceOf($model, AuthToken::class);

        $uuid = Uuid::uuid6();

        foreach (['key', 'token', 'user_id'] as $field) {
            Assert::keyExists($attributes, $field);
            Assert::notNull($attributes[$field]);
        }

        $token = new AuthToken();

        $token->setId($uuid->toString());
        $token->setAttribute('user_id', $attributes['user_id']);
        $token->setTokenKey($attributes['key']);
        $token->setToken($attributes['token']);

        $expiredAt = Arr::get($attributes, 'expired_at');

        if ($expiredAt instanceof DateTimeInterface) {
            $token->setExpiredTime($expiredAt);
        }

        return $token;
    }
}
