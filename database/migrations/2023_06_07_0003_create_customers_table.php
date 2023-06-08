<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->string('dni',45)->primary()->comment("Documento de Identidad");;
            $table->unsignedInteger("id_reg");
            $table->unsignedInteger("id_com");
           	$table->foreign("id_reg")->references("id_reg")->on("regions")->onDelete("restrict")->onUpdate("cascade");
          	$table->foreign("id_com")->references("id_com")->on("communes")->onDelete("restrict")->onUpdate("cascade");
            $table->string('email', 120)->unique()->comment("'Correo Electrónico'");
            $table->string('name', 45)->comment("'Nombre'");
            $table->string('last_name', 45)->comment("'Apellido'");;
            $table->string('address', 255)->nullable()->comment("'Dirección'");
            $table->enum('status',['A','I','trash'])->default('A')->comment("Estado del registro: A - Activo, I - Inactivo, trash - Registro eliminado");
            $table->timestamp("date_reg");
            $table->engine="MyISAM";
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('customers');
        Schema::enableForeignKeyConstraints();
    }

};
