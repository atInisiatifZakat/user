<?php declare(strict_types=1);

namespace Inisiatif\Package\User\Models;

use LogicException;
use DateTimeInterface;
use Webmozart\Assert\Assert;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\Contract\User\Model\AuthTokenBlacklistInterface;

final class AuthTokenBlacklist extends Model implements AuthTokenBlacklistInterface
{
    protected $casts = [
        'values' => 'array',
        'expired_at' => 'datetime',
    ];

    public function getId(): ?int
    {
        return $this->getAttribute('id');
    }

    public function setId($id): void
    {
        throw new LogicException('Cannot perform this method, id is generated using autoincrement.');
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

    public function getTokenValues(): array
    {
        return $this->getAttribute('values') ?? [];
    }

    public function setTokenValues($value): self
    {
        return $this->setAttribute('values', $value);
    }

    public function getExpiredAt(): DateTimeInterface
    {
        return $this->getAttribute('expired_at');
    }

    /**
     * @param DateTimeInterface $value
     */
    public function setExpiredAt($value): self
    {
        Assert::nullOrIsInstanceOf($value, DateTimeInterface::class);

        $date = $value === null ? null : Carbon::instance($value)->toDateTimeString();

        return $this->setAttribute('expired_at', $date);
    }
}
