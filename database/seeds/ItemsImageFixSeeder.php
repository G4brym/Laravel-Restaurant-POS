<?php

// Para executar este Seeder : php artisan db:seed --class=ItemsImageFixSeeder
use Illuminate\Database\Seeder;

class ItemsImageFixSeeder extends Seeder
{
    private $itemsPath = 'public/items';

    private $contadorGlobal = 0;
    private $totaItems = 60;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->line("");
        $this->command->line("##################################################################################");
        $this->command->line("Updating Items");
        $this->command->line("##################################################################################");

        Storage::deleteDirectory($this->itemsPath);
        Storage::makeDirectory($this->itemsPath);

        $this->faker = Faker\Factory::create('pt_PT');

        $this->updateItem('7-up.jpg', '7-Up');
        $this->updateItem('agua-das-pedras.jpg', 'Água das Pedras');
        $this->updateItem('agua.jpg', 'Água do Luso');
        $this->updateItem('aletria.jpeg', 'Aletria');
        $this->updateItem('alheira.jpg', 'Alheira');
        $this->updateItem('arroz-de-marisco.jpg', 'Arroz de Marisco');
        $this->updateItem('arroz-doce.jpg', 'Arroz Doce');
        $this->updateItem('bacalao-a-casa.jpg', 'Bacalhau à Casa');
        $this->updateItem('bacalhau-a-braz.jpg', 'Bacalhau à Braz');
        $this->updateItem('bacalhau-gomes-sa.jpg', 'Bacalhau à Gomes de Sá');
        $this->updateItem('batatas-fritas.jpg', 'Batatas Fritas');
        $this->updateItem('bife-vaca-grelhado.jpg', 'Bife de Vaca Grelhado');
        $this->updateItem('bitoque.jpg', 'Bitoque');
        $this->updateItem('bobo-camarao.jpg', 'Bobó de Camarão');
        $this->updateItem('caldeirada-frutos-do-mar.jpg', 'Caldeirada de Frutos do Mar');
        $this->updateItem('caldo-verde.jpg', 'Caldo Verde');
        $this->updateItem('calzone.jpg', 'Calzone');
        $this->updateItem('Carne-de-Porco-alentejana.jpg', 'Carne de Porco à Alentejana');
        $this->updateItem('cavala-grelhada.jpg', 'Cavala Grelhada');
        $this->updateItem('cerveja-sagres.jpg', 'Cerveja Sagres');
        $this->updateItem('coca-cola.jpg', 'Coca-Cola');
        $this->updateItem('compal-pera.jpg', 'Compal de Pera');
        $this->updateItem('compal-pessego.jpg', 'Compal de Pêssego');
        $this->updateItem('doca-casa.jpg', 'Doce da Casa');
        $this->updateItem('espaguete-carbonara.jpg', 'Esparguete à Carbonara');
        $this->updateItem('Espargete-alho.jpg', 'Esparguete de Alho', 'dish');
        $this->updateItem('esparguete-bolonhesa.jpg', 'Esparguete à Bolonhesa');
        $this->updateItem('fanta.jpg', 'Fanta');
        $this->updateItem('ide-tea.jpg', 'Ice-Tea');
        $this->updateItem('jardineira-de-vaca.jpg', 'Jardineira de Vaca');
        $this->updateItem('lasanha_a_bolonhesa.jpg', 'Lasanha à Bolonhesa');
        $this->updateItem('lasanha-peixe.jpg', 'Lasanha de Peixe');
        $this->updateItem('lulas-grelhadas.jpg', 'Lulas Grelhadas');
        $this->updateItem('lulas-recheadas.jpg', 'Lulas Recheadas');
        $this->updateItem('melao.jpg', 'Melão');
        $this->updateItem('mousse-chocolate.jpg', 'Mousse de Chocolate');
        $this->updateItem('naco-vaca.jpg', 'Naco de Vaca');
        $this->updateItem('perca-grelhada.jpg', 'Perca Grelhada');
        $this->updateItem('pizza.jpg', 'Pizza');
        $this->updateItem('polvo-a-lagareiro.jpg', 'Polvo à Lagareiro');
        $this->updateItem('pudim-ovos.jpg', 'Pudim de Ovos');
        $this->updateItem('robalo-grelhado.jpg', 'Robalo Grelhado');
        $this->updateItem('rosbife.jpg', 'Rosbife');
        $this->updateItem('salada-de-frutas.jpg', 'Salada de Frutas');
        $this->updateItem('salada-fria.jpg', 'Salada Fria');
        $this->updateItem('salmao-grelhado.jpg', 'Salmão Grelhado');
        $this->updateItem('sopa-de-legumes.jpg', 'Sopa de Legumes');
        $this->updateItem('sopa-peixe.jpg', 'Sopa de Peixe');
        $this->updateItem('strogonoff.jpg', 'Strogonoff');
        $this->updateItem('sumo-ananas.jpg', 'Sumo de Ananás');
        $this->updateItem('tigelada.jpg', 'Tigelada');
        $this->updateItem('vinho-branco-casa.jpg', 'Vinho Branco da Casa');
        $this->updateItem('vinho-branco-conde-ervideira.jpg', 'Vinho Branco Conde de Ervideira');
        $this->updateItem('vinho-branco-periquita.jpg', 'Vinho Branco Piriquita');
        $this->updateItem('vinho-tinto-borba.jpg', 'Vinho Tinto Borba');
        $this->updateItem('vinho-tinto-cartucha.jpg', 'Vinho Tinto Cartucha');
        $this->updateItem('vinho-tinto-casa.jpg', 'Vinho Tinto da Casa');
        $this->updateItem('vinho-tinto-dao.jpg', 'Vinho Tinto do Dão');
        $this->updateItem('vinho-tinto-douro.jpg', 'Vinho Tinto do Douro');
        $this->updateItem('vinho-verde-messias.jpg', 'Vinho Tinto Messias');
    }


    private function copyProfilePhoto($filename)
    {
        $targetDir = storage_path('app/'.$this->itemsPath);

        $newFileName = str_random(16) . ".jpg";

        File::copy(database_path('seeds/items_photos')."/$filename", $targetDir . '/' . $newFileName);
        return $newFileName;
    }


    private function updateItem($filename, $name)
    {
        $newFileName = $this->copyProfilePhoto($filename);

        $this->contadorGlobal ++;
        DB::table('items')
            ->where('name', $name)
            ->update(['photo_url' => $newFileName]);

        $this->command->info("Updated Item {$this->contadorGlobal}/{$this->totaItems}: " . $name);
    }
}
