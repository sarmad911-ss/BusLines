<?php

use Illuminate\Database\Seeder;

class TransactionPurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
          ['id' => 1, 'name' => 'Reiseabrechnung'],
          ['id' => 2, 'name' => 'Off-Trip-Konto'],
          ['id' => 3, 'name' => 'Andere'],
        ];
        foreach ($data as $datum){
            $transactionPurpose = new \App\Models\TransactionPurpose();
            $transactionPurpose->fill($datum);
            $transactionPurpose->save();
        }
    }
}
