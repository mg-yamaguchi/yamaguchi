<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('item_id')->nullable()->comment('商品');
            $table->unsignedBigInteger('employee_id')->nullable()->comment('担当者');
            $table->boolean('is_oxygen_scavenger')->default(false)->comment('脱酸素剤');
            $table->boolean('is_packaging_material')->default(false)->comment('包装材料');
            $table->boolean('is_filling_gas')->default(false)->comment('充填ガス');
            $table->decimal('workers_number', 10, 2)->nullable()->comment('人員数');
            $table->boolean('start_metal_detector_fe_check')->default(false)->comment('開始 金属検出機 動作確認 Fe');
            $table->boolean('start_metal_detector_sus_check')->default(false)->comment('開始 金属検出機 動作確認 SUS');
            $table->boolean('start_x_detector_fe_check')->default(false)->comment('開始 X線異物検出機 動作確認 Fe');
            $table->boolean('start_x_detector_sus_check')->default(false)->comment('開始 X線異物検出機 動作確認 SUS');
            $table->boolean('start_x_detector_gi_check')->default(false)->comment('開始 X線異物検出機 動作確認 GI');
            $table->boolean('start_x_detector_pvc_check')->default(false)->comment('開始 X線異物検出機 動作確認 PVC');
            $table->time('started_on')->nullable()->comment('作業開始時間');
            $table->time('finished_on')->nullable()->comment('作業終了時間');
            $table->integer('pass_amount')->nullable()->comment('良品数');
            $table->integer('repack_amount')->nullable()->comment('リパック数');
            $table->integer('lightweight')->nullable()->comment('軽量');
            $table->integer('appearance')->nullable()->comment('外観等');
            $table->integer('metal_removal')->nullable()->comment('金属排除');
            $table->integer('x_removal')->nullable()->comment('X線排除');
            $table->boolean('stop_metal_detector_fe_check')->default(false)->comment('終了 金属検出機 動作確認 Fe');
            $table->boolean('stop_metal_detector_sus_check')->default(false)->comment('終了 金属検出機 動作確認 SUS');
            $table->boolean('stop_x_detector_fe_check')->default(false)->comment('終了 X線異物検出機 動作確認 Fe');
            $table->boolean('stop_x_detector_sus_check')->default(false)->comment('終了 X線異物検出機 動作確認 SUS');
            $table->boolean('stop_x_detector_gi_check')->default(false)->comment('終了 X線異物検出機 動作確認 GI');
            $table->boolean('stop_x_detector_pvc_check')->default(false)->comment('終了 X線異物検出機 動作確認 PVC');
            $table->string('state')->nullable()->comment('状態');
            $table->boolean('is_finished')->default(false)->comment('完了かどうか');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_details');
    }
}
