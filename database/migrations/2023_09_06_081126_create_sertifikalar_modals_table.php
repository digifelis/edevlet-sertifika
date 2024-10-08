<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSertifikalarModalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sertifikalar_modals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ogrenciId');
            $table->unsignedBigInteger('kursId');
            $table->unsignedBigInteger('kurumId');
            $table->string('sertifikaNo')->nullable();
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
        Schema::dropIfExists('sertifikalar_modals');
    }
}
