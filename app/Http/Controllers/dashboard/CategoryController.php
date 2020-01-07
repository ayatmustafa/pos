<?php

namespace App\Http\Controllers\dashboard;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::when($request->search, function ($q) use ($request) {

            return $q->whereTranslationLike('name' ,'%'. $request->search .'%');

        })->latest()->paginate(5);
        return view('dashboard.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.categories.create');

    }


    public function store(Request $request)
    {


        //using validation of unique in new way ^_^
        /*
 $rules+=[$local.'.name'=>'required',Rule::unique('category_translations','name')];
*/

        //using validation of unique in old way ^_^
        $rules=[];
        foreach(config('translatable.locales') as $local){
            $rules+=[$local.'.name'=>'required|unique:category_translations,name'];
        }

        $request->validate($rules);

        Category::create($request->all());

        return redirect('dashboard/categories');
    }



    public function edit(Category $category)
    {
        return view('dashboard.categories.edit',compact('category'));
    }


    public function update(Request $request,Category $category)
    {
        $rules=[];
        foreach(config('translatable.locales') as $local){
            $rules+=[$local.'.name'=>['required',Rule::unique('category_translations','name')->ignore($category->id,'category_id')]];
        }
        $request->validate($rules);

        $category->update($request->all());
        session()->flash('success', trans('site.updated_successfully'));

        return redirect()->route('dashboard.categories.index');

    }


    public function destroy(Category $category)
    {
        $category->delete();
        session()->flash('success', trans('site.deleted_successfully'));
        return redirect()->route('dashboard.categories.index');
    }
}
