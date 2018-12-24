<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevisEntreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devis_entrees', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('devis_id');
            $table->foreign('devis_id')->references('id')->on('devis')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('devis_entrees');
    }
}
