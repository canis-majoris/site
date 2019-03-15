<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PagesTranslated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('region_id')->default(1);
            $table->integer('language_id')->nullable();
            $table->integer('page_id')->nullable();
            $table->integer('status')->default(1);
            $table->text('tags')->nullable();
            $table->text('short_text')->nullable();
            $table->longText('text')->nullable();
            $table->longText('seo_title')->nullable();
            $table->longText('seo_description')->nullable();
            $table->longText('seo_keywords')->nullable();
            $table->string('video')->nullable();    
            $table->string('source')->nullable();
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
        Schema::dropIfExists('pages_languages');
    }
}
