<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Builders;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Webmozart\Assert\Assert;
use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\User\Models\AbstractUser;
use Inisiatif\Package\Common\Abstracts\ModelBuilder;
use Inisiatif\Package\Contract\User\Model\UserInterface;

final class UserBuilder extends ModelBuilder
{
    public static function makeFromRequest(Model $model, Request $request): UserInterface
    {
        return self::makeFromArray($model, $request->input());
    }

    public static function makeFromArray(Model $model, array $attributes): UserInterface
    {
        Assert::isInstanceOf($model, AbstractUser::class);

        foreach (['name', 'email', 'username'] as $field) {
            Assert::keyExists($attributes, $field);
            Assert::notNull($attributes[$field]);
        }

        if ($model->getId() === null) {
            $id = Uuid::uuid6();

            $model->setId($id->toString());
        }

        $model->setName($attributes['name']);
        $model->setEmail($attributes['email']);
        $model->setUsername($attributes['username']);

        if (array_key_exists('loginable_id', $attributes)) {
            $model->setLoginable($attributes['loginable_id']);
        }

        if (array_key_exists('loginable_type', $attributes)) {
            $model->setLoginableType($attributes['loginable_type']);
        }

        if (array_key_exists('password', $attributes)) {
            $password = $attributes['password'];

            $passwordCheck = password_get_info($password);

            if ($passwordCheck['algo'] === 0 || $passwordCheck['algo'] === null) {
                $model->setPassword(bcrypt(md5($password)));
            } else {
                $model->setPassword($password);
            }
        }

        /** @var UserInterface */
        return $model;
    }
}
