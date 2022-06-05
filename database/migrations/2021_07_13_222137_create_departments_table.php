<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('code', 10)->nullable();
            $table->string('name', 50);
            $table->string('description', 500)->nullable();
            $table->unsignedTinyInteger('is_active')->nullable()->default(1);
            $table->unsignedTinyInteger('deleted')->nullable()->default(0);
            $table->dateTime('created_at');
            $table->string('created_by', 20)->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('updated_by', 20)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 20)->nullable();
            $table->bigInteger('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
