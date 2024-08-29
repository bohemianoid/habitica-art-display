<?php

use App\Models\User;
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
        Schema::create('quests', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class);
            $table->tinyText('key');
            $table->text('boss_name');
            $table->double('boss_hp');
            $table->integer('boss_max_health');
            $table->boolean('active');

            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('party_id')->nullable()->after('openai_api_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quests');

        if (Schema::hasColumn('users', 'party_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('party_id');
            });
        }
    }
};
