<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compra';

    public $timestamps = false;

    protected $guarded = [];

    public function ingresso()
    {
        return $this->hasOne('App\Models\Ingresso', 'id', 'idingresso');
    }
    public function evento()
    {
        return $this->hasOneThrough('App\Models\Evento', 'App\Models\Ingresso', 'id', 'id', 'idingresso', 'idevento');
    }
}
