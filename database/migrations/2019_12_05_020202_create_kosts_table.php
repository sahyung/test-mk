<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kosts', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('user_id')->comment('kost\'s owner');
            $table->string('name')->nullable();
            $table->string('city')->nullable();
            $table->double('price')->nullable();
            $table->integer('available_room_count')->nullable();
            $table->integer('total_room_count')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kosts');
    }
}
