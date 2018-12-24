<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order as OrderResource;

use App\Meal;
use Illuminate\Http\Request;
use App\Order;
use Illuminate\Support\Facades\Auth;

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
            $baseQuery = Order::whereIn('state', ['confirmed', 'in preparation'])
                              ->where('responsible_cook_id', $uid)
                              ->orWhereNull('responsible_cook_id')
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

// TODO: Diogo's Task
//
//    public function store(Request $request)
//    {
//        $request->validate([
//            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
//            'email' => 'required|email|unique:users,email',
//            'age' => 'integer|between:18,75',
//            'password' => 'min:3'
//        ]);
//        $user = new User();
//        $user->fill($request->all());
//        $user->password = Hash::make($user->password);
//        $user->save();
//        return response()->json(new UserResource($user), 201);
//    }

    public function destroy($id)
    {
        $uid = Auth::user()->id;
        $order = Order::whereIn('meal_id', function($query) use ($uid){
            $query->select('id')
                ->from(with(new Meal)->getTable())
                ->where('responsible_waiter_id', $uid);
        })->where('state', 'pending')->findOrFail($id);

        $dtime = Carbon\Carbon::now()->subSeconds(5);

        if($dtime < $order->created_at){
            $order->delete();
            return response()->json(null, 204);
        }

        return response()->json(null, 406);
    }
}
