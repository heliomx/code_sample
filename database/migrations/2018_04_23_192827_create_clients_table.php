<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->char('radio_type', 1);
            $table->char('radio_name', '80');
            $table->char('business_category', '50');
            $table->bigInteger('cpf')->nullable();
            $table->bigInteger('cnpj')->nullable();
            $table->char('address', 50);
            $table->bigInteger('address_cep');
            $table->char('address_complement', 30)->nullable();
            $table->char('address_city', 30);
            $table->char('address_uf', 2);
            $table->char('tel', 15)->nullable();
            $table->char('tel_mobile', 15)->nullable();
            $table->char('tel_mobile_carrier', 15)->nullable();
            $table->char('site', 50)->nullable();
            $table->char('status', 1);
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
        Schema::dropIfExists('clients');
    }
}
