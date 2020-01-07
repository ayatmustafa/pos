<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
class OrderConroller extends Controller
{
    public function index(Request $request){

        $orders=Order::wherehas('client',function($q)use ($request) {
            return $q->where('name','like','%'.$request->search.'%');

        })->latest()->paginate(5);
      //  $orders = Order::paginate(5);
        return view('dashboard.orders.index',compact('orders'));
    }//end of index s

    public function products(Order $order){
        $products=$order->products;
      return view('dashboard.orders._products',compact('products','order'));

    }
  public function destroy(Order $order)
    {
     foreach($order->products as $product) {

         $product->update([
             'stock'=>$product->stock + $product->pivot->quantity

         ]);
     }
    $order->delete();
    session()->flash('success',trans('site.deleted_successfully'));
    return redirect()->route('dashboard.orders.index');
    }
}//end of controller
