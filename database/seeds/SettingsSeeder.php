<?php

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings =  [
            ['key'=>"nds","value"=>0.19],
            ['key'=>"admin_by_km","value"=>0.5],
            ['key'=>"tax_by_km","value"=>0.28],
            ['key'=>"fuel_cost","value"=>1.05],
            ['key'=>"driver_cost","value"=>150],
        ];
        foreach ($settings as $data){
        	Settings::set($data['key'],$data['value']);
        }
    }
}
