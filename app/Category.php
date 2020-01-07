<?php

namespace App;
//use Dimsav\Translatable\Translatable;
use \Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
class Category extends Model
{
    use \Astrotomic\Translatable\Translatable;
    protected $guarded = [];
    public $translatedAttributes = ['name'];

    public function products(){
     //2 ways this first and commen and the other is seconde you cat press ctrl+click to open it's controller
    //return $this->hasMany('App\product');
        return $this->hasMany(Product::class);
    }

}
