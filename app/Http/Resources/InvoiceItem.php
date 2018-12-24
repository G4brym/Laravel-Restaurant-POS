<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class InvoiceItem extends Resource
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
          'invoice_id'   => $this->invoice_id,
          'item_id' => $this->item_id,
          'quantity' => $this->quantity,
          'unit_price' => $this->unit_price,
          'sub_total_price' => $this->sub_total_price,
          'item' => $this->item,
        ];
    }
}
