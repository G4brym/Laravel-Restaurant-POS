<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'id',
        'state',
        'start',
        'end',
        'responsible_cook_id',
        'meal_id',
        'item_id',
    ];

    public function responsible_cook()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
