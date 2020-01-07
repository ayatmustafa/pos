<?php

namespace App\Http\Controllers\dashboard;
use Illuminate\Support\Facades\Storage;
use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use App\Role;
use Illuminate\Contracts\Validation\Rule as IlluminateRule;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $categories=Category::all();
        $products=Product::when($request->search , function($q) use ($request){
            return $q->whereTranslationLike('name' ,'%' . $request->search . '%');
        })->when($request->category_id,function($q)use ($request){
            return $q->where('category_id',$request->category_id);
        })->latest()->paginate(3);
        return view('dashboard.products.index',compact('products','categories'));
    }//end of index

    public function create()
    {

        $categories=Category::paginate(2);
        return view('dashboard.products.create',compact('categories'));

    }//end of create

    public function store(Request $request)
    {
        $rules=[
            'category_id'=>'required',
        ];
        foreach(config('translatable.locales') as $locale){
            $rules+=[$locale.'.name'=>'required|unique:product_translations,name'];
            $rules+=[$locale.'.description'=>'required|unique:product_translations,description'];

        }
        $rules+=[
            'purchase_price'=>'required',
            'sale_price'=>'required',
            'image'=>'mimes:jpeg,jpg,png',
            'stock'=>'required',
        ];
        $request->validate($rules);
        //dd($request->all());

        $data=$request->all();
        if($request->image){
            Image::make($request->image)
            ->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/product_images/' . $request->image->hashName()));
            //bet3mlo save w bndelha parameter of makan 2l save w mmken ndeha quality 2l sora kman zay keda
            //save(public_path('uploads/user_images/' . $request->image->hashName()),60)

        $data['image'] = $request->image->hashName();
      //  dd($data);


        }//end if
      //  dd($data);
        $data=Product::create($data);
        session()->flash('success',trans('site.added_successfully'));

        return redirect()->route('dashboard.products.index');

    }//end of store


    public function show(Product $product)
    {
        //
    }//end of show


    public function edit(Product $product)
    {
        $category=Category::all();
        return view('dashboard.products.edit',compact('product','category'));
    }//end of edit


    public function update(Request $request, Product $product)
    {
        $rules = [
            'category_id' => 'required'
        ];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('product_translations', 'name')->ignore($product->id, 'product_id')]];
            $rules += [$locale . '.description' => 'required'];

        }//end of  for each

        $rules += [
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'image'=>'mimes:jpeg,jpg,png| max:1000',
            'stock' => 'required',
        ];

        $request->validate($rules);

        $request_data = $request->all();

        if ($request->image) {


        if ($product->image != 'default.png') {

            Storage::disk('public_uploads')->delete('/product_images/' . $product->image);

        }//end of if

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();

        }//end of if

        $product->update($request_data);
        //dd($request_data);
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.products.index');

    }//end of update



    public function destroy(Product $product)
    {
        if ($product->image != 'default.png') {

            Storage::disk('public_uploads')->delete('/product_images/' . $product->image);

        }//end of if

        $product->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.products.index');
    }//end of destroy
}//end of controller
