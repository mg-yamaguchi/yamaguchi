<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReportDetail extends Model
{
    /**
     * 日付へキャストする属性
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    // protected $fillable = ['report_id'];
    protected $guarded = ['id'];

    /**
     * 商品
     */
    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    /**
     * 日報
     */
    public function report()
    {
        return $this->belongsTo('App\Report');
    }

    /**
     * 担当者を取得
     */
    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    /**
     * 生産時間を取得
    */
    public function getProductedTimeAttribute()
    {
        $started_on = Carbon::parse($this->started_on);
        $finished_on = Carbon::parse($this->finished_on);
        $diff_total_minutes = $started_on->diffInMinutes($finished_on);
        $diff_hours = floor($diff_total_minutes / 60);
        if ($diff_hours < 10) $diff_hours = '0'.$diff_hours;
        $diff_minutes = $diff_total_minutes - $diff_hours*60;
        if ($diff_minutes < 10) $diff_minutes = '0'.$diff_minutes;
        return $diff_hours.':'.$diff_minutes;
        // return $started_on->diffInMinutes($finished_on);
    }
}
