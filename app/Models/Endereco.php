<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    protected $table = 'endereco';

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = ['id', 'idusr'];
}
