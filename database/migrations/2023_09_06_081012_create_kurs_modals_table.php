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
        /*
        id	kursAdi	sertifikaAdi	baslik	dilKey	tur	aciklama	sablonDosyasi	
        baslangicTarihi	bitisTarihi	kursKurumId	created_at	updated_at	

        */
        Schema::create('kurs_modals', function (Blueprint $table) {
            $table->id();
            $table->string('kursAdi');
            $table->string('sertifikaAdi');
            $table->string('baslik');
            $table->string('dilKey');
            $table->string('tur');
            $table->date('sertifikaGecerlilikTarihi');
            $table->text('aciklama')->nullable();
            $table->string('sablonDosyasi')->nullable();
            $table->date('baslangicTarihi');
            $table->date('bitisTarihi');
            $table->unsignedBigInteger('kursKurumId');
            $table->timestamps();
            $table->foreign("kursKurumId")->references("id")->on("kurum_modals")->onDelete("cascade");
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
