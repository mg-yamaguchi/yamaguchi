<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportDetailForList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'report_id' => $this->report_id,
            'worked_on' => $this->report->worked_on,
            'producted_time' => $this->producted_time,
            'item_name' => ($this->item_id)? $this->item->name : '',
            'line_name' => $this->line_name,
            'workers_number' => $this->workers_number,
            'started_on' => $this->started_on,
            'pass_amount' => $this->pass_amount,
            'state' => $this->state,
        ];
    }
}
