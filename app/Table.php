<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{

	protected $primaryKey = 'table_number';

	protected $table = 'restaurant_tables';

    protected $fillable = [
        'table_number',
        'created_at',
        'updated_at',
    ];

}


