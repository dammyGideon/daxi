<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "account_name" => $this->account_name,
            "account_number" => $this->account_number,
            "bank_name" => $this->bank_name,
        ];
    }
}
