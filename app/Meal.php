<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = [
        'state',
        'table_number',
        'start',
        'end',
        'responsible_waiter_id',
        'total_price_preview'
    ];

    public function table_number()
    {
        return $this->belongsTo(Table::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function responsible_waiter()
    {
        return $this->belongsTo(User::class);
    }
}
