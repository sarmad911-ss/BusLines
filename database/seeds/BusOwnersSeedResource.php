<?php

use Illuminate\Database\Seeder;

class BusOwnersSeedResource extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
          ['name' => 'P. Kigels', 'role_id'=> \App\Models\UserRole::FRIEND, 'password' => 'friend']
        ];
        foreach ($data as $datum){
            $user = new \App\Models\User();
            $user->fill($datum);
            $user->save();
        }
    }
}
