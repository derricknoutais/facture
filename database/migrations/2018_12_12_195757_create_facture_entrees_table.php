<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactureEntreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facture_entrees', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('facture_id');
            $table->foreign('facture_id')->references('id')->on('factures')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('quantité');
            $table->string('description');
            $table->unsignedInteger('prix_unitaire');
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
        Schema::dropIfExists('facture_entrees');
    }
}
