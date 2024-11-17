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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('type', 45);
            $table->string('address1', 255);
            $table->string('address2', 255)->nullable();
            $table->string('kelurahan', 45);
            $table->string('kecamatan', 45);
            $table->string('kota', 45);
            $table->string('provinsi', 45);
            $table->string('nomorTlp', 13);
            $table->string('ktp', 25);
            $table->string('name', 25);
            $table->boolean('isMain')->default(1);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
