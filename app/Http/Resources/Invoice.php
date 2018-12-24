<?php

namespace App\Http\Resources;

use App\Http\Resources\InvoiceItem as InvoiceItemResource;

use Illuminate\Http\Resources\Json\Resource;

class Invoice extends Resource
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
          'table' => $this->meal->table_number,
          'waiter' => $this->meal->responsible_waiter->name,
          'meal_id' => $this->meal_id,
          'nif' => $this->nif,
          'name' => $this->name,
          'date' => $this->date,
          'total_price' => $this->total_price,
          'items' => InvoiceItemResource::collection($this->items),
        ];
    }
}
