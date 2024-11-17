<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 225);
            $table->string('slug', 400);
            $table->integer('quantity')->default(1)->nullable();
            $table->longText('description')->nullable();
            $table->boolean('published')->default(1);
            $table->boolean('inStock')->default(1);
            $table->decimal('price', 10);

            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();

            $table->foreignIdFor(Location ::class, 'location_id')->nullable();
            $table->foreignIdFor(Category::class, 'category_id')->nullable();
            $table->softDeletes();
            $table->foreignIdFor(User::class, 'deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
