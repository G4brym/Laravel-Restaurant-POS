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
            return InvoiceResource::collection(Invoice::orderBy('id', 'desc')->paginate(10));
        }
    }
}
