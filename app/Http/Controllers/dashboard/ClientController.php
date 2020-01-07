<?php

namespace App\Http\Controllers\dashboard;

use App\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function index( Request $request)
    {
        //
        $clients=Client::when($request->search,function($q)use($request){
            return $q->where('name','like','%'.$request->search.'%')
            ->orwhere('phone','like','%'.$request->search.'%')
            ->orwhere('address','like','%'.$request->search.'%');
        })->latest()->paginate(5);
        return view('dashboard.clients.index',compact('clients'));
    }


    public function create()
    {
        return view('dashboard.clients.create');
    }//end of create


    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'phone.0'=>'required',
            'address'=>'required',
        ]);
        $data=$request->all();
        $request->phone= array_filter($request->phone);
        $data['phone']=$request->phone;
        Client::create($data);
        session()->flash('success',trans('site.added_successfully'));
        return redirect()->route('dashboard.clients.index');
    }//end of store


    public function edit(Client $client)
    {
        return view('dashboard.clients.edit',compact('client'));
    }//end of edite

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name'=>'required',
            'phone.0'=>'required',
            'address'=>'required',
        ]);
        $data=$request->all();
        $request->phone= array_filter($request->phone);
        $data['phone']=$request->phone;

        $client->update($data);
        //dd($data);
        session()->flash('success',trans('site.added_successfully'));
        return redirect()->route('dashboard.clients.index');
    }//end of update

    public function destroy(Client $client)
    {
      $client->delete();
      session()->flash('success',trans('site.deleted_successfully'));
      return redirect()->route('dashboard.clients.index');

    }//end of destroy
}
