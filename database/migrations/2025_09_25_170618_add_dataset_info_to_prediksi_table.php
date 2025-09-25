<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatasetInfoToPrediksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prediksi', function (Blueprint $table) {
            $table->text('dataset_info')->nullable()->after('mape');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prediksi', function (Blueprint $table) {
            $table->dropColumn('dataset_info');
        });
    }
}