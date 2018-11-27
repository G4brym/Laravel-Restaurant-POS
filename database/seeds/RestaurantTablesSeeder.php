<?php

use Illuminate\Database\Seeder;

class RestaurantTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->line("");
        $this->command->line("##################################################################################");
        $this->command->line("Creating Restaurant Tables");
        $this->command->line("##################################################################################");

        $this->faker = Faker\Factory::create('pt_PT');

        for ($i=1; $i <= 30; $i++) {
            $this->addTable($this->faker, $i);
        };
    }

    private function addTable(Faker\Generator $faker, $numTable)
    {
        $createdAt = Carbon\Carbon::now()->subDays(600);
        $table = [
            'table_number' => $numTable,
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween($createdAt),
            'deleted_at' => null,
        ];
        DB::table('restaurant_tables')->insert($table);
    }
}
