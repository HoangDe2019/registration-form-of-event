<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discuss', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('issue_id')->nullable()->index('FK_discuss_issueId_issue_id');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('image_id')->nullable()->index('FK_image_id_fileId');
            $table->bigInteger('count_like')->nullable()->default(0);
            $table->unsignedTinyInteger('is_active')->nullable()->default(1);
            $table->bigInteger('parent_id')->nullable();
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
        Schema::dropIfExists('discuss');
    }
}
