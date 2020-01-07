<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use \Astrotomic\Translatable\Translatable;
    protected $guarded = [];
    public $translatedAttributes = ['name','description'];
    protected $appends=['image_path','profit_percent'];
    public function getProfitPercentAttribute(){
        $profit=$this->sale_price-$this->purchase_price;
        $profit_percent=$profit*100/$this->purchase_price;
        return number_format($profit_percent,2) ;
    }
    public function getImagePathAttribute()
    {
        return asset('uploads/product_images/' . $this->image);

    }//end of get image path

    public function category()
    {
        return $this->belongsTo(Category::class);

    }//end fo category
    public function orders()
    {
        return $this->belongsToMany(Order::class,'order_product');

    }//end fo order

}
