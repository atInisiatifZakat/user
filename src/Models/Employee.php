<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Models;

use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\Common\Concerns\HasBranch;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Inisiatif\Package\Common\Concerns\UuidPrimaryKey;
use Inisiatif\Package\Common\Contracts\HasBranchInterface;

final class Employee extends Model implements HasBranchInterface
{
    use HasBranch;

    use UuidPrimaryKey;

    public function user(): MorphOne
    {
        $userClass = \get_class(app(AbstractUser::class));

        return $this->morphOne($userClass, 'loginable');
    }
}
