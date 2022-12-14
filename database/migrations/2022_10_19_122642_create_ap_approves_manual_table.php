<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ap_approves_manuals', function (Blueprint $table) {
            $table->id();
            $table->string('booker_id');
            $table->string('user_id');
            $table->string('stuff_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('destination');
            $table->string('gender');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('book_number');
            $table->string('time_leave');
            $table->string('groups')->nullable();
            $table->string('day');
            $table->string('ap_date');
            $table->string('ap_type');
            $table->string('approve_td');
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
        Schema::dropIfExists('approves');
    }
};
