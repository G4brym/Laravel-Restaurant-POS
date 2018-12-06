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

    public function show($table_number)
    {
        return new TableResource(Table::find($table_number));
    }

    public function destroy($table_number)
    {
        $table = Table::findOrFail($table_number);
        $table->delete();
        return response()->json(null, 204);
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|unique:restaurant_tables,table_number'
        ]);

        $table = new Table();
        $table->fill($request->all());
        $table->save();

        return response()->json(new TableResource($table), 201);
    }

    public function update(Request $request, $table_number)
    {
        $request->validate([
            'table_number' => 'required|unique:restaurant_tables,table_number'
        ]);

        $table = Table::findOrFail($table_number);
        $table->update($request->all());
        return new TableResource($table);
    }

}
