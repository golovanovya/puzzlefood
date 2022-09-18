<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreate;
use App\Http\Requests\OrderUpdate;
use App\Http\Resources\OrderCollection;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new OrderCollection(Order::paginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderCreate $request)
    {
        $order = Order::create([
            'full_name' => $request->post('full_name'),
            'amount' => $request->post('amount'),
            'address' => $request->post('address'),
        ]);
        return response()->json($order, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderUpdate $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->full_name = $request->post('full_name', $order->full_name);
        $order->amount = $request->post('amount', $order->amount);
        $order->address = $request->post('address', $order->address);
        $order->save();
        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->noContent();
    }
}
