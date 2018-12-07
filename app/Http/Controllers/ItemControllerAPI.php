<?php

namespace App\Http\Controllers;

use App\Item;
use App\Http\Resources\Item as ItemResource;
use Illuminate\Http\Request;

class ItemControllerAPI extends Controller
{
    public function index(Request $request)
    {
        return ItemResource::collection(Item::all());
    }   
}
