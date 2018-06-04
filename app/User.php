<?php

namespace App;

use App\Client;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    const ROLE_ADMIN = 'A';
    const ROLE_CLIENT = 'C';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'role'
    ];

    protected $appends = ['roles', 'client_id'];

    /**
     * Compatibilizando com roles do @websanova/vue-auth
     */
    public function getRolesAttribute()
    {
        return [ $this->role ];
    }

    public function getClientIdAttribute()
    {
        $id = null;
        if ($this->role == 'C')
        {
            $id = Client::whereUserId($this->id)->first()->id;
        }

        return $id;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
