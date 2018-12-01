<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order as OrderResource;

use Illuminate\Http\Request;
use App\Order;

class OrderControllerAPI extends Controller
{
    public function index(Request $request)
    {
        return OrderResource::collection(Order::paginate(20));
    }

    public function waiter(Request $request)
    {
        $uid = Auth::user()->id;
        return OrderResource::collection(Order::where('meal.responsible_waiter_id', $uid)->where('state', 'pending')->orWhere('state', 'confirmed')->paginate(20));
    }

    public function show($id)
    {
        return new OrderResource(Order::find($id));
    }

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
//
//    public function update(Request $request, $id)
//    {
//        $request->validate([
//            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
//            'email' => 'required|email|unique:users,email,'.$id,
//            'age' => 'integer|between:18,75'
//        ]);
//        $user = User::findOrFail($id);
//        $user->update($request->all());
//        return new UserResource($user);
//    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(null, 204);
    }
}
