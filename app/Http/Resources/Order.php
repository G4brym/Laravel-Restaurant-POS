<?php

namespace App\Http\Resources;

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
          'item_id' => $this->item->id,
          'item' => $this->item->name,
          'meal' => $this->meal->id,
          'responsible_cook_id' => $this->responsible_cook->id,
          'responsible_cook' => $this->responsible_cook->name,
        ];
    }
}
