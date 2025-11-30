<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToDataKriminalTable extends Migration
{
    public function up()
    {
        Schema::table('data_kriminal', function (Blueprint $table) {
            // TAMBAHKAN BARIS INI
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('data_kriminal', function (Blueprint $table) {
            // TAMBAHKAN BARIS INI
            $table->dropTimestamps();
        });
    }
}