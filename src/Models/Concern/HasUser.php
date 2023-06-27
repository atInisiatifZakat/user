<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Models\Concern;

use Inisiatif\Package\User\ModelRegistrar;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasUser
{
    public function user(): MorphOne
    {
        return $this->morphOne(
            ModelRegistrar::getUserModelClass(), 'loginable'
        );
    }
}
