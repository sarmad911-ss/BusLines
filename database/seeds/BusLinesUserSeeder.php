<?php

use Illuminate\Database\Seeder;

class BusLinesUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\Models\User();
        $user->name = 'Bus-lines';
        $user->password = 'buslines';
        $user->role_id = \App\Models\UserRole::FRIEND;
        $user->save();
    }
}
