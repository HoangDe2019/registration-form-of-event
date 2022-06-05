<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('degree', 200)->nullable();
            $table->string('college_or_universiy', 250)->nullable();
            $table->dateTime('year_of_completion')->nullable();
            $table->unsignedTinyInteger('is_active')->default(1);
            $table->unsignedTinyInteger('deleted')->nullable()->default(0);
            $table->dateTime('created_at');
            $table->string('created_by', 20)->nullable();
            $table->dateTime('updated_at')->nullable();
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
        Schema::dropIfExists('educations');
    }
}
