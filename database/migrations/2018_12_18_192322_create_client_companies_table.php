<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('company_id');

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('client_companies');
    }
}
