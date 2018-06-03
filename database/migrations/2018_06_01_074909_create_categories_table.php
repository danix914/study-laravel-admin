<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('description', 255)->nullable();
            $table->string('image_path', 255)->nullable();
            $table->unsignedTinyInteger('sort_num')->default(0);
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('created_at');
            $table->unsignedInteger('updated_at');
            $table->unsignedInteger('deleted_at')->nullable();

            $table->index(['parent_id', 'sort_num'], 'parent_id__sort_num');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('parent_id__sort_num');
        });
        Schema::dropIfExists('categories');
    }
}
