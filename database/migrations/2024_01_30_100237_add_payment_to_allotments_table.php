<?php

use App\Models\Payment;
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
        if (!Schema::hasColumn('allotments', 'payment_id')) {
            Schema::table('allotments', function (Blueprint $table) {
                //
                $table->foreignIdFor(Payment::class)->nullable()->default(null)->constrained();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('allotments', function (Blueprint $table) {
            //
            $table->dropColumn('payment_id');
            $table->dropConstrainedForeignIdFor(Payment::class);
        });
    }
};
