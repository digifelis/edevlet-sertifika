<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOgrencilerModalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ogrenciler_modals', function (Blueprint $table) {
            $table->id();
            $table->string('ogrenciAdi');
            $table->string('ogrenciSoyadi');
            $table->string('tcKimlikNo');
            $table->unsignedBigInteger('kurumId');
            $table->timestamps();
            $table->foreign("kurumId")->references("id")->on("kurum_modals")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ogrenciler_modals');
    }
}
