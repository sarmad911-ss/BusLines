<?php

use Illuminate\Database\Seeder;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'name' => 'Admin',
            'email' => 'superadmin',
            'password' => 'adminsuper',
            'role_id' => \App\Models\UserRole::ADMIN,
        ];
        $user = new \App\Models\User;
        $user->fill($data);
        $user->save();
    }
}
