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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 191);
            $table->string('firstname', 191)->nullable();
            $table->string('lastname', 191)->nullable();
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 191);
            $table->string('address', 191)->nullable();
            $table->string('city', 191)->nullable();
            $table->string('country', 191)->nullable();
            $table->string('postal', 191)->nullable();
            $table->text('about', 191)->nullable();
            $table->rememberToken();
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
};
