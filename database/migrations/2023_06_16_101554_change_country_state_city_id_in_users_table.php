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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId("country")->default(0)->change();
            $table->foreignId("state")->default(0)->change();
            $table->foreignId("city")->default(0)->change();
            $table->renameColumn("country", "country_id");
            $table->renameColumn("state", "state_id");
            $table->renameColumn("city", "city_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("country_id")->change();
            $table->string("state_id")->change();
            $table->string("city_id")->change();
            $table->renameColumn("country_id", "country");
            $table->renameColumn("state_id", "state");
            $table->renameColumn("city_id", "city");
        });
    }
};
