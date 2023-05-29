<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportDetailForShow extends JsonResource
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
            'item' => ($this->item_id) ? $this->item : '',
            'employee_id' => $this->employee_id,
            'employee' => $this->employee,
            'is_oxygen_scavenger' => $this->is_oxygen_scavenger,
            'is_packaging_material' => $this->is_packaging_material,
            'is_filling_gas' => $this->is_filling_gas,
            'workers_number' => $this->workers_number,
            'start_metal_detector_fe_check' => $this->start_metal_detector_fe_check,
            'start_metal_detector_sus_check' => $this->start_metal_detector_sus_check,
            'start_x_detector_fe_check' => $this->start_x_detector_fe_check,
            'start_x_detector_sus_check' => $this->start_x_detector_sus_check,
            'start_x_detector_gi_check' => $this->start_x_detector_gi_check,
            'start_x_detector_pvc_check' => $this->start_x_detector_pvc_check,
            'started_on' => $this->started_on,
            'finished_on' => $this->finished_on,
            'pass_amount' => $this->pass_amount,
            'repack_amount' => $this->repack_amount,
            'lightweight' => $this->lightweight,
            'appearance' => $this->appearance,
            'metal_removal' => $this->metal_removal,
            'x_removal' => $this->x_removal,
            'stop_metal_detector_fe_check' => $this->stop_metal_detector_fe_check,
            'stop_metal_detector_sus_check' => $this->stop_metal_detector_sus_check,
            'stop_x_detector_fe_check' => $this->stop_x_detector_fe_check,
            'stop_x_detector_sus_check' => $this->stop_x_detector_sus_check,
            'stop_x_detector_gi_check' => $this->stop_x_detector_gi_check,
            'stop_x_detector_pvc_check' => $this->stop_x_detector_pvc_check,
            'state' => $this->state,
            'is_finished' => $this->is_finished,
        ];
    }
}
