<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("read_items", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table->unsignedBigInteger("item_id");
            $table
                ->foreign("item_id")
                ->references("id")
                ->on("items")
                ->onDelete("cascade");
            $table->unique(["user_id", "item_id"]);
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
        Schema::dropIfExists("read_items");
    }
}
