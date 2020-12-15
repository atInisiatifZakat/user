<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Models;

use DateTime;
use DateTimeInterface;
use Webmozart\Assert\Assert;
use InvalidArgumentException;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Inisiatif\Package\User\Builders\UserBuilder;
use Inisiatif\Package\Contract\User\Model\UserInterface;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Inisiatif\Package\Contract\User\Model\LoginableAwareInterface;
use Inisiatif\Package\Contract\Common\Concern\ToggleAwareInterface;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

abstract class AbstractUser extends Model implements UserInterface, LoginableAwareInterface, Authenticatable, ToggleAwareInterface, CanResetPassword
{
    use Notifiable;
    use AuthenticatableTrait;
    use CanResetPasswordTrait;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'deactivated_at' => 'datetime',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function fromArray(array $attributes): UserInterface
    {
        return UserBuilder::makeFromArray(new static(), $attributes);
    }

    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    /**
     * @param string $id
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function setId($id): self
    {
        Assert::uuid($id);

        return $this->setAttribute('id', $id);
    }

    public function getUsername(): string
    {
        return $this->getAttribute('username');
    }

    public function setUsername(string $value)
    {
        Assert::regex($value, '/^[A-Za-z.-]+$/');

        return $this->setAttribute('username', $value);
    }

    public function getEmail(): string
    {
        return $this->getAttribute('email');
    }

    public function setEmail(string $value)
    {
        Assert::email($value);

        return $this->setAttribute('email', $value);
    }

    public function getName(): string
    {
        return $this->getAttribute('name');
    }

    public function setName(string $value)
    {
        return $this->setAttribute('name', $value);
    }

    public function getPassword(): string
    {
        return $this->getAttribute('password');
    }

    public function setPassword(string $value)
    {
        $password = password_get_info($value);

        if ($password['algo'] === 0 || $password['algo'] === null) {
            throw new InvalidArgumentException('Value must be hashed.');
        }

        return $this->setAttribute('password', $value);
    }

    public function getDeactivatedAt(): ?DateTimeInterface
    {
        return $this->getAttribute('deactivated_at');
    }

    public function setDeactivatedAt(?DateTimeInterface $date = null)
    {
        $deactivatedAt = $date ? $date->format(Carbon::DEFAULT_TO_STRING_FORMAT) : $date;

        return $this->setAttribute('deactivated_at', $deactivatedAt);
    }

    public function getLoginable(): string
    {
        return $this->getAttribute('loginable_id');
    }

    public function setLoginable($value)
    {
        Assert::uuid($value);

        return $this->setAttribute('loginable_id', $value);
    }

    public function getLoginableType(): string
    {
        return $this->getAttribute('loginable_type');
    }

    public function setLoginableType(string $value)
    {
        Assert::inArray($value, [self::TYPE_AGENT, self::TYPE_EMPLOYEE, self::TYPE_VOLUNTEER]);

        return $this->setAttribute('loginable_type', $value);
    }

    public function isActive(): bool
    {
        return $this->getDeactivatedAt() === null;
    }

    public function setIsActive(bool $value)
    {
        return $value === false ? $this->setDeactivatedAt(new DateTime()) : $this->setDeactivatedAt(null);
    }
}
