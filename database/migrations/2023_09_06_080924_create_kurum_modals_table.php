<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKurumModalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kurum_modals', function (Blueprint $table) {
            $table->id();
            $table->string('kurumAdi');
            $table->string("kurumKodu");
            $table->string("kullaniciAdi");
            $table->string("sifre");
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
        Schema::dropIfExists('kurum_modals');
    }
}
