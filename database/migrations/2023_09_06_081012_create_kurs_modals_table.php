<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKursModalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kurs_modals', function (Blueprint $table) {
            $table->id();
            $table->string('kursAdi');
            $table->text('aciklama');
            $table->date('baslangicTarihi');
            $table->date('bitisTarihi');
            
            $table->integer('kursKurumId');
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
        Schema::dropIfExists('kurs_modals');
    }
}
