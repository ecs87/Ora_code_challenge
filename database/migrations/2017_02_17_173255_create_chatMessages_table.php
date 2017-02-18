<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
			Schema::create('chat_messages', function ($table) {
				$table->increments('id');
				$table->string('message');
				$table->string('created_at');
				$table->integer('user_id');
				$table->integer('chat_id');
			});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
			Schema::drop('chat_messages'); //comment this out on initial setup
    }
}
