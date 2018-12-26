<?php

namespace App\Http\Controllers;

use App\Http\Resources\Invoice as InvoiceResource;

use App\Invoice;
use Illuminate\Http\Request;

class InvoiceControllerAPI extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('pending')) {
            $baseQuery = Invoice::where('state', 'pending')->orderBy('id', 'desc');

            return InvoiceResource::collection($baseQuery->get());
        } else {
            return InvoiceResource::collection(Invoice::where('state', '<>', 'pending')->orderBy('id', 'desc')->paginate(10));
        }
    }

    public function markPaid(Request $request, $id)
    {
        $meal = Invoice::findOrFail($id);

        $name = $request->get('name');
        $nif = $request->get('nif');

        if($name != '' && $nif != ''){
            $meal->name = $name;
            $meal->nif = $nif;
            $meal->state = 'paid';
            $meal->save();

            return response()->json(['status'=>'success'], 200);

        }

        return response()->json(['status'=>'missing arguments'], 400);
    }
}
