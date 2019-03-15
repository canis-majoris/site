<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('region_id')->default(1);
            $table->integer('is_menu')->default(1);
            $table->integer('is_text')->default(1);
            $table->integer('status')->default(1);
            $table->string('index')->nullable();
            $table->string('root')->nullable();
            $table->string('locked')->default(1);
            $table->text('tags')->nullable();
            $table->text('short_text')->nullable();
            $table->longText('text')->nullable();
            $table->string('order')->nullable();
            $table->string('controller')->nullable();
            $table->string('method')->nullable();
            $table->string('param')->nullable();    
            $table->string('target')->nullable();
            $table->string('url')->nullable();
            $table->string('name')->nullable();
            $table->string('name_trans')->nullable();
            $table->string('alt')->nullable();
            $table->string('img')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
