<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
	protected $table = 'restaurant_tables';

    protected $fillable = [
    	'id',
        'table_number',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

}


