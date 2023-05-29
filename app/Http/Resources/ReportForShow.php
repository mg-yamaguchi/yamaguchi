<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ReportDetailForShow as ReportDetailForShowResource;

class ReportForShow extends JsonResource
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
            'worked_on' => $this->worked_on,
            'line_name' => $this->line_name,
            'report_details' => ReportDetailForShowResource::collection($this->report_details),
        ];
    }
}
