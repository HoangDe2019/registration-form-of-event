<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diseases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 20);
            $table->string('name', 100)->nullable();
            $table->string('symptom', 250)->nullable();
            $table->string('phase', 100)->nullable();
            $table->bigInteger('department_id')->nullable();
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
        Schema::dropIfExists('diseases');
    }
}
