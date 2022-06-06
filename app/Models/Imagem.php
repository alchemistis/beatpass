<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imagem extends Model
{
    protected $table = 'imagem';

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = [
        'id', 'laravel_through_key'
    ];
}

