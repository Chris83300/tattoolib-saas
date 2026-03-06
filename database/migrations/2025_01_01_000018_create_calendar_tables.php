<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_hours', function (Blueprint $table) {
            $table->id();
            // Polymorphique — owner_type/owner_id (tattooer, piercer, studio)
            $table->string('owner_type');
            $table->unsignedBigInteger('owner_id');
            $table->tinyInteger('day_of_week');
            $table->boolean('is_open')->default(true);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->integer('slot_duration_minutes')->default(60);
            $table->integer('buffer_time_minutes')->default(15);
            $table->timestamps();

            $table->index(['owner_type', 'owner_id']);
            $table->index(['owner_type', 'owner_id', 'is_open']);
            $table->index(['owner_type', 'owner_id', 'day_of_week']);
        });

        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            // Polymorphique — owner_type/owner_id
            $table->string('owner_type');
            $table->unsignedBigInteger('owner_id');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('type', ['available', 'busy', 'break', 'holiday', 'sick_leave', 'external_booking', 'blocked'])->default('available');
            $table->enum('source', ['manual', 'working_hours', 'booking', 'external'])->default('manual');
            $table->text('notes')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_pattern')->nullable();
            $table->date('recurring_end_date')->nullable();
            $table->foreignId('appointment_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->index(['owner_type', 'owner_id']);
            $table->index(['owner_type', 'owner_id', 'date']);
            $table->index(['owner_type', 'owner_id', 'date', 'type']);
            $table->index('appointment_id');
        });

        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            // Polymorphique — bookable_type/bookable_id
            $table->unsignedBigInteger('bookable_id');
            $table->string('bookable_type');
            $table->enum('type', ['appointment', 'break', 'vacation', 'closure']);
            $table->foreignId('appointment_id')->nullable()->constrained()->cascadeOnDelete();
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->boolean('is_recurring')->default(false);
            $table->json('recurrence_rule')->nullable();
            $table->text('notes')->nullable();
            $table->string('color')->default('#D4B59E');
            $table->timestamps();

            $table->index(['bookable_type', 'bookable_id', 'start_datetime', 'end_datetime'], 'calendar_events_datetime_index');
            $table->index(['type', 'start_datetime'], 'calendar_events_type_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
        Schema::dropIfExists('availabilities');
        Schema::dropIfExists('working_hours');
    }
};
