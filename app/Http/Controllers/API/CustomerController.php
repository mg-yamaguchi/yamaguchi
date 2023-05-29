<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerForList as CustomerForListResource;
use App\Http\Resources\CustomerForShow as CustomerForShowResource;
use App\Http\Resources\CustomerSelector as CustomerSelectorResource;

use App\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();
        return CustomerForListResource::collection($customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $count = Customer::where([
            ['name', $request->customer['name']]
        ])->count();
        if ($count) {
            return response()->json([
                'result' => false,
                'errorMessage' => '顧客名は既に登録されています。'
            ]);
        }
        DB::transaction(function () use ($request) {
            $customer = new Customer;
            $customer->name = $request->customer['name'];
            $customer->phonetic_name = $request->customer['phonetic_name'];

            $customer->save();
        });

        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return new CustomerForShowResource($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $count = Customer::where([
            ['id', '<>', $customer->id],
            ['name', $request->customer['name']]
        ])->count();
        if ($count) {
            return response()->json([
                'result' => false,
                'errorMessage' => '商品コードは既に登録されています。'
            ]);
        }
        DB::transaction(function () use ($request, $customer) {
            $customer->name = $request->customer['name'];
            $customer->phonetic_name = $request->customer['phonetic_name'];
            $customer->phonetic_name = $request->customer['phonetic_name'];

            $customer->save();
        });

        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        DB::transaction(function () use ($customer) {
            $customer->delete();
        });

        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * 検索用
     *
     * @return \Illuminate\Http\Response
     */
    public function selector(Request $request)
    {
            $customers = Customer::all();
        // $name = $request->name;
        // $phonetic_name = $request->phonetic_name;
        // $customers = Customer::when($name, function ($query) use ($name) {
        //         return $query->where('name', 'like', '%'.$name.'%');
        //     })
        //     ->when($phonetic_name, function ($query) use ($phonetic_name) {
        //         return $query->where('phonetic_name', 'like', '%'.$phonetic_name.'%');
        //     })
        //     ->get();
        return CustomerSelectorResource::collection($customers);
    }
}
