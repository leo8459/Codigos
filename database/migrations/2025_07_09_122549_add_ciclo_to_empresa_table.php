<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_xx_xx_add_ciclo_to_empresa_table.php
public function up()
{
    Schema::table('empresa', function (Blueprint $table) {
        $table->char('ciclo', 1)->default('A')->after('secuencia');
    });
}

public function down()
{
    Schema::table('empresa', function (Blueprint $table) {
        $table->dropColumn('ciclo');
    });
}
};
