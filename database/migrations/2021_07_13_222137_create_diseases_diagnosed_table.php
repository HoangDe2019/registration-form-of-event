<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseasesDiagnosedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diseases_diagnosed', function (Blueprint $table) {
            $table->unsignedBigInteger('medical_history_id');
            $table->bigInteger('diseases_id')->nullable();
            $table->string('state', 100)->nullable();
            $table->string('description', 200)->nullable();
            $table->unsignedTinyInteger('is_active')->nullable();
            $table->unsignedTinyInteger('deleted')->nullable();
            $table->string('created_by', 20)->nullable();
            $table->timestamps();
            $table->string('updated_by', 20)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diseases_diagnosed');
    }
}
