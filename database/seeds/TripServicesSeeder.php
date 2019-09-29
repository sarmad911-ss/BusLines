<?php

use Illuminate\Database\Seeder;

class TripServicesSeeder extends Seeder
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
          ['name' => 'service1'],
          ['name' => 'service2'],
          ['name' => 'service3'],
        ];

        foreach ($data as $fillData){
            $service = new \App\Models\trip\TripService();
            $service->fill($fillData);
            $service->save();
        }
    }
}
