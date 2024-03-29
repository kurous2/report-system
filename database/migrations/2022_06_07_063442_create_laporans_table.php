<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->string('subjek');
            $table->string('unit');
            $table->string('uraian', 4000);
            $table->string('solusi', 4000)->nullable();
            $table->string('gambar')->nullable();
            $table->string('status');
            $table->bigInteger('categories_id');
            $table->bigInteger('users_id');
            $table->bigInteger('vote')->nullable();
            $table->string('tanggapan', 4000)->nullable();
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
        Schema::dropIfExists('laporans');
    }
}
