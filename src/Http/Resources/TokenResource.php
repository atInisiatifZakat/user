<?php

declare(strict_types=1);

namespace Inisiatif\Package\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class TokenResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'name' => $this->resource->getAttribute('name'),
            'agent' => $this->resource->getAgent(),
            'abilities' => $this->resource->getAttribute('abilities'),
            'last_used_at' => $this->resource->getAttribute('last_used_at'),
            'tokenable' => $this->whenLoaded('tokenable', [
                'id' => $this->resource->getAttribute('tokenable_id'),
                'type' => $this->resource->getAttribute('tokenable_type'),
                'name' => $this->resource->getAttribute('tokenable')?->getAttribute('name'),
                'email' => $this->resource->getAttribute('tokenable')?->getAttribute('email'),
            ]),
        ];
    }
}
