<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('communes', function (Blueprint $table) {
            $table->integer('id_com')->autoIncrement();
            $table->integer("id_reg");
           	$table->foreign(["id_reg"])->references(["id_reg"])->on("regions")->onDelete("restrict")->onUpdate("cascade");
            $table->string ("description", 90);
            $table->enum("status", ["A","I","trash"])->default ("A")->comment("'status'");
            $table->engine="MyISAM";
         });
     }

    public function down ()
    {
        Schema::dropIfExists('customers');
    }
};
