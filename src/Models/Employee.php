<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Models;

use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\User\ModelRegistrar;
use Inisiatif\Package\User\Models\Concern\HasUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Employee extends Model
{
    use HasUser;
    use HasUuids;

    public function getTable(): string
    {
        /** @var string */
        return \config('user.table_names.employees', parent::getTable());
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(
            ModelRegistrar::getBranchModelClass(), 'branch_id'
        );
    }
}
