<?php

namespace App\Http\Controllers;

use App\Http\Resources\Meal as MealResource;
use App\Http\Resources\Order as OrderResource;

use App\Invoice;
use App\Invoice_item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Order;
use App\Meal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MealControllerAPI extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('waiter')) {

            $uid = Auth::user()->id;
            $baseQuery = Meal::where('responsible_waiter_id', $uid)->orderBy('updated_at', 'desc');

            if ($request->has('unfinished')) {
                $baseQuery = $baseQuery->where('state', 'active');
            }

            return MealResource::collection($baseQuery->paginate(50));
        } else {
            // TODO
        }
    }

    // Get all orders for a given meal id
    public function orders(Request $request, $id)
    {
        $baseQuery = Order::where('meal_id', $id)->orderBy('updated_at', 'desc');

        if ($request->has('states')) {
            $pieces = explode(',', $request->get('states'));
            $i = 1;
            foreach ($pieces as &$value) {
                if($i === 1){
                    $baseQuery->where('state', $value);
                } else {
                    $baseQuery->orWhere('state', $value);
                }

                $i++;
            }
        }

        return OrderResource::collection($baseQuery->get());
    }

    // Get all orders for a given meal id
    public function terminate(Request $request, $id)
    {
        $meal = Meal::where('id', $id)->first();

        if($meal->state === 'terminated'){
            return response()->json(['status'=>'already terminated'], 402);
        }

        $invoice = new Invoice();
        $invoice->state = 'pending';
        $invoice->meal_id = $meal->id;
        $invoice->date = Carbon::today();
        $invoice->total_price = 0;
        $invoice->save();

        $total = 0;

        foreach ($meal->orders as &$order) {
            if($order->state !== 'delivered'){
                $order->state = 'not delivered';
                $order->save();
            } else {
                $inv_item = Invoice_item::where('invoice_id', $invoice->id)
                                        ->where('item_id', $order->item_id)->first();

                if($inv_item == null){
                    DB::table('invoice_items')->insert(
                        [
                            "invoice_id" => $invoice->id,
                            "item_id" => $order->item_id,
                            "quantity" => 1,
                            "unit_price" => $order->item->price,
                            "sub_total_price" => $order->item->price
                        ]
                    );
                    $total += $order->item->price;
                } else {
                    $inv_item->quantity++;
                    $inv_item->sub_total_price = $inv_item->quantity*$inv_item->unit_price;

                    $total += $inv_item->unit_price;

                    $inv_item->save();
                }
            }
        }

        $meal->state = 'terminated';
        $meal->save();

        $invoice->total_price = $total;
        $invoice->save();

        return response()->json(['status'=>'success'], 200);
    }
}
