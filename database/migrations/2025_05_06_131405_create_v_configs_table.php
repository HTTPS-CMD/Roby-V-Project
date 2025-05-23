<?php

use App\Enums\OperatorEnum;
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
        Schema::create('v_configs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('server_id')->constrained('servers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('total')->nullable();
            $table->integer('usage')->nullable();
            $table->boolean('status')->default(true);
            $table->enum('operator', OperatorEnum::values())->default(OperatorEnum::All);
            $table->date('expire')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('v_configs');
    }
};
