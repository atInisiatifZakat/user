<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'name' => $this->resource->getAttribute('name'),
            'email' => $this->resource->getAttribute('email'),
            'loginable' => $this->whenLoaded('loginable', [
                'id' => $this->resource->getAttribute('loginable_id'),
                'type' => $this->resource->getAttribute('loginable_type'),
                'name' => $this->resource->getAttribute('loginable')?->getAttribute('name'),
                'branch' => $this->resource->getAttribute('branch') ? [
                    'id' => $this->resource->getAttribute('branch')?->getKey(),
                    'name' => $this->resource->getAttribute('branch')?->getAttribute('name'),
                    'is_head_office' => (bool) $this->resource->getAttribute('branch')?->getAttribute('is_head_office'),
                ] : null,
            ]),
        ];
    }
}
