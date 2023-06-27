<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Models;

use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\User\ModelRegistrar;
use Inisiatif\Package\User\Models\Concern\HasUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

final class Volunteer extends Model
{
    use HasUser;
    use HasUuids;

    public function getTable(): string
    {
        /** @var string */
        return \config('user.table_names.volunteers', parent::getTable());
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            ModelRegistrar::getEmployeeModelClass()
        );
    }

    public function branch(): HasOneThrough
    {
        return $this->hasOneThrough(
            ModelRegistrar::getBranchModelClass(),
            ModelRegistrar::getEmployeeModelClass(),
            'id',
            'id',
            'employee_id',
            'branch_id'
        );
    }
}
