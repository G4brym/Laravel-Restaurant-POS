<?php

use Illuminate\Database\Seeder;

class ItemsSeeder extends Seeder
{
    private $itemsPath = 'public/items';

    private $contadorGlobal = 0;
    private $totaItems = 60;
    private $faker = null;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->line("");
        $this->command->line("##################################################################################");
        $this->command->line("Creating Items");
        $this->command->line("##################################################################################");

        Storage::deleteDirectory($this->itemsPath);
        Storage::makeDirectory($this->itemsPath);

        $this->faker = Faker\Factory::create('pt_PT');

        $this->addItem($this->faker, '7-up.jpg', '7-Up', 'drink', 1.4);
        $this->addItem($this->faker, 'agua-das-pedras.jpg', 'Água das Pedras', 'drink', 1.4);
        $this->addItem($this->faker, 'agua.jpg', 'Água do Luso', 'drink', 1.0);
        $this->addItem($this->faker, 'aletria.jpeg', 'Aletria', 'dish', 3.4, true);
        $this->addItem($this->faker, 'alheira.jpg', 'Alheira', 'dish', 9.9);
        $this->addItem($this->faker, 'arroz-de-marisco.jpg', 'Arroz de Marisco', 'dish', 19.9);
        $this->addItem($this->faker, 'arroz-doce.jpg', 'Arroz Doce', 'dish', 3.1);
        $this->addItem($this->faker, 'bacalao-a-casa.jpg', 'Bacalhau à Casa', 'dish', 12.9);
        $this->addItem($this->faker, 'bacalhau-a-braz.jpg', 'Bacalhau à Braz', 'dish', 9.0);
        $this->addItem($this->faker, 'bacalhau-gomes-sa.jpg', 'Bacalhau à Gomes de Sá', 'dish', 8.9);
        $this->addItem($this->faker, 'batatas-fritas.jpg', 'Batatas Fritas', 'dish', 2.5);
        $this->addItem($this->faker, 'bife-vaca-grelhado.jpg', 'Bife de Vaca Grelhado', 'dish', 14.5);
        $this->addItem($this->faker, 'bitoque.jpg', 'Bitoque', 'dish', 9.5);
        $this->addItem($this->faker, 'bobo-camarao.jpg', 'Bobó de Camarão', 'dish', 18.9);
        $this->addItem($this->faker, 'caldeirada-frutos-do-mar.jpg', 'Caldeirada de Frutos do Mar', 'dish', 21.5);
        $this->addItem($this->faker, 'caldo-verde.jpg', 'Caldo Verde', 'dish', 2.9);
        $this->addItem($this->faker, 'calzone.jpg', 'Calzone', 'dish', 14.3);
        $this->addItem($this->faker, 'Carne-de-Porco-alentejana.jpg', 'Carne de Porco à Alentejana', 'dish', 9.0);
        $this->addItem($this->faker, 'cavala-grelhada.jpg', 'Cavala Grelhada', 'dish', 8.0, true);
        $this->addItem($this->faker, 'cerveja-sagres.jpg', 'Cerveja Sagres', 'drink', 1.8);
        $this->addItem($this->faker, 'coca-cola.jpg', 'Coca-Cola', 'drink', 1.4);
        $this->addItem($this->faker, 'compal-pera.jpg', 'Compal de Pera', 'drink', 1.4);
        $this->addItem($this->faker, 'compal-pessego.jpg', 'Compal de Pêssego', 'drink', 1.4);
        $this->addItem($this->faker, 'doca-casa.jpg', 'Doce da Casa', 'dish', 3.9);
        $this->addItem($this->faker, 'espaguete-carbonara.jpg', 'Esparguete à Carbonara', 'dish', 11.2);
        $this->addItem($this->faker, 'Espargete-alho.jpg', 'Esparguete de Alho', 'dish', 3.9, true);
        $this->addItem($this->faker, 'esparguete-bolonhesa.jpg', 'Esparguete à Bolonhesa', 'dish', 8.0);
        $this->addItem($this->faker, 'fanta.jpg', 'Fanta', 'drink', 1.4);
        $this->addItem($this->faker, 'ide-tea.jpg', 'Ice-Tea', 'drink', 1.4);
        $this->addItem($this->faker, 'jardineira-de-vaca.jpg', 'Jardineira de Vaca', 'dish', 7.9);
        $this->addItem($this->faker, 'lasanha_a_bolonhesa.jpg', 'Lasanha à Bolonhesa', 'dish', 9.2);
        $this->addItem($this->faker, 'lasanha-peixe.jpg', 'Lasanha de Peixe', 'dish', 12.4);
        $this->addItem($this->faker, 'lulas-grelhadas.jpg', 'Lulas Grelhadas', 'dish', 11.9);
        $this->addItem($this->faker, 'lulas-recheadas.jpg', 'Lulas Recheadas', 'dish', 9.1);
        $this->addItem($this->faker, 'melao.jpg', 'Melão', 'dish', 2.5);
        $this->addItem($this->faker, 'mousse-chocolate.jpg', 'Mousse de Chocolate', 'dish', 3.9);
        $this->addItem($this->faker, 'naco-vaca.jpg', 'Naco de Vaca', 'dish', 19.0);
        $this->addItem($this->faker, 'perca-grelhada.jpg', 'Perca Grelhada', 'dish', 17.9);
        $this->addItem($this->faker, 'pizza.jpg', 'Pizza', 'dish', 6.8);
        $this->addItem($this->faker, 'polvo-a-lagareiro.jpg', 'Polvo à Lagareiro', 'dish', 17.5);
        $this->addItem($this->faker, 'pudim-ovos.jpg', 'Pudim de Ovos', 'dish', 3.9);
        $this->addItem($this->faker, 'robalo-grelhado.jpg', 'Robalo Grelhado', 'dish', 15.9);
        $this->addItem($this->faker, 'rosbife.jpg', 'Rosbife', 'dish', 16.5);
        $this->addItem($this->faker, 'salada-de-frutas.jpg', 'Salada de Frutas', 'dish', 2.5);
        $this->addItem($this->faker, 'salada-fria.jpg', 'Salada Fria', 'dish', 5.4);
        $this->addItem($this->faker, 'salmao-grelhado.jpg', 'Salmão Grelhado', 'dish', 13.3);
        $this->addItem($this->faker, 'sopa-de-legumes.jpg', 'Sopa de Legumes', 'dish', 2.5);
        $this->addItem($this->faker, 'sopa-peixe.jpg', 'Sopa de Peixe', 'dish', 3.9);
        $this->addItem($this->faker, 'strogonoff.jpg', 'Strogonoff', 'dish', 12.2);
        $this->addItem($this->faker, 'sumo-ananas.jpg', 'Sumo de Ananás', 'drink', 2.0);
        $this->addItem($this->faker, 'tigelada.jpg', 'Tigelada', 'dish', 3.4);
        $this->addItem($this->faker, 'vinho-branco-casa.jpg', 'Vinho Branco da Casa', 'drink', 2.5);
        $this->addItem($this->faker, 'vinho-branco-conde-ervideira.jpg', 'Vinho Branco Conde de Ervideira', 'drink', 18.3);
        $this->addItem($this->faker, 'vinho-branco-periquita.jpg', 'Vinho Branco Piriquita', 'drink', 11.1);
        $this->addItem($this->faker, 'vinho-tinto-borba.jpg', 'Vinho Tinto Borba', 'drink', 11.1);
        $this->addItem($this->faker, 'vinho-tinto-cartucha.jpg', 'Vinho Tinto Cartucha', 'drink', 14.5);
        $this->addItem($this->faker, 'vinho-tinto-casa.jpg', 'Vinho Tinto da Casa', 'drink', 2.5);
        $this->addItem($this->faker, 'vinho-tinto-dao.jpg', 'Vinho Tinto do Dão', 'drink', 8.0);
        $this->addItem($this->faker, 'vinho-tinto-douro.jpg', 'Vinho Tinto do Douro', 'drink', 9.5);
        $this->addItem($this->faker, 'vinho-verde-messias.jpg', 'Vinho Tinto Messias', 'drink', 7.8);
    }


    private function copyProfilePhoto($filename)
    {
        $targetDir = storage_path('app/'.$this->itemsPath);

        $newFileName = str_random(16) . ".jpg";

        File::copy(database_path('seeds/items_photos')."/$filename", $targetDir . '/' . $newFileName);
        return $newFileName;
    }


    private function addItem(Faker\Generator $faker, $filename, $name, $type, $price, $softDelete = false)
    {
        $newFileName = $this->copyProfilePhoto($filename);
        $createdAt = Carbon\Carbon::now()->subDays(600);
        $item = [
            'name' => $name,
            'type' => $type,
            'description' => $faker->realText(200),
            'photo_url' => $newFileName,
            'price' => $price,
            'created_at' => $createdAt,
            'updated_at' => $faker->dateTimeBetween($createdAt),
            'deleted_at' => $softDelete ? $this->faker->dateTimeBetween($createdAt) : null,
        ];
        $this->contadorGlobal ++;
        DB::table('items')->insert($item);
        $this->command->info("Created Item {$this->contadorGlobal}/{$this->totaItems}: " . $item['name']);
    }
}
