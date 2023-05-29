<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    /**
     * 従業員のフルネーム取得
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name}";
    }

    public function getFullPhoneticNameAttribute()
    {
        return "{$this->last_phonetic_name} {$this->first_phonetic_name}";
    }

    /**
     * ユーザを取得
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * 工場を取得
     */
    public function factory()
    {
        return $this->belongsTo('App\Factory');
    }

    /**
     * 部署を取得
     */
    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    /**
     * タイムテーブルを取得
     */
    public function product_plans()
    {
        return $this->belongsToMany('App\ProductPlan', 'product_plan_employee')->withTimestamps();
    }
}
