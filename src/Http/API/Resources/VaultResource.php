<?php

namespace DionTech\Vault\Http\API\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VaultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
