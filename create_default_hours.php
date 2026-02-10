<?php

$tattooer = App\Models\Tattooer::first();
if ($tattooer) {
    // Créer horaires par défaut (lun-vendredi, 9h-18h)
    $defaultHours = [
        ['day_of_week' => 1, 'is_open' => true, 'opening_time' => '09:00', 'closing_time' => '18:00'],
        ['day_of_week' => 2, 'is_open' => true, 'opening_time' => '09:00', 'closing_time' => '18:00'],
        ['day_of_week' => 3, 'is_open' => true, 'opening_time' => '09:00', 'closing_time' => '18:00'],
        ['day_of_week' => 4, 'is_open' => true, 'opening_time' => '09:00', 'closing_time' => '18:00'],
        ['day_of_week' => 5, 'is_open' => true, 'opening_time' => '09:00', 'closing_time' => '18:00'],
        // Samedi fermé
        ['day_of_week' => 6, 'is_open' => false, 'opening_time' => '09:00', 'closing_time' => '18:00'],
        // Dimanche fermé
        ['day_of_week' => 0, 'is_open' => false, 'opening_time' => '09:00', 'closing_time' => '18:00'],
    ];
    
    foreach ($defaultHours as $hour) {
        App\Models\WorkingHour::updateOrCreate(
            ['tattooer_id' => $tattooer->id, 'day_of_week' => $hour['day_of_week']],
            $hour
        );
    }
    
    echo 'Created ' . count($defaultHours) . ' working hours for tattooer ID: ' . $tattooer->id;
} else {
    echo 'No tattooer found';
}
