<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Meal extends Resource
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
          'table_number_id' => $this->table_number,
          'start' => $this->start,
          'end' => $this->end,
          'responsible_waiter_id' => $this->responsible_waiter->id,
          'responsible_waiter' => $this->responsible_waiter->name,
          'total_price_preview' => $this->total_price_preview,
          'orders' => $this->orders,
        ];
    }
}
