<?php

namespace App\Http\Controllers;

use App\Http\Resources\Meal as MealResource;
use App\Http\Resources\Order as OrderResource;

use Illuminate\Http\Request;
use App\Order;
use App\Meal;
use Illuminate\Support\Facades\Auth;

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
}
