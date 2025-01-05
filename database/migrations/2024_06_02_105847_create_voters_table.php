<?php

use App\Helpers\Model\VoterHelper;
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
        Schema::create('voters', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('election_session_id');
            $table->string('nik', 16);
            $table->string('full_name', 255);
            $table->date('birth_date');
            $table->text('address');
            $table->string('gender', 10);
            $table->string('otp', 50);
            $table->string('otp_status', 10)->default(VoterHelper::OTP_STATUSES['not_used']);
            $table->uuid('selected_candidate_pair_id')->nullable();
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
        Schema::dropIfExists('voters');
    }
};
