<?php

namespace App\Http\Controllers\Dashboard;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\User;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:read_users'])->only('index');
        $this->middleware(['permission:create_users'])->only('create');
        $this->middleware(['permission:update_users'])->only('edit');
        $this->middleware(['permission:delete_users'])->only('destroy');

    }//end of constructor
    public function index(Request $request)
    {
       /* if($request->search){
         $users=User::where('first_name', 'like', '%' . $request->search . '%')
         ->orWhere('last_name', 'like', '%' . $request->search . '%')
         ->get();
        }else{
            $users= User::whereRoleIs('admin')->get();
        }*/ //not perfect way so
        $users = User::whereRoleIs('admin')->where(function ($q) use ($request) {

            return $q->when($request->search, function ($query) use ($request) {

                return $query->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%');

            });

        })->latest()->paginate(3);

        return view('dashboard.users.index', compact('users'));

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('dashboard.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // dd($request->all());

        //
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'image'=>'image|mimes:jpeg,jpg,png| max:1000',
            'permissions' => 'required|min:1'
        ]);
        $data=$request->except(['password','password_confirmation', 'permissions', 'image'] );
        $data['password']=bcrypt($request->password);
        if($request->image){
            Image::make($request->image)
            ->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/user_images/' . $request->image->hashName()));
            //bet3mlo save w bndelha parameter of makan 2l save w mmken ndeha quality 2l sora kman zay keda
            //save(public_path('uploads/user_images/' . $request->image->hashName()),60)

        $data['image'] = $request->image->hashName();
      //  dd($data);


        }//end if
      //  dd($data);
        $user=User::create($data);
      //  dd($data);

        $user->attachRole('admin');
        $user->syncPermissions($request->permissions);

        session()->flash('success',trans('site.added_successfully'));

       return redirect()->route('dashboard.users.index');

       // return 'ggggg';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
       // $user=User::findOrFail($id);
        return view('dashboard.users.edit',compact('user'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'image'=>'image|mimes:jpeg,jpg,png| max:1000',
            'email' => ['required', Rule::unique('users')->ignore($user->id),],
            'permissions' => 'required|min:1'
        ]);
        $data=$request->except(['permissions','image'] );
       // $user=new User();
      //  $user=User::findOrFail($id);
      // $user->first_name=$request->first_name;
      // $user->last_name=$request->last_name;
       //$user->email=$request->email;
      // $user->save();
      if($request->image){
        if ($request->image != 'default.png') {

            Storage::disk('public_uploads')->delete('/user_images/' . $request->image);

        }//end of if
        Image::make($request->image)
        ->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('uploads/user_images/' . $request->image->hashName()));
        //bet3mlo save w bndelha parameter of makan 2l save w mmken ndeha quality 2l sora kman zay keda
        //save(public_path('uploads/user_images/' . $request->image->hashName()),60)

    $data['image'] = $request->image->hashName();
  //  dd($data);


    }//end if
      $user->update($data);
        $user->syncPermissions($request->permissions);

        session()->flash('success', trans('site.updated_successfully'));

       return redirect()->route('dashboard.users.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->image != 'default.png') {

            \Storage::disk('public_uploads')->delete('/user_images/' . $user->image);

        }//end of if
       // dd($user);
        $user->delete();
        session()->flash ('success', trans('site.deleted_successfully'));
        return redirect()->route('dashboard.users.index');
        //return"done";
        //
    }
}
