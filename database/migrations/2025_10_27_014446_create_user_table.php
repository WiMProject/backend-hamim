<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('email',255)->unique();
            $table->string('password',100);
            $table->string('phone_number',25)->unique();
            $table->string('address',255)->nullable();
            $table->string('profile_picture',500)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            //$table->string('role')->default('user');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
