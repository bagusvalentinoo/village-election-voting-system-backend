<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candidate_pairs', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('election_session_id');
            $table->string('first_candidate_name');
            $table->string('second_candidate_name');
            $table->text('description')->nullable();
            $table->text('image_url')->nullable();
            $table->unsignedInteger('number');
            $table->unsignedBigInteger('total_vote');
            $table->timestamps();

            $table->foreign('election_session_id')
                ->references('id')
                ->on('election_sessions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_pairs');
    }
};
