<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    const STATUS_ACTIVE = 'A';
    const STATUS_INACTIVE = 'I';
    const STATUS_MIGRATION = 'M';
    
    protected $fillable = [
        'user_id', 'updated_at', 'created_at', 'radio_type', 'radio_name', 'cpf',
        'cnpj', 'address','address_cep','address_complement','address_city',
        'address_uf', 'tel', 'tel_mobile','site', 'status', 'annotations'
    ];

    protected $appends = ['qt_signatures', 'qt_signatures_active', 'qt_signatures_not_active'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function programs()
    {
        return $this->belongsToMany('App\Program', 'signatures')
            ->withPivot(['status', 'id']);
    }

    public function downloads()
    {
        return $this->hasMany('App\Download');
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