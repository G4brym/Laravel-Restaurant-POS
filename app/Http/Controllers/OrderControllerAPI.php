<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order as OrderResource;

use Illuminate\Http\Request;
use App\Order;
use Illuminate\Support\Facades\Auth;

class OrderControllerAPI extends Controller
{
    public function index(Request $request)
    {
        $uid = Auth::user()->id;
        return OrderResource::collection(Order::join('meals', 'orders.meal_id', '=', 'meals.id')->where('meals.responsible_waiter_id', $uid)->orderBy('orders.updated_at', 'desc')->paginate(50));
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

    public function deliver($id)
    {
        $uid = Auth::user()->id;
        $order = Order::join('meals', 'orders.meal_id', '=', 'meals.id')->where('meals.responsible_waiter_id', $uid)->where('orders.state', 'prepared')->findOrFail($id);

        $order->state = 'delivered';
        $order->save();

        return response()->json(null, 200);
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
        $order = Order::where('state', 'pending')->findOrFail($id);
        $dtime = Carbon\Carbon::now()->subSeconds(5);

        if($dtime < $order->created_at){
            $order->delete();
            return response()->json(null, 204);
        }

        return response()->json(null, 406);
    }
}
