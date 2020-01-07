<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products=['pro one', 'pro two', 'pro three'];
        foreach($products as $product)
        {
            App\Product::create([
               'category_id'=>1,
               'ar'=>['name'=>$product,'description'=>$product.' description'],
               'en'=>['name'=>$product,'description'=>$product.' description'],
               'purchase_price'=>55,
               'sale_price'=>60,
               'stock'=>9

            ]);
        }
    }//end of run
}//end of seeder
