<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'id',
        'name',
        'type',
        'description',
        'photo_url',
        'price'
    ];

}
