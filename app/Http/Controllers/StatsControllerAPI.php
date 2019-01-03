<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;

class StatsControllerAPI extends Controller
{

    public function index(Request $request)
    {
    	if ($request->has('type')) {
    		$stats = [];
			

    		if ($request->type == 'Waiters') {

    			$users = DB::table('users')->where('type', 'waiter')->get();
    			foreach ($users as $user) {
	    			$avgOrders = DB::select('Select AVG(dayOrders) AS "ordersPerDay" FROM (
													SELECT COUNT(*) AS "dayOrders" FROM orders JOIN meals ON orders.meal_id = meals.id WHERE meals.responsible_waiter_id = '.$user->id.' GROUP BY DATE(orders.start)
											) dayOrdersTable;');
	    			$avgMeals = DB::select('Select AVG(dayMeals) AS "mealsPerDay" FROM (
													SELECT COUNT(*) AS "dayMeals" FROM meals WHERE responsible_waiter_id = '.$user->id.' GROUP BY DATE(start)
											) dayMealsTable;');

				    array_push($stats,["id" => $user->id, "name" => $user->name, "type" => $user->type, "avgOrders" => $avgOrders, "avgMeals" => $avgMeals]);
    			}

    		} else if ($request->type == 'Cookers') {

    			$users = DB::table('users')->where('type', 'cook')->get();
    			foreach ($users as $user) {
	    			$avgOrders = DB::select('Select AVG(dayOrders) AS "ordersPerDay" FROM (
													SELECT COUNT(*) AS "dayOrders" FROM orders WHERE responsible_cook_id = '.$user->id.' GROUP BY DATE(orders.start)
											) dayOrdersTable;');
	    
				    array_push($stats,["id" => $user->id, "name" => $user->name, "type" => $user->type, "avgOrders" => $avgOrders]);
    			}

    		} else {
    			return response()->json("Type dont match", 400);
    		}

    		
    		

    		/*$avgPerDay = DB::select('Select AVG(dayOrders) FROM (
											SELECT COUNT(*) AS "dayOrders"
											FROM `orders`
											WHERE responsible_cook_id = '.$user'
											GROUP BY DATE(`start`) 
										) dayOrdersTable;');*/


    		
    	
    		$collection = collect($stats);
	        $currentPage = LengthAwarePaginator::resolveCurrentPage();
	        $perPage = 10;
	        $currentPage = $collection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
	        $paginated= new LengthAwarePaginator($currentPage , count($collection), $perPage);
	        $paginated->setPath($request->url());

	        return $paginated;

    	}
   	 
    }
  
}
