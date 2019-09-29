<?php

use Illuminate\Database\Seeder;

class TripConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //TODO
        $data = [
            ['name' => 'condition1'],
            ['name' => 'condition2'],
            ['name' => 'condition3'],
        ];

        foreach ($data as $fillData){
            $condition = new \App\Models\trip\TripCondition();
            $condition->fill($fillData);
            $condition->save();
        }
    }
}
