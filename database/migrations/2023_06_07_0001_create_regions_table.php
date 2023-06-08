<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->integer('id_reg')->autoIncrement();
            $table->string('description', 90);
            $table->enum('status', ['A', 'I', 'trash'])->default('A');
            $table->timestamps();
            $table->engine="MyISAM";
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists ("regions");
        Schema::enableForeignKeyConstraints ();
    }
};
