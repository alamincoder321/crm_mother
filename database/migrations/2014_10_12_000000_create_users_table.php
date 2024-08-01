<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('phone', 15);
            $table->string('role');
            $table->string('image')->nullable();
            $table->integer('branch_id');
            $table->char('status', 1)->default('a');
            $table->string('action')->nullable()->comment('e=>entry u=>update d=>delete');
            $table->ipAddress('ip_address');
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
        Schema::dropIfExists('users');
    }
}
