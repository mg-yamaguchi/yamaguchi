<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemForList as ItemForListResource;
use App\Http\Resources\ItemForShow as ItemForShowResource;
use App\Http\Resources\ItemSelector as ItemSelectorResource;

use App\Item;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();
        return ItemForListResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $count = Item::where([
            ['code', $request->item['code']]
        ])->count();
        if ($count) {
            return response()->json([
                'result' => false,
                'errorMessage' => '商品コードは既に登録されています。'
            ]);
        }
        DB::transaction(function () use ($request) {
            $item = new Item;
            $item->code = $request->item['code'];
            $item->name = $request->item['name'];
            $item->phonetic_name = $request->item['phonetic_name'];

            $item->save();
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
    public function show(Item $item)
    {
        return new ItemForShowResource($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $count = Item::where([
            ['id', '<>', $item->id],
            ['code', $request->item['code']]
        ])->count();
        if ($count) {
            return response()->json([
                'result' => false,
                'errorMessage' => '商品コードは既に登録されています。'
            ]);
        }
        DB::transaction(function () use ($request, $item) {
            $item->code = $request->item['code'];
            $item->name = $request->item['name'];
            $item->phonetic_name = $request->item['phonetic_name'];

            $item->save();
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
    public function destroy(Item $item)
    {
        DB::transaction(function () use ($item) {
            $item->delete();
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
        $code = $request->code;
        $name = $request->name;
        $items = Item::when($code, function ($query) use ($code) {
                return $query->where('code', 'like', '%'.$code.'%');
            })
            ->when($name, function ($query) use ($name) {
                return $query->where('name', 'like', '%'.$name.'%');
            })
            ->get();
        return ItemSelectorResource::collection($items);
    }
}
