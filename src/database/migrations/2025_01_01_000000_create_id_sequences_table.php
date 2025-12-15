<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('id_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('model', 191);
            $table->string('prefix', 50);
            $table->date('seq_date');
            $table->string('id_column', 100);
            $table->unsignedBigInteger('counter')->default(0);
            $table->timestamps();

            $table->unique(
                ['model', 'prefix', 'seq_date', 'id_column'],
                'id_sequences_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('id_sequences');
    }
};
