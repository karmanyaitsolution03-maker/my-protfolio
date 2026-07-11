<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availabilities', function (Blueprint $t) {
            $t->id();
            $t->string('label');                       // e.g. "CURRENT CTC"
            $t->string('value');                       // e.g. "₹3,00,000"
            $t->string('accent')->nullable();          // '', 'cyan' or 'green' — row color
            $t->unsignedInteger('position')->default(0);
            $t->timestamps();
        });

        // Seed the current Career & Availability rows so the section keeps working.
        // (Total experience is intentionally omitted here — it already shows in the
        //  Profile section, driven by the decimal "years" setting.)
        $now = now();
        DB::table('availabilities')->insert([
            ['label' => 'CURRENT COMPANY',    'value' => 'Digilance Infotech, Surat', 'accent' => null,    'position' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['label' => 'CURRENT CTC',        'value' => '₹3,00,000',                 'accent' => null,    'position' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['label' => 'EXPECTED CTC',       'value' => '₹3,84,000',                 'accent' => 'green', 'position' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['label' => 'PREFERRED LOCATION', 'value' => 'Surat, Gujarat',            'accent' => null,    'position' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['label' => 'NOTICE PERIOD',      'value' => '2 Months',                  'accent' => null,    'position' => 5, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
