<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookBeforeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_before', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('medical_schedule_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name', 500)->nullable();
            $table->string('time', 100);
            $table->unsignedTinyInteger('state')->nullable();
            $table->unsignedTinyInteger('is_active')->nullable();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('deleted')->nullable()->default(0);
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
        Schema::dropIfExists('book_before');
    }
}
