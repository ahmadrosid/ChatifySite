<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE EXTENSION IF NOT EXISTS vector;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
