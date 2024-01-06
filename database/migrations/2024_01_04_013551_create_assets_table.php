<?php

use App\Models\User;
use App\Models\Portfolio;
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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Portfolio::class);
            $table->string('name');
            $table->decimal('value', 8, 2);
            $table->date('acquisition_date')->nullable();
            $table->decimal('quantity', 8, 2)->nullable();
            $table->integer('liquidity_days')->nullable();
            $table->date('liquidity_date')->nullable();
            $table->decimal('income_tax', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign('user_id');
        });
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign('portfolio_id');
        });
        Schema::dropIfExists('assets');
    }
};
