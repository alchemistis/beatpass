<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Carrinho extends Model
{
    protected $table = 'carrinho';

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = ['id', 'idusr'];

    public function ingresso()
    {
        return $this->hasOne('App\Models\Ingresso', 'id', 'idingresso');
    }
    public function evento()
    {
        return $this->hasOneThrough('App\Models\Evento', 'App\Models\Ingresso', 'id', 'id', 'idingresso', 'idevento');
    }
}