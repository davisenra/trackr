<?php

use App\Models\Package;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('package_events', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Package::class)->constrained();
            $table->string('status');
            $table->string('status_hash');
            $table->string('location')->nullable();
            $table->string('destination')->nullable();
            $table->datetime('evented_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_events');
    }
};
