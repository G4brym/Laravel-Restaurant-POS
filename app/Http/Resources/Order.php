<?php

namespace App\Http\Resources;

use ErrorException;
use Illuminate\Http\Resources\Json\Resource;

class Order extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id'   => $this->id,
            'state' => $this->state,
            'start' => $this->start,
            'end' => $this->end,
            'item' => $this->item,
            'meal' => $this->meal->id,
            'responsible_cook' => $this->responsible_cook
            /*'updated_at' => $this->updated_at->toDateTimeString(),*/
        ];

    }
}
