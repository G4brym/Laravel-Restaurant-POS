<?php

namespace App\Http\Controllers;

use App\Http\Resources\Meal as MealResource;

use Illuminate\Http\Request;
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

//            if ($request->has('states')) {
//                $pieces = explode(',', $request->get('states'));
//                foreach ($pieces as &$value) {
//                    $baseQuery = $baseQuery->where('state', $value);
//                }
//            }

            return MealResource::collection($baseQuery->paginate(50));
        } else {
            // TODO
        }
    }
}
