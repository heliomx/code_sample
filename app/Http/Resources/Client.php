<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Client extends JsonResource
{

    protected $complete = false;
    
    public function __construct($resource, $complete = false)
    {
        $this->complete = $complete;
        parent::__construct($resource);
    }

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
            'cpf'               => $this->cpf,
            'cnpj'              => $this->cnpj,
            'address'           => $this->address,
            'address_city'      => $this->address_city,
            'address_uf'        => $this->address_uf,
            'address_cep'        => $this->address_cep,
            'tel'               => $this->tel,
            'tel_mobile'        => $this->tel_mobile,
            'site'              => $this->site,
            'programs'          => $this->programs,
            'status'          => $this->status,
            'qt_signatures'     => $this->qt_signatures,
            'qt_signatures_active'     => $this->qt_signatures_active,
            'qt_signatures_not_active'     => $this->qt_signatures_not_active,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'annotations'       => $this->when($this->complete, $this->annotations)
        ];
    }
}
