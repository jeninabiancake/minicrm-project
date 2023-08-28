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
        Schema::create('mailbox_tmp_receiver', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mailbox_id')->unsigned();
            $table->integer('receiver_id')->unsigned();
            $table->timestamps();
            $table->foreign('mailbox_id')->references('id')->on('mailbox')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mailbox_tmp_receiver');
    }
};
