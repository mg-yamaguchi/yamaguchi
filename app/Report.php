<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * 日付へキャストする属性
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
    
    /**
     * 日報詳細
     */
    public function report_details()
    {
        return $this->hasMany('App\ReportDetail');
    }
}
