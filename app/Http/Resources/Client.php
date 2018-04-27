<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Client extends JsonResource
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
            'id'                => $this->id,
            'user'              => $this->user,
            'radio_type'        => $this->radio_type,
            'radio_name'        => $this->radio_name,
            'business_category' => $this->business_category,
            'cpf'               => $this->cpf,
            'cnpj'              => $this->cnpj,
            'address'           => $this->address,
            'address_city'      => $this->address_city,
            'address_uf'        => $this->address_uf,
            'address_cep'        => $this->address_cep,
            'tel'               => $this->tel,
            'tel_mobile'        => $this->tel_mobile,
            'tel_mobile_carrier' => $this->tel_mobile_carrier,
            'site'              => $this->site,
            'programs'          => $this->programs,
            'status'          => $this->status,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
