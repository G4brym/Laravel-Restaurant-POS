<?php

namespace App\Http\Controllers;

use App\Http\Resources\Meal as MealResource;
use App\Http\Resources\Order as OrderResource;

use App\Meal;
use App\MealItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Order;
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
        } else if ($request->has('filter')) {

            if ($request->waiterName != "") {
                $waiterId = DB::table('users')->where('name', $request->waiterName)->pluck('id');
                if ($request->date != "") {
                    $query = Meal::whereIn('state', ['terminated', 'active'])->whereDate('start', $request->date)->where('responsible_waiter_id', $waiterId)->paginate(10);
                    switch ($request->filter) {
                        case "Active":
                            $query = Meal::where('state', 'active')->whereDate('start', $request->date)->where('responsible_waiter_id', $waiterId)->paginate(10);
                            break;
                        case "Terminated":
                            $query = Meal::where('state', 'terminated')->whereDate('start', $request->date)->where('responsible_waiter_id', $waiterId)->paginate(10);
                            break;
                        case "Paid":
                            $query = Meal::where('state', 'paid')->whereDate('start', $request->date)->where('responsible_waiter_id', $waiterId)->paginate(10);
                            break;
                        case "Not Paid":
                            $query = Meal::where('state', 'not paid')->whereDate('start', $request->date)->where('responsible_waiter_id', $waiterId)->paginate(10);
                            break;
                    }
                } else {
                    $query = Meal::whereIn('state', ['terminated', 'active'])->where('responsible_waiter_id', $waiterId)->paginate(10);
                    switch ($request->filter) {
                        case "Active":
                            $query = Meal::where('state', 'active')->where('responsible_waiter_id', $waiterId)->paginate(10);
                            break;
                        case "Terminated":
                            $query = Meal::where('state', 'terminated')->where('responsible_waiter_id', $waiterId)->paginate(10);
                            break;
                        case "Paid":
                            $query = Meal::where('state', 'paid')->where('responsible_waiter_id', $waiterId)->paginate(10);
                            break;
                        case "Not Paid":
                            $query = Meal::where('state', 'not paid')->where('responsible_waiter_id', $waiterId)->paginate(10);
                            break;
                    }
                }

            } else {

                if ($request->date != "") {
                    $query = Meal::where('state', 'active')->orWhere('state', 'terminated')->whereDate('start', $request->date)->paginate(10);
                    switch ($request->filter) {
                        case "Active":
                            $query = Meal::where('state', 'active')->whereDate('start', $request->date)->paginate(10);
                            break;
                        case "Terminated":
                            $query = Meal::where('state', 'terminated')->whereDate('start', $request->date)->paginate(10);
                            break;
                        case "Paid":
                            $query = Meal::where('state', 'paid')->whereDate('start', $request->date)->paginate(10);
                            break;
                        case "Not Paid":
                            $query = Meal::where('state', 'not paid')->whereDate('start', $request->date)->paginate(10);
                            break;
                    }
                } else {
                    $query = Meal::where('state', 'active')->orWhere('state', 'terminated')->paginate(10);
                    switch ($request->filter) {
                        case "Active":
                            $query = Meal::where('state', 'active')->paginate(10);
                            break;
                        case "Terminated":
                            $query = Meal::where('state', 'terminated')->paginate(10);
                            break;
                        case "Paid":
                            $query = Meal::where('state', 'paid')->paginate(10);
                            break;
                        case "Not Paid":
                            $query = Meal::where('state', 'not paid')->paginate(10);
                            break;
                    }
                }
              
            }
           
            return MealResource::collection($query);

        } else {
            return MealResource::collection(Meal::orderBy('updated_at', 'desc')->paginate(50));
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

        $invoice = new Meal();
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
                $inv_item = MealItem::where('invoice_id', $invoice->id)
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
