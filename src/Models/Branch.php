<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class Branch extends Model
{
    use HasUuids;

    protected $casts = [
        'is_head_office' => 'boolean',
    ];

    public function getTable(): string
    {
        /** @var string */
        return \config('user.table_names.branches', parent::getTable());
    }
}
