<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'facebook_id']);
            $table->string('firebase_uid')->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('firebase_uid');
            $table->string('google_id')->nullable();
            $table->string('facebook_id')->nullable();
        });
    }
};