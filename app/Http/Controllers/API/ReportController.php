<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ReportDetailForList as ReportDetailForListResource;
use App\Http\Resources\ReportForShow as ReportForShowResource;
use App\Report;
use App\ReportDetail;
use App\Item;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Reader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Writer;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $started_from = $request->started_from;
        $started_to = $request->started_to;
        $search_line = $request->searchLine;
        $search_item_code = $request->searchItemCode;

        $line_names = Report::select('line_name')->where('line_name', '<>', null )->distinct()->pluck('line_name');
        // select('main')->distinct()->pluck('main')
        logger('$line_names:'.$line_names);

        $report_details = ReportDetail::join('reports', 'report_details.report_id', '=', 'reports.id')
        ->join('items', 'report_details.item_id', '=', 'items.id')
        ->when($started_from, function ($query) use ($started_from) {
            return $query->whereDate('reports.worked_on', '>=', $started_from);
        })
        ->when($started_to, function ($query) use ($started_to) {
            return $query->whereDate('reports.worked_on', '<=', $started_to);
        })
        ->when($search_item_code, function ($query) use ($search_item_code) {
            return $query->where('items.code', 'like', '%'.$search_item_code.'%');
            // return $query->where(function($query) use ($search_item){
            //     $query->orWhere('items.code', 'like', '%'.$search_item.'%')
            //           ->orWhere('items.name', 'like', '%'.$search_item.'%');
            //     });
        })
        ->when($search_line, function ($query) use ($search_line) {
            return $query->where('reports.line_name', 'like', '%'.$search_line.'%');
        })
        ->select('report_details.*', 'reports.line_name', 'reports.worked_on')
        ->orderBy('reports.worked_on')
        ->orderBy('report_details.id')
        ->get();

        
        return ReportDetailForListResource::collection($report_details);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $report = DB::transaction(function () use ($request) {
            $report = new Report;
            $report->line_name  = $request->report['line_name'];
            $report->worked_on = $request->report['worked_on'];
            $report->save();

            foreach ($request->report['report_details'] as $detail) {
                $report_detail = $report->report_details()->create([
                    // 'report_id'     => $report->id,
                    'item_id'       => isset($detail['item']['id'])?$detail['item']['id']:null,
                    'employee_id'   => $detail['employee_id'],
                    'is_oxygen_scavenger'   => $detail['is_oxygen_scavenger'],
                    'is_packaging_material' => $detail['is_packaging_material'],
                    'is_filling_gas'        => $detail['is_filling_gas'],
                    'workers_number'        => $detail['workers_number'],
                    'start_metal_detector_fe_check'   => $detail['start_metal_detector_fe_check'],
                    'start_metal_detector_sus_check'  => $detail['start_metal_detector_sus_check'],
                    'start_x_detector_fe_check'       => $detail['start_x_detector_fe_check'],
                    'start_x_detector_sus_check'      => $detail['start_x_detector_sus_check'],
                    'start_x_detector_gi_check'       => $detail['start_x_detector_gi_check'],
                    'start_x_detector_pvc_check'      => $detail['start_x_detector_pvc_check'],
                    'started_on'    => $detail['started_on'],
                    'finished_on'   => $detail['finished_on'],
                    'pass_amount'   => $detail['pass_amount'],
                    'repack_amount' => $detail['repack_amount'],
                    'lightweight'   => $detail['lightweight'],
                    'appearance'    => $detail['appearance'],
                    'metal_removal' => $detail['metal_removal'],
                    'x_removal'     => $detail['x_removal'],
                    'stop_metal_detector_fe_check'    => $detail['stop_metal_detector_fe_check'],
                    'stop_metal_detector_sus_check'   => $detail['stop_metal_detector_sus_check'],
                    'stop_x_detector_fe_check'        => $detail['stop_x_detector_fe_check'],
                    'stop_x_detector_sus_check'       => $detail['stop_x_detector_sus_check'],
                    'stop_x_detector_gi_check'        => $detail['stop_x_detector_gi_check'],
                    'stop_x_detector_pvc_check'       => $detail['stop_x_detector_pvc_check'],
                    'state'         => $detail['state'],
                    'is_finished'   => $detail['is_finished'],
                ]);
            }
            return $report;
            // $item_ids = $this->getItemIds($report->report_details);
            // $item_ids = array_unique($item_ids);
            // $response = $this->registrationToKintone($report, $item_ids);
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
    public function show(Report $report)
    {
        return new ReportForShowResource($report);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        // $before_change_details = $this->getReportDetailIds($report->report_details);
        
        $report = DB::transaction(function () use ($request, $report) {
            // $after_change_items  = [];
            $report->line_name  = $request->report['line_name'];
            $report->worked_on = $request->report['worked_on'];
            $report->save();

            $report->report_details()->delete();
            foreach ($request->report['report_details'] as $detail) {
                $report_detail = $report->report_details()->create([
                    // 'report_id'     => $report->id,
                    'item_id'       => isset($detail['item']['id'])?$detail['item']['id']:null,
                    'employee_id'   => $detail['employee_id'],
                    'is_oxygen_scavenger'   => $detail['is_oxygen_scavenger'],
                    'is_packaging_material' => $detail['is_packaging_material'],
                    'is_filling_gas'        => $detail['is_filling_gas'],
                    'workers_number'        => $detail['workers_number'],
                    'start_metal_detector_fe_check'   => $detail['start_metal_detector_fe_check'],
                    'start_metal_detector_sus_check'  => $detail['start_metal_detector_sus_check'],
                    'start_x_detector_fe_check'       => $detail['start_x_detector_fe_check'],
                    'start_x_detector_sus_check'      => $detail['start_x_detector_sus_check'],
                    'start_x_detector_gi_check'       => $detail['start_x_detector_gi_check'],
                    'start_x_detector_pvc_check'      => $detail['start_x_detector_pvc_check'],
                    'started_on'    => $detail['started_on'],
                    'finished_on'   => $detail['finished_on'],
                    'pass_amount'   => $detail['pass_amount'],
                    'repack_amount' => $detail['repack_amount'],
                    'lightweight'   => $detail['lightweight'],
                    'appearance'    => $detail['appearance'],
                    'metal_removal' => $detail['metal_removal'],
                    'x_removal'     => $detail['x_removal'],
                    'stop_metal_detector_fe_check'    => $detail['stop_metal_detector_fe_check'],
                    'stop_metal_detector_sus_check'   => $detail['stop_metal_detector_sus_check'],
                    'stop_x_detector_fe_check'        => $detail['stop_x_detector_fe_check'],
                    'stop_x_detector_sus_check'       => $detail['stop_x_detector_sus_check'],
                    'stop_x_detector_gi_check'        => $detail['stop_x_detector_gi_check'],
                    'stop_x_detector_pvc_check'       => $detail['stop_x_detector_pvc_check'],
                    'state'         => $detail['state'],
                    'is_finished'   => $detail['is_finished'],
                ]);
            }
            return $report;
            // $item_ids = $this->getItemIds($report->report_details);
            // $item_ids = array_unique($item_ids);
            // $response = $this->registrationToKintone($report, $item_ids);
        });
        // $item_ids = $before_change_items;
        // $report = Report::find($model->id);
        // $after_change_items = $this->getItemIds($report->report_details);
        // $item_ids = array_merge($item_ids, $after_change_items);
        // $item_ids = array_unique($item_ids);
        // $response = $this->registrationToKintone($model, $item_ids);

        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        DB::transaction(function () use ($report) {
            $report->report_details()->delete();
            $report->delete();
        });
        return response()->json([
            'result' => true,
        ]);
    }

    public function getReportDetailIds($report_details)
    {
        $report_detail_ids = [];
        foreach ($report_details as $report_detail) {
            array_push($report_detail_ids, $report_detail->id);
        }
        return $report_detail_ids;
    }

    /**
     * エクセルのダウンロード
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
        //
    }

    /**
     * ラインの獲得
     *
     * @return \Illuminate\Http\Response
     */
    public function lineSelector()
    { 
        return Report::select('line_name as name')->where('line_name', '<>', null )->distinct()->orderBy('line_name')->get();
    }
}
