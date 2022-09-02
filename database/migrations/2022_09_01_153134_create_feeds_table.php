<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("feeds", function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("url")->unique();
            $table->string("rss_path")->nullable();
            $table->string("image", 500)->nullable();
            $table
                ->boolean("is_huge")
                ->default(false)
                ->comment(
                    "it means this feed has more than 100000 subscribers"
                );
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
        Schema::dropIfExists("feeds");
    }
}
