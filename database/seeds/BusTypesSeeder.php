<?php

use Illuminate\Database\Seeder;

class BusTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'Omnibus'],
            ['id' => 2, 'name' => 'PKW/Kleinbus'],
        ];
        foreach ($data as $datum){
            $busType = new \App\Models\BusType();
            $busType->fill($datum);
            $busType->save();
        }
    }
}
