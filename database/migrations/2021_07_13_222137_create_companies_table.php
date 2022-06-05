<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('code', 20)->unique('code');
            $table->string('name', 100);
            $table->string('email', 100);
            $table->text('address');
            $table->string('tax', 45);
            $table->string('phone', 15);
            $table->text('description')->nullable();
            $table->bigInteger('avatar_id')->nullable();
            $table->text('avatar')->nullable();
            $table->unsignedTinyInteger('is_active')->default(1);
            $table->unsignedTinyInteger('deleted')->nullable()->default(0);
            $table->dateTime('created_at');
            $table->bigInteger('created_by');
            $table->dateTime('updated_at')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->bigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
