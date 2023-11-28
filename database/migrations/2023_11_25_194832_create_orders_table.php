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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amount')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->enum('type', [
                'sold',
                'rent'
            ]);
            $table->enum('status', [
                'started',
                'process',
                'ended'
            ]);
            $table->boolean('is_payed')->default(true);
            $table->timestamp('rent_start_date')->nullable()->default(null);
            $table->integer('rent_hour')->nullable()->default(null);
            $table->json('details')->nullable()->default(null);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
