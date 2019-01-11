<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->string('numéro')->nullable();
            $table->unsignedInteger('etabli_par');
            $table->foreign('etabli_par')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('client_id')->nullable();
            $table->enum('etat', ['Ouvert', 'E.A.V', 'Validé', 'Rejetté', 'Annulé', 'E.A.P', 'Paiement Partiel', 'Payé', 'Impayé'])->nullable();
            $table->unsignedInteger('verifie_par')->nullable();
            $table->unsignedInteger('facture_id')->nullable();
            $table->longText('objet')->nullable();
            $table->date('échéance')->nullable();
            $table->date('date')->nullable();
            $table->boolean('taxable')->nullable();
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
        Schema::dropIfExists('factures');
    }
}
