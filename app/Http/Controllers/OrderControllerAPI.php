<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order as OrderResource;

use App\Meal;
use App\Item;
use Illuminate\Http\Request;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderControllerAPI extends Controller
{
    public function index(Request $request)
    {
        $uid = Auth::id();
        $user = Auth::user();
        if ($user->type === 'waiter') {
            $baseQuery = Order::whereIn('meal_id', function ($query) use ($uid) {
                $query->select('id')
                    ->from(with(new Meal)->getTable())
                    ->where('responsible_waiter_id', $uid);
            })->orderBy('updated_at', 'desc');

            if ($request->has('states')) {
                $pieces = explode(',', $request->get('states'));
                $i = 1;
                foreach ($pieces as &$value) {
                    if ($i == 1) {
                        $baseQuery = $baseQuery->where('state', $value);
                    } else {
                        $baseQuery = $baseQuery->orWhere('state', $value);
                    }

                    $i++;
                }
            }

        } else if ($user->type === 'cook') {
            $baseQuery = Order::whereRaw("(state in ('in preparation', 'confirmed'))")
                              ->whereRaw("(responsible_cook_id = $uid or responsible_cook_id is null)")
                              ->orderBy('state', 'desc')
                              ->orderBy('updated_at');

        } else {
            return response()->json(null, 401);
        }

        return OrderResource::collection($baseQuery->paginate(50));
    }

// Trash code
//    public function waiter(Request $request)
//    {
//        $uid = Auth::user()->id;
//        return OrderResource::collection(Order::join('meals', 'orders.meal_id', '=', 'meals.id')->where('meals.responsible_waiter_id', $uid)->where('meals.state', 'active')->where('orders.state', 'pending')->orWhere('orders.state', 'confirmed')->paginate(20));
//    }

    public function show($id)
    {
        return new OrderResource(Order::find($id));
    }

    public function update(Request $request, $id) {
        if (Auth::user()->type === 'cook' && Auth::user()->shift_active === 1) {
            $order = Order::find($id);

            $order->state = $request->get('state');

            if ($order->responsible_cook_id === null) {
                $order->responsible_cook_id = Auth::id();
            }

            $order->save();
            return new OrderResource($order);
        }

        return response()->json(null, 401);
    }

    public function deliver($id)
    {
        $uid = Auth::user()->id;
        $order = Order::whereIn('meal_id', function($query) use ($uid){
            $query->select('id')
                ->from(with(new Meal)->getTable())
                ->where('responsible_waiter_id', $uid);
        })->where('orders.state', 'prepared')->findOrFail($id);

        $order->state = 'delivered';
        $order->save();

        return response()->json(['status'=>'success'], 200);
    }

    public function store(Request $request)
    {
        if (Auth::user()->type !== 'waiter') {
            return response()->json(null, 401);
        }

        // firstOrFail
        $data = $request->validate([
          'itemName' => 'required|string',
          'mealId' => 'required|numeric',
        ]);

        if (!Meal::where('id', $data['mealId'])->exists()) {
            return response()->json(['error' => 'no meal'], 404);
        }

        $item = Item::where('name', $data['itemName'])->firstOrFail();

        $order = new Order;
        $order->state = 'pending';
        $order->item_id = $item->id;
        $order->meal_id = $data['mealId'];
        $order->start = Carbon::now();
        $order->save();

        return new OrderResource($order);
  }

    public function destroy($id)
    {
        $uid = Auth::user()->id;
        $order = Order::whereIn('meal_id', function($query) use ($uid){
            $query->select('id')
                ->from(with(new Meal)->getTable())
                ->where('responsible_waiter_id', $uid);
        })->where('state', 'pending')->findOrFail($id);

        $dtime = Carbon::now()->subSeconds(5);

        if($dtime < $order->created_at){
            $order->delete();
            return response()->json(null, 204);
        }

        return response()->json(null, 406);
    }

    public function confirm($id)
    {
        if (Auth::user()->type !== 'waiter') {
            return response()->json(null, 401);
        }

        $order = Order::findOrFail($id);

        if ($order->state === 'confirmed') {
            return response()->json(null, 202);
        }
        if (Auth::id() != $order->meal->responsible_waiter_id) {
            return response()->json(null, 401);
        }

        $order->state = 'confirmed';
        $order->save();

        return response()->json(null, 200);
    }
}
