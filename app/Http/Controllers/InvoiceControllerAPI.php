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

        } else if ($request->has('filter')) {
            
            if ($request->date != "") {
                $query = Invoice::where('state', 'pending')->where('date', $request->date)->paginate(10);
                switch ($request->filter) {
                    case "Paid":
                        $query = Invoice::where('state', 'paid')->where('date', $request->date)->paginate(10);
                        break;
                    case "Not Paid":
                        $query = Invoice::where('state', 'not paid')->where('date', $request->date)->paginate(10);
                        break;
                }
            } else {
                $query = Invoice::where('state', 'pending')->paginate(10);
                switch ($request->filter) {
                    case "Paid":
                        $query = Invoice::where('state', 'paid')->paginate(10);
                        break;
                    case "Not Paid":
                        $query = Invoice::where('state', 'not paid')->paginate(10);
                        break;
                }
            }
            return InvoiceResource::collection($query);
    
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
