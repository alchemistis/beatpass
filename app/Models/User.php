<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = 'usuario';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'senha',
    ];

    public function endereco()
    {
        return $this->hasOne('App\Models\Endereco', 'idusr');
    }

    public function compras()
    {
        return $this->hasMany('App\Models\Compra', 'iduser', 'id');
    }

    public function carrinho()
    {
        return $this->hasMany('App\Models\Carrinho', 'idusr', 'id');
    }
}
