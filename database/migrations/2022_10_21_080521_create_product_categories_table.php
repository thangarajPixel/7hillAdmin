<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string( 'name' );
            $table->string( 'slug' );
            $table->unsignedBigInteger('parent_id')->default(0)->nullable();
            $table->unsignedBigInteger('industrial_id')->default(0)->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->longText('meta_title')->nullable();
            $table->longText('meta_keyword')->nullable();
            $table->longText('meta_description')->nullable();
            $table->enum( 'status', ['published', 'unpublished'])->default('published');
            $table->integer('order_by')->nullable();
            $table->unsignedBigInteger('added_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
}
