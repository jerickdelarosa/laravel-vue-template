<?php

use Database\Seeders\BulsuCampusSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblBulsuCampuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_bulsu_campuses', function (Blueprint $table) {
            $table->id();
            $table->string('campus_name');
        });

        $seeder = new BulsuCampusSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_bulsu_campuses');
    }
}
