<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mailbox_flags', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger("is_unread")->default(0);
            $table->tinyInteger("is_important")->default(0);
            $table->integer('mailbox_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->foreign('mailbox_id')->references('id')->on('mailbox')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mailbox_flags');
    }
};
