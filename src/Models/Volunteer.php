<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Models;

use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\Common\Models\Branch;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Inisiatif\Package\Common\Concerns\UuidPrimaryKey;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Inisiatif\Package\Common\Contracts\HasBranchInterface;

final class Volunteer extends Model implements HasBranchInterface
{
    use UuidPrimaryKey;

    public function user(): MorphOne
    {
        $userClass = \get_class(app(AbstractUser::class));

        return $this->morphOne($userClass, 'loginable');
    }

    public function branch(): HasOneThrough
    {
        return $this->hasOneThrough(
            Branch::class,
            Employee::class,
            'id',
            'id',
            'employee_id',
            'branch_id'
        )->withoutGlobalScopes();
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class)->withoutGlobalScopes();
    }

    public function getTable()
    {
        return \config('user.table_names.volunteers', parent::getTable());
    }
}
