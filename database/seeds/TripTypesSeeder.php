<?php

use Illuminate\Database\Seeder;

class TripTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'Tour'],
            ['id' => 2, 'name' => 'Transport'],
        ];
        foreach ($data as $datum){
            $tripType = new \App\Models\trip\TripType();
            $tripType->fill($datum);
            $tripType->save();
        }
    }
}
