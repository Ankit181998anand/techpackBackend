<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'user_id' => $this->id,
            'username'  => $this->username,
            'firstName' => $this->first_name,
            'lasttName' => $this->last_name,
            'email'     => $this->email,
            'token'     => $this->createToken("Token")->plainTextToken,
            'roles'     => $this->roles->pluck('name') ?? [],

        ];
    }
}
