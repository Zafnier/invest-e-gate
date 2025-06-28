<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountAmountToUserRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add the 'discount_amount' column to the 'user_rewards' table
        Schema::table('user_rewards', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->nullable()->after('voucher_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the 'discount_amount' column if the migration is rolled back
        Schema::table('user_rewards', function (Blueprint $table) {
            $table->dropColumn('discount_amount');
        });
    }
}
