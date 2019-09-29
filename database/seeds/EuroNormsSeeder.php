<?php

use Illuminate\Database\Seeder;

class EuroNormsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'Euro 3'],
            ['id' => 2, 'name' => 'Euro 4'],
            ['id' => 3, 'name' => 'Euro 5'],
            ['id' => 4, 'name' => 'Euro 6'],
        ];
        foreach ($data as $datum){
            $busType = new \App\Models\EuroNorm();
            $busType->fill($datum);
            $busType->save();
        }
    }
}
