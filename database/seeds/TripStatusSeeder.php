<?php

use Illuminate\Database\Seeder;

class TripStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'Notitzen'],
            ['id' => 2, 'name' => 'geplant'],
            ['id' => 3, 'name' => 'bestätigt'],
            ['id' => 4, 'name' => 'erledigte Fahrt'],
            ['id' => 5, 'name' => 'stornierte Fahrt'],
        ];
        foreach ($data as $datum){
            $status = new \App\Models\trip\TripStatus();
            $status->fill($datum);
            $status->save();
        }
    }
}
