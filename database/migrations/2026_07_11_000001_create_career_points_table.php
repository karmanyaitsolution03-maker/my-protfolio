<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_points', function (Blueprint $t) {
            $t->id();
            $t->text('text');                          // one highlight line (HTML allowed)
            $t->unsignedInteger('position')->default(0);
            $t->timestamps();
        });

        // Seed the current highlight points so the section keeps working.
        $now = now();
        DB::table('career_points')->insert([
            ['text' => 'I have a <b>2-month notice period</b> at my current company.',            'position' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['text' => 'Open to <b>relocation within Gujarat</b>.',                                'position' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['text' => 'Hands-on with both <b>web and mobile</b> application projects.',           'position' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['text' => 'Comfortable owning features <b>end-to-end</b> — from API design to production support.', 'position' => 4, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('career_points');
    }
};
