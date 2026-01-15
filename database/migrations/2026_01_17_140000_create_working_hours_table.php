
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('working_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tattooer_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('day_of_week'); // 0 (dimanche) à 6 (samedi)
            $table->boolean('is_open')->default(true);
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->boolean('is_break')->default(false);
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->timestamps();

            // Un tatoueur ne peut avoir qu'une seule entrée par jour
            $table->unique(['tattooer_id', 'day_of_week']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('working_hours');
    }
};
