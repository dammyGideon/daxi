<?php

namespace App\Http\Resources;


use App\Http\Resources\BankResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userRoles = $this->roles()->with('permissions')->get();
        $roles = $userRoles->pluck('slug');
        $rolesPermissions = $userRoles->pluck('permissions')->flatten(1)->pluck('slug');
        $userPermissions = $rolesPermissions->merge($this->permissions->pluck('slug'));

        return [
            //"bank_info" => BankResource::collection($this->banks) ?? NULL,
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone,
            "location" => $this->location,
            "picture" => $this->picture,
            "vehicle_type" => $this->vehicle_type,
            "registration_status"=>$this->registration_status,
            "created_at" => $this->created_at->format('Y-m-d'),
            "roles" =>$roles,
            "permissions" => $userPermissions
        ];
    }
}
