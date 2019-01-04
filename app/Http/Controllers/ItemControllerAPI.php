<?php

namespace App\Http\Controllers;

use App\Item;
use App\Http\Resources\Item as ItemResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemControllerAPI extends Controller
{
    public function index()
    {
        return ItemResource::collection(Item::where('deleted_at', null)->paginate(10));
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
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $image = $request->photo_url;
        if($image != null) {
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = str_random(10).'.'.'jpeg';
            \File::put(storage_path().'/app/public/items/'.$imageName, base64_decode($image));
            $date=date_create();
            date_timestamp_get($date);
        
            DB::table('items')->insert(
                [
                    "name" => $request->name,
                    "type" => $request->type,
                    "description" => $request->description,
                    "photo_url" => $imageName,
                    "price" => $request->price,
                    "created_at" => date_format($date,"Y-m-d H:i:s"),
                    "updated_at" => date_format($date,"Y-m-d H:i:s")
                ] 
            );
            return response()->json(null, 201);
        }
        return response()->json("Photo missing", 400);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $item = Item::findOrFail($id);

        $image = $request->photo_url;
        if($image != null) {
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = str_random(10).'.'.'jpeg';
            \File::put(storage_path().'/app/public/items/'.$imageName, base64_decode($image));
            $oldPick = DB::table('items')->where('id', $id)->value('photo_url');
            \File::delete(storage_path().'/app/public/items/'.$oldPick);
        } else {
            $imageName = DB::table('items')->where('id', $id)->value('photo_url');
        }
        $item->update( 
                array ( 
                    "name" => $request->name,
                    "type" => $request->type,
                    "description" => $request->description,
                    "photo_url" => $imageName,
                    "price" => $request->price
                    )
                );
        return new ItemResource($item);
    }
  
}
