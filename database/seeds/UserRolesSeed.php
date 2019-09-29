<?php

use Illuminate\Database\Seeder;

class UserRolesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Admin'],
            ['name' => 'Employee'],
	        ['name' => 'Client'],
        ];
        foreach ($data as $datum){
            $role = new \App\Models\UserRole();
            $role->fill($datum);
            $role->save();
        }
    }
}
