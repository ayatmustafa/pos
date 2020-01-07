<?php

namespace App\Http\Controllers\dashboard\client;

use App\Http\Controllers\Controller;
use App\Order;
use App\Client;
use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class OrderController extends Controller
{

    public function index()
    {
        //
    }//end of index

    public function create(Client $client)
    {

        $categories=Category::with('products')->paginate(2);
        $orders=$client->orders()->paginate(4);
       // dd($orders);
        return view('dashboard.clients.orders.create',\compact('categories','client','orders'));
    }//end of create


    public function store(Request $request,Client $client)
    {

      //  dd($request->all());
        $request->validate([
            'products'=>'required|array',
          //  'quanities'=>'required|array',
        ]);
       // dd($request->all());
       $this->attach_order($client,$request);

       session()->flash('success',trans('site.added_successfully'));
        return redirect()->route('dashboard.orders.index');
    }//end of store




    public function edit(Client $client, Order $order)
    {
        $categories = Category::with('products')->get();
        $orders = $client->orders()->with('products')->paginate(5);
        return view('dashboard.clients.orders.edit', compact('client', 'order', 'categories','orders'));

    }//end of edit


    public function update(Request $request,Client $client, Order $order)
    {
        $request->validate([
            'products'=>'required|array',
          //  'quanities'=>'required|array',
        ]);
        $this->dettach_order($order);
        $this->attach_order($client, $request);
        session()->flash('success',trans('site.updated_successfully'));
        return redirect()->route('dashboard.orders.index');
    }//end of update


    public function destroy(Order $order,Client $client)
    {
        //
    }//end of destroy


    private function attach_order($client,$request){
        $order = $client->orders()->create([]);

        $order->products()->attach($request->products);
           $total_price=0;
                foreach($request->products as $id=>$quantity){
                    $product=Product::FindOrFail($id);

                    $total_price+=($product->sale_price*$quantity['quantity']);
                   // $order->products()->attach($id,$quantity['quantity']);

                    $product->update([
                        'stock'=>$product->stock - $quantity['quantity'],
                    ]);
                }

                $order->update([
                    'total_price'=>$total_price,

                ]);

    }
    private function dettach_order($order){
        foreach($order->products as $product) {

            $product->update([
                'stock'=>$product->stock + $product->pivot->quantity

            ]);
        }
       $order->delete();
       session()->flash('success',trans('site.deleted_successfully'));
       return redirect()->route('dashboard.orders.index');
    }

}
