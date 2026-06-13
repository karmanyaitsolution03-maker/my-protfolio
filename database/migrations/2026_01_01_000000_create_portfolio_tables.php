<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
        });

        Schema::create('skill_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('icon')->default('⚙️');
            $t->boolean('wide')->default(false);
            $t->unsignedInteger('position')->default(0);
            $t->timestamps();
        });

        Schema::create('skills', function (Blueprint $t) {
            $t->id();
            $t->foreignId('skill_category_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->unsignedTinyInteger('level')->default(80);
            $t->unsignedInteger('position')->default(0);
            $t->timestamps();
        });

        Schema::create('experiences', function (Blueprint $t) {
            $t->id();
            $t->string('company');
            $t->string('sub')->nullable();
            $t->string('role');
            $t->string('period');
            $t->boolean('live')->default(false);
            $t->json('responsibilities')->nullable();
            $t->unsignedInteger('position')->default(0);
            $t->timestamps();
        });

        Schema::create('projects', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('kicker')->nullable();
            $t->text('description')->nullable();
            $t->string('color')->default('#3DE8FF');
            $t->boolean('wide')->default(false);
            $t->json('arch')->nullable();      // [{label, sub, hot}]
            $t->json('metrics')->nullable();   // [{value, label}]
            $t->json('tags')->nullable();      // ["Laravel", ...]
            $t->unsignedInteger('position')->default(0);
            $t->timestamps();
        });

        Schema::create('achievements', function (Blueprint $t) {
            $t->id();
            $t->string('value');               // e.g. "API" (used when count is null)
            $t->unsignedInteger('count')->nullable(); // e.g. 2 -> animated "2+"
            $t->string('suffix')->default('+');
            $t->string('label');
            $t->unsignedInteger('position')->default(0);
            $t->timestamps();
        });

        Schema::create('messages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email');
            $t->text('message');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('skill_categories');
        Schema::dropIfExists('settings');
    }
};
