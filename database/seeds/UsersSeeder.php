<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    private $photoPath = 'public/profiles';
    private $typesOfUsers = ['manager', 'waiter', 'cook', 'cashier'];
    private $typesOfUsersPrefix = ['m', 'w', 'k', 'c'];
    private $numberOfUsers = [5,25,20,10];
    private $numberOfSoftDeletedUsers = [1,5,10,4];
    private $malePhotos = [];
    private $femalePhotos = [];
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->line("");
        $this->command->line("##################################################################################");
        $this->command->line("Creating Users");
        $this->command->line("##################################################################################");

        $totalUsers = 0;
        $contadorGlobal = 0;
        for ($typeOfUserIdx = 0; $typeOfUserIdx< 4; $typeOfUserIdx++) {
            $totalUsers += $this->numberOfUsers[$typeOfUserIdx];
            $totalUsers += $this->numberOfSoftDeletedUsers[$typeOfUserIdx];
        }
        $this->command->table(['Users table seeder notice'], [
            ['Profile photos will be stored on path '.storage_path('app/'.$this->photoPath)],
            ['Edit UsersSeeder.php file to change the storage path or the number of users']
        ]);


        Storage::deleteDirectory($this->photoPath);
        Storage::makeDirectory($this->photoPath);
        $faker = Faker\Factory::create('pt_PT');
        $this->files = collect(File::files(database_path('seeds/profile_photos')));
        $this->malePhotos = [];
        $this->femalePhotos = [];
        foreach ($this->files as $fileInfo) {
            $filename = $fileInfo->getFilename();
            if (strpos($filename, "M_") === 0) {
                $this->malePhotos[] = $filename;
            } else if (strpos($filename, "W_") === 0) {
                $this->femalePhotos[] = $filename;
            }
        }
        for ($typeOfUserIdx = 0; $typeOfUserIdx< 4; $typeOfUserIdx++) {
            for ($i = 0; $i < $this->numberOfUsers[$typeOfUserIdx]; $i++) {
                $contadorGlobal++;
                $user = $this->fakeUser($faker, $typeOfUserIdx, $i);
                DB::table('users')->insert($user);
                $this->command->info("Created User $contadorGlobal/$totalUsers: " . $user['name']);
            }
            for ($j = 0; $j < $this->numberOfSoftDeletedUsers[$typeOfUserIdx]; $j++) {
                $contadorGlobal++;
                $user = $this->fakeUser($faker, $typeOfUserIdx, $j + $i, true);
                DB::table('users')->insert($user);
                $this->command->info("Created User $contadorGlobal/$totalUsers: " . $user['name']);
            }
        }
    }

    private function copyProfilePhoto($filename)
    {
        $targetDir = storage_path('app/'.$this->photoPath);
        $newFileName = str_random(16) . ".jpg";
        File::copy(database_path('seeds/profile_photos')."/$filename", $targetDir . '/' . $newFileName);
        return $newFileName;
    }
 
    private function fakeUser(Faker\Generator $faker, $typeOfUserIdx, $idx, $softDelete = false)
    {
        $createdAt = Carbon\Carbon::now()->subDays(600);
        $lastShiftBase = Carbon\Carbon::now()->subDays(5);
        $lastShiftStart = $lastShiftBase->copy()->addMinutes(rand(0, 6600));
        $lastShiftEnd =  $lastShiftStart->copy()->addMinutes(rand(360, 600));
        $gender = $faker->randomElements(['male', 'female'])[0];
        if ($gender == 'male') {
            $filename= $faker->randomElements($this->malePhotos)[0];
            UsersSeeder::deleteArrayElement($filename, $this->malePhotos);
        } else {
            $filename= $faker->randomElements($this->femalePhotos)[0];
            UsersSeeder::deleteArrayElement($filename, $this->femalePhotos);
        }
        $newProfileFileName= $this->copyProfilePhoto($filename);
        return [
            'name' => $idx == 0 ? 'First ' . $this->typesOfUsers[$typeOfUserIdx] : $faker->name($gender),
            'username' => $this->typesOfUsersPrefix[$typeOfUserIdx].$idx,
            'email' => $this->typesOfUsersPrefix[$typeOfUserIdx].$idx.'@mail.pt',
            'email_verified_at' => $faker->dateTimeBetween($createdAt),
            'password' => bcrypt('123'),
            'type' => $this->typesOfUsers[$typeOfUserIdx],
            'blocked' => $softDelete? false : ($idx == 0 ? false : $idx % 9 == 0),
            'photo_url' => $newProfileFileName,
            'last_shift_start' => $lastShiftStart,
            'last_shift_end' => $lastShiftEnd,
            'shift_active' => $softDelete? false : $idx % 3 == 0,
            'remember_token' => str_random(10),
            'created_at' => $createdAt,
            'updated_at' => $faker->dateTimeBetween($createdAt),
            'deleted_at' => $softDelete ? $faker->dateTimeBetween($createdAt) : null,
        ];
    }

    private static function deleteArrayElement($element, &$array)
    {
        $index = array_search($element, $array);
        if ($index !== false) {
            unset($array[$index]);
        }
    }
}
