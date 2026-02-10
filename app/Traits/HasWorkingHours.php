<?php

namespace App\Traits;

use App\Models\WorkingHour;
use Carbon\Carbon;

trait HasWorkingHours
{
    /**
     * Relation morphMany working hours
     */
    public function workingHours()
    {
        return $this->morphMany(WorkingHour::class, 'owner');
    }
    
    /**
     * Obtenir horaires pour un jour spécifique
     */
    public function getWorkingHoursForDay(int $dayOfWeek): ?WorkingHour
    {
        return $this->workingHours()
            ->where('day_of_week', $dayOfWeek)
            ->first();
    }
    
    /**
     * Vérifier si ouvert un jour donné
     */
    public function isOpenOn(int $dayOfWeek): bool
    {
        $hours = $this->getWorkingHoursForDay($dayOfWeek);
        return $hours && !$hours->is_closed;
    }
    
    /**
     * Obtenir tous les horaires formatés
     */
    public function getFormattedWorkingHours(): array
    {
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $result = [];
        
        foreach (range(0, 6) as $dayIndex) {
            $hours = $this->getWorkingHoursForDay($dayIndex);
            
            $result[$days[$dayIndex]] = $hours && !$hours->is_closed
                ? sprintf('%s - %s', $hours->open_time, $hours->close_time)
                : 'Fermé';
        }
        
        return $result;
    }
    
    /**
     * Mettre à jour horaires
     */
    public function updateWorkingHours(array $hoursData): void
    {
        foreach ($hoursData as $data) {
            $this->workingHours()->updateOrCreate(
                ['day_of_week' => $data['day_of_week']],
                [
                    'open_time' => $data['open_time'] ?? null,
                    'close_time' => $data['close_time'] ?? null,
                    'is_closed' => $data['is_closed'] ?? false,
                ]
            );
        }
        
        // Invalider cache
        if (class_exists('\App\Services\CacheService')) {
            app(\App\Services\CacheService::class)->invalidateArtist($this);
        }
    }
    
    /**
     * Vérifier si disponible à une heure donnée
     */
    public function isAvailableAt(Carbon $dateTime): bool
    {
        $dayOfWeek = $dateTime->dayOfWeek;
        $hours = $this->getWorkingHoursForDay($dayOfWeek);
        
        if (!$hours || $hours->is_closed) {
            return false;
        }
        
        $time = $dateTime->format('H:i:s');
        return $time >= $hours->open_time && $time <= $hours->close_time;
    }
    
    /**
     * Obtenir les jours d'ouverture
     */
    public function getOpenDays(): array
    {
        return $this->workingHours()
            ->where('is_closed', false)
            ->pluck('day_of_week')
            ->toArray();
    }
    
    /**
     * Obtenir les jours de fermeture
     */
    public function getClosedDays(): array
    {
        return $this->workingHours()
            ->where('is_closed', true)
            ->pluck('day_of_week')
            ->toArray();
    }
}
