<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaults', function (Blueprint $table) {
            $table->id();
            $table->string('vaultable_type')->nullable()->index();
            $table->unsignedBigInteger('vaultable_id')->nullable()->index();
            $table->string('name');
            $table->timestamps();

            $table->index(['vaultable_type', 'vaultable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vault');
    }
}
