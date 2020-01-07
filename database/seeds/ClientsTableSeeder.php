<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients=['ahmed','mohmed','ali'];
        foreach($clients as $client)
        {
        App\Client::create([
            'name'=>$client,
            'phone'=>06666,
            'address'=>'haram'
        ]);
     }
    }//end of run
}//end of seeder
