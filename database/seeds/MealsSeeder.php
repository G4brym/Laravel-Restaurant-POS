<?php

use App\User;
use Illuminate\Database\Seeder;

class MealsSeeder extends Seeder
{
    private $activeOrderStates;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->line("");
        $this->command->line("##################################################################################");
        $this->command->line("Creating Meals");
        $this->command->line("##################################################################################");

        $totalDays = $this->command->ask('Create meals for how many days (prior to today)?', 400);
        $averageMealsperDay = $this->command->ask('On average create how many meal per day?', 100);

        $faker = Faker\Factory::create('pt_PT');

        $this->activeOrderStates = collect(['confirmed', 'in preparation', 'prepared']);
        
        $today = Carbon\Carbon::today();
        $day = Carbon\Carbon::today()->subDays($totalDays-1);  //Create meals for today also
        while ($day <= $today) {
            if ($day == $today) {
                $this->buildMealsForOneDay($faker, $day, Carbon\Carbon::now(), $averageMealsperDay, true);
            } else {
                $this->buildMealsForOneDay($faker, $day->copy(), $day->copy()->addMinutes(1439), $averageMealsperDay);
            }
            $this->command->info("Built meals for '" . $day->toDateString() . "'");
            $day->addDay();
        }
        $this->buildInvoices($faker);
    }

    private function getItemPrice($id)
    {
        static $items;
        $items = $items != null ? $items : DB::table('items')->pluck('price', 'id');
        return $items[$id];
    }

    private function getRandomItem()
    {
        static $items;
        $items = $items != null ? $items : DB::table('items')->pluck('id');
        return $items->random();
    }

    private function getRandomTable()
    {
        static $tables;
        $tables = $tables != null ? $tables : DB::table('restaurant_tables')->pluck('table_number');
        return $tables->random();
    }

    private function getRandomWaiter()
    {
        static $waiters;
        $waiters = $waiters != null ? $waiters : DB::table('users')->where('type', 'waiter')->pluck('id');
        return $waiters->random();
    }

    private function getRandomCook()
    {
        static $cooks;
        $cooks = $cooks != null ? $cooks : DB::table('users')->where('type', 'cook')->pluck('id');
        return $cooks->random();
    }

    private function buildMealsForOneDay(Faker\Generator $faker, $minDate, $maxDate, $avgMealsDay, $allowActive = false)
    {
        $totalMeals = intval($avgMealsDay * 0.7 + rand(0, $avgMealsDay * 0.6));
        for ($i=0; $i<$totalMeals; $i++) {
            $this->buildOneMeal($faker, $faker->dateTimeBetween($minDate, $maxDate), $allowActive ? rand(0, 5) == 0 : false);
        }
    }

    private function buildOneMeal(Faker\Generator $faker, $dateTime, $active)
    {
        $createdAt = Carbon\Carbon::parse($dateTime->format(DATE_ISO8601));
        $endedAt = $createdAt->copy()->addSeconds(rand(300, 5600));
        $meal = [
            'state' => $active ? 'active' : 'terminated',
            'table_number' => $this->getRandomTable(),
            'start' => $createdAt->copy()->addSeconds(3),
            'end' => $active ? null : $endedAt,
            'responsible_waiter_id' => $this->getRandomWaiter(),
            'total_price_preview' => 0,
            'created_at' => $createdAt,
            'updated_at' => $createdAt->copy()->addSeconds(rand(300, 5600))
        ];
        $newId = DB::table('meals')->insertGetId($meal);
        $totalPrice = $this->buildOrdersForMeal($faker, $newId, $createdAt, $endedAt, $active);
        DB::table('meals')->where('id', $newId)->update(['total_price_preview' => $totalPrice]);
    }

    private function buildOrdersForMeal(Faker\Generator $faker, $idMeal, $minDate, $maxDate, $active = false)
    {
        $totalPrice = 0;
        $orders = [];
        $totalOrders = rand(3, 50);
        for ($i= 0; $i< $totalOrders; $i++) {
            $startDate = new Carbon\Carbon(($faker->dateTimeBetween($minDate, $maxDate))->format(DATE_ISO8601));
            $endDate = $faker->dateTimeBetween($startDate, $maxDate);
            $newOrder = [
                'state' => $active ? $this->activeOrderStates->random() : (rand(0, 20) == 0 ? 'not delivered' : 'delivered'),
                'item_id' => $this->getRandomItem(),
                'meal_id' => $idMeal,
                'responsible_cook_id' => $this->getRandomCook(),
                'start' => $startDate,
                'end' => $active ? null : $endDate,
                'created_at' => $startDate,
                'updated_at' => $endDate
            ];
            if ($newOrder['state'] != 'not delivered') {
                $totalPrice += $this->getItemPrice($newOrder['item_id']);
            }
            $orders[] = $newOrder;
        }
        DB::table('orders')->insert($orders);
        return $totalPrice;
    }


    private function buildInvoices(Faker\Generator $faker)
    {
        $this->command->line("");
        $this->command->line("##################################################################################");
        $this->command->line("Creating Invoices");
        $this->command->line("##################################################################################");

        $terminatedMealsCount = DB::table('meals')->where('state', 'terminated')->count();
        $terminatedMeals = DB::table('meals')->where('state', 'terminated')->get();
        $contador = 0;
        foreach ($terminatedMeals as $meal) {
            $paid = $this->createInvoice($faker, $meal);
            DB::table('meals')->where('id', $meal->id)->update(['state' => $paid ? 'paid' : 'not paid']);
            $contador++;
            if (($contador == $terminatedMealsCount) || ($contador % 10 == 0)) {
                $this->command->info("Created Invoice $contador of $terminatedMealsCount");
            }
        }
    }

    private function createInvoice(Faker\Generator $faker, $meal)
    {
        $notPaid = rand(0, 100) == 0;

        $createdAt = Carbon\Carbon::parse($meal->end);
        $date = Carbon\Carbon::parse($createdAt->toDateString());
        $updatedAt = $createdAt->copy()->addSeconds(rand(30, 300));

        $invoice = [
            'state' => $notPaid ? 'not paid' : 'paid',
            'meal_id' => $meal->id,
            'nif' => $faker->randomNumber(9),
            'name' => $faker->name(),
            'date' => $date,
            'total_price' => $meal->total_price_preview,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ];

        $newId = DB::table('invoices')->insertGetId($invoice);
        $total = $this->createInvoiceItems($newId, $meal->id);
        if (round($total, 2) != round($invoice['total_price'], 2)) {
            $this->command->error("Calculated total price of Invoice $newId ($total) is not equal to the total of associated meal " . $meal->id . " (".$invoice['total_price'].")");
        }
        return !$notPaid;
    }

    private function createInvoiceItems($invoiceId, $mealId)
    {
        $itemsConsumed = [];
        $total = 0;
        $orders = DB::table('orders')
                                ->select('item_id', DB::raw('COUNT(id) as qty'))
                                ->groupBy('item_id')
                                ->where([
                                        ['state', 'delivered'],
                                        ['meal_id', $mealId],
                                    ])
                                ->get();
        foreach ($orders as $order) {
            $precoUn = $this->getItemPrice($order->item_id);
            $item = [
                'invoice_id' => $invoiceId,
                'item_id' => $order->item_id,
                'quantity' => $order->qty,
                'unit_price' => $precoUn,
                'sub_total_price' => $order->qty * $precoUn
            ];
            $total += $item['sub_total_price'];
            $itemsConsumed[] = $item;
        }
        DB::table('invoice_items')->insert($itemsConsumed);
        return $total;
    }
}
