<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function Orders()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function OrderDetails($order_id)
    {
        $order = Order::find($order_id);
        $orderItems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(10);
        $transaction = Transaction::where('order_id', $order_id)->first();

        return view('user.order-details', compact('order', 'orderItems', 'transaction'));
    }

    public function OrderCancel(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = 'canceled';
        $order->canceled_date = Carbon::now();
        $order->save();

        $transaction = Transaction::where('order_id', $request->order_id)->first();
        $transaction->status = 'declined';
        $transaction->save();

        return back()->with('status', 'Order cancelled successfully');
    }

    public function Address()
    {
        $addresses = Address::where('user_id', Auth::user()->id)->paginate(3);
        return view('user.account-address', compact('addresses'));
    }

    public function AddressCreate()
    {
        return view('user.account-address-create');
    }

    public function AddressStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|numeric|digits:11',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required|numeric|digits:6',
            'locality' => 'required',
            'landmark' => 'required'
        ]);

        $address = new Address();
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->zip = $request->zip;
        $address->locality = $request->locality;
        $address->landmark = $request->landmark;
        $address->country = 'Turkey';
        $address->save();

        return redirect()->route('user.address')->with('status', 'Address added successfully');
    }

    public function AddressEdit($address_id)
    {
        $address = Address::find($address_id);
        return view('user.account-address-edit', compact('address'));
    }

    public function AddressUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|numeric|digits:11',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required|numeric|digits:6',
            'locality' => 'required',
            'landmark' => 'required'
        ]);

        $address = Address::find($request->address_id);
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->zip = $request->zip;
        $address->locality = $request->locality;
        $address->landmark = $request->landmark;
        $address->save();

        return redirect()->route('user.address')->with('status', 'Address updated successfully');

    }


    public function AddressDefault(Request $request, $address_id)
    {
       $addresses = Address::where('default_address', 1)->where('user_id', Auth::user()->id)->get();

       foreach ($addresses as $address) {
           $address->default_address = 0;
           $address->save();
       }

       $address = Address::find($address_id);
       if ($address)
       {
           $address->default_address = 1;
           $address->save();
       }

    }
}
