<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'user_id', 'updated_at', 'created_at', 'radio_type', 'radio_name', 'cpf',
        'cnpj', 'address','address_cep','address_complement','address_city',
        'address_uf', 'tel', 'tel_mobile','site', 'status'
    ];

    protected $appends = ['qt_signatures', 'qt_signatures_active', 'qt_signatures_not_active'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function programs()
    {
        return $this->belongsToMany('App\Program', 'signatures')
            ->withPivot('status');
    }

    public function getQtSignaturesAttribute() {
        return $this->programs()->count();
    }

    public function getQtSignaturesActiveAttribute() {
        return $this->programs()->whereStatus('A')->count();
    }
    
    public function getQtSignaturesNotActiveAttribute() {
        return $this->programs()->whereStatus('D')->count();
    }

    public function delete() {
        $this->user->delete();
        $this->programs()->detach();
        parent::delete();
    }
}
