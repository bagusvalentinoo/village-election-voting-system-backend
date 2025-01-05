<?php

namespace App\Http\Resources\User;

use App\Http\Resources\BaseResourceCollection;
use App\Http\Resources\Role\RoleForGeneralResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * User For Login Resource
 *
 * @mixin \App\Models\User\User
 */
class UserForLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'roles' => new BaseResourceCollection($this->roles, RoleForGeneralResource::class)
        ];
    }
}
