<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'user_id', 'updated_at', 'created_at', 'radio_type', 'radio_name', 'business_category', 'cpf',
        'cnpj', 'address','address_cep','address_complement','address_city',
        'address_uf', 'tel', 'tel_mobile', 'tel_mobile_carrier','site', 'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function programs()
    {
        return $this->belongsToMany('App\Program', 'signatures');
    }

    public function delete() {
        $this->user->delete();
        $this->programs()->detach();
        parent::delete();
    }
}
