<?php

use Illuminate\Database\Seeder;

class categoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories=['category one', 'category two', 'category three'];
        foreach($categories as $category)
        {
            $add=App\Category::create([
                'ar'=>['name'=>$category],
                'en'=>['name'=>$category]
            ]);
        }

    }//end of run
}//rnd of seeder
