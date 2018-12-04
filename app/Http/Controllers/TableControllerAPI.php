<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Table as TableResource;
use App\Table;

class TableControllerAPI extends Controller
{

    public function index()
    {
        return TableResource::collection(Table::paginate(50));
    }

    /*public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|unique:restaurante_tables,table_number'
        ]);

        $table = new Table();
        $table->fill($request->all());
        $table.created_at = now()->timestamp;
        $table->save();

        return response()->json(new TableResource($table), 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'table_number' => 'required|unique:restaurante_tables,table_number'
        ]);

        $table = Table::findOrFail($id);
        $table.updated_at = now()->timestamp;
        $table->update($request->all());
        return new UserResource($user);
    }

    public function destroy($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();
        return response()->json(null, 204);
    }*/

}
