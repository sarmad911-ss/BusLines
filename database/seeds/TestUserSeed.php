<?php

use Illuminate\Database\Seeder;

class TestUserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'name' => 'User',
            'email' => 'isf.common@gmail.com',
            'password' => 'testuser',
            'role_id' => \App\Models\UserRole::USER,
        ];
        $user = new \App\Models\User;
        $user->fill($data);
        $user->save();
    }
}
