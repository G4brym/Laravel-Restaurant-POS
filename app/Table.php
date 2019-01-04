<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use SoftDeletes;

	protected $primaryKey = 'table_number';

	protected $table = 'restaurant_tables';

    protected $fillable = [
        'table_number',
        'created_at',
        'updated_at',
    ];
}


