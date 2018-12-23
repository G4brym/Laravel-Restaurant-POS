<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'state',
        'meal_id',
        'nif',
        'name',
        'date',
        'total_price',
    ];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function items()
    {
        return $this->hasMany(Invoice_item::class);
    }
}
