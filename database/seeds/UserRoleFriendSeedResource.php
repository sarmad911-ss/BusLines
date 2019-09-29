<?php

use Illuminate\Database\Seeder;

class UserRoleFriendSeedResource extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 4, 'name' => 'Friend'],
        ];
        foreach ($data as $datum){
            $role = new \App\Models\UserRole();
            $role->fill($datum);
            $role->save();
        }
    }
}
