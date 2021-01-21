<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Models;

use DateTimeInterface;
use Webmozart\Assert\Assert;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\User\Builders\AuthTokenBuilder;
use Inisiatif\Package\Contract\User\Model\UserInterface;
use Inisiatif\Package\Contract\User\Model\AuthTokenInterface;

final class AuthToken extends Model implements AuthTokenInterface
{
    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function getTable()
    {
        return config('user.table_names.auth_tokens', parent::getTable());
    }

    public static function fromArray(array $input): AuthTokenInterface
    {
        return AuthTokenBuilder::makeFromArray(new self(), $input);
    }

    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    public function setId($id)
    {
        Assert::string($id);
        Assert::uuid($id);

        return $this->setAttribute('id', $id);
    }

    public function getTokenKey(): ?string
    {
        return $this->getAttribute('key');
    }

    public function setTokenKey(?string $value): self
    {
        Assert::notNull($value);

        return $this->setAttribute('key', $value);
    }

    public function getToken(): ?string
    {
        return $this->getAttribute('token');
    }

    public function setToken(?string $value)
    {
        Assert::notNull($value);

        return $this->setAttribute('token', $value);
    }

    public function getExpiredTime(): ?DateTimeInterface
    {
        return $this->getAttribute('expired_at');
    }

    public function setExpiredTime(?DateTimeInterface $value)
    {
        Assert::nullOrIsInstanceOf($value, DateTimeInterface::class);

        $date = $value === null ? null : Carbon::instance($value)->toDateTimeString();

        return $this->setAttribute('expired_at', $date);
    }

    public function getUser(): ?UserInterface
    {
        return $this->loadMissing('user')->getRelation('user');
    }

    public function setUser(?UserInterface $user)
    {
        $userId = $user === null ? null : $user->getId();

        return $this->setAttribute('user_id', $userId);
    }
}
