<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("feed_user", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table->unsignedBigInteger("feed_id");
            $table
                ->foreign("feed_id")
                ->references("id")
                ->on("feeds")
                ->onDelete("cascade");
            $table->unique(["user_id", "feed_id"]);
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
        Schema::dropIfExists("feed_user");
    }
}
