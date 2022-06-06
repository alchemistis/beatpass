<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'evento';

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = ['galeria', 'laravel_through_key'];

    public function ingressos()
    {
        return $this->hasMany('App\Models\Ingresso', 'idevento');
    }

    public function imagens()
    {
        return $this->hasManyThrough('App\Models\Imagem', 'App\Models\Galeria', 'idevento', 'id', 'id', 'idimagem');
    }
}
