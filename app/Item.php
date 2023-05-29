<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use SoftDeletes;

    /**
     * 商品マスタと完工品マスタの混合
     * type:1=商品マスタ、type:2=完工品マスタ
     *
     * @return void
     */
    // public static function selector()
    // {
    //     $first = DB::table('items')->select('id', 'name', DB::raw('1 as type'));
    //     return DB::table('products')->select('id', 'name', DB::raw('2 as type'))
    //         ->union($first)
    //         ->get();
    // }
}
