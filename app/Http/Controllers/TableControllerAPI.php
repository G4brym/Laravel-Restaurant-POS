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

    public function show($id)
    {
        return new UserResource(User::find($id));
    }

    public function destroy($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();
        return response()->json(null, 204);
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|unique:restaurante_tables,table_number'
        ]);

        $table = new Table();
        $table->fill($request->all());
        $table->save();

        return response()->json(new TableResource($table), 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'table_number' => 'required|unique:restaurante_tables,table_number'
        ]);

        $table = Table::findOrFail($id);
        $table->update($request->all());
        return new UserResource($user);
    }

}
