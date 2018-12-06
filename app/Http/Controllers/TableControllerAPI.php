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
        try {
            $table->delete();
        }
        catch (\Exception $e) {
            $date=date_create();
            date_timestamp_get($date);
            Table::where('table_number', $table_number)->update(array('deleted_at' => date_format($date,"Y-m-d H:i:s")));
        }
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
