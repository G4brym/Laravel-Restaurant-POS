<?php

namespace App\Http\Controllers;

use App\Item;
use App\Http\Resources\Item as ItemResource;
use Illuminate\Http\Request;

class ItemControllerAPI extends Controller
{
    public function index()
    {
        return ItemResource::collection(Item::paginate(50));
    }

    public function show($id)
    {
        return new ItemResource(Item::find($id));
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        try {
            $item->delete();
        }
        catch (\Exception $e) {
            $date=date_create();
            date_timestamp_get($date);
            Item::where('id', $id)->update(array('deleted_at' => date_format($date,"Y-m-d H:i:s")));
        }
        return response()->json(null, 204);
    }

    public function store(Request $request)
    {
        /*$request->validate([
            'item_number' => 'required|unique:restaurant_items,item_number'
        ]);

        $item = new Item();
        $item->fill($request->all());
        $item->save();

        return response()->json(new ItemResource($item), 201);*/
    }

    public function update(Request $request, $item_number)
    {
        /*$request->validate([
            'item_number' => 'required|unique:restaurant_items,item_number'
        ]);

        $item = Item::findOrFail($item_number);
        $item->update($request->all());
        return new ItemResource($item);*/
    }
  
}
