<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //DB::statement("SET foreign_key_checks=0");

        DB::table('restaurant_tables')->delete();
        DB::table('users')->delete();
        DB::table('items')->delete();
        DB::table('meals')->delete();
        DB::table('orders')->delete();
        DB::table('invoices')->delete();
        DB::table('invoice_items')->delete();

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 0');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 0');
        DB::statement('ALTER TABLE meals AUTO_INCREMENT = 0');
        DB::statement('ALTER TABLE orders AUTO_INCREMENT = 0');
        DB::statement('ALTER TABLE invoices AUTO_INCREMENT = 0');
        DB::statement('ALTER TABLE invoice_items AUTO_INCREMENT = 0');
        
        //DB::statement("SET foreign_key_checks=1");

        $this->call(RestaurantTablesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(ItemsSeeder::class);
        $this->call(MealsSeeder::class);
    }
}
