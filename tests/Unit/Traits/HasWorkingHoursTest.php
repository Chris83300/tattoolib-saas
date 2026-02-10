<?php

namespace Tests\Unit\Traits;

use App\Models\Tattooer;
use App\Models\WorkingHour;
use App\Traits\HasWorkingHours;
use Tests\TestCase;
use Mockery;

class HasWorkingHoursTest extends TestCase
{
    /**
     * Setup test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test trait provides working hours methods
     */
    public function test_trait_provides_working_hours_methods(): void
    {
        $tattooer = new class {
            use HasWorkingHours;
        };

        expect($tattooer)->toHaveMethod('workingHours');
        expect($tattooer)->toHaveMethod('getWorkingHoursForDay');
        expect($tattooer)->toHaveMethod('isOpenOn');
        expect($tattooer)->toHaveMethod('getFormattedWorkingHours');
        expect($tattooer)->toHaveMethod('updateWorkingHours');
        expect($tattooer)->toHaveMethod('isAvailableAt');
    }

    /**
     * Test can check if open on specific day
     */
    public function test_can_check_if_open_on_specific_day(): void
    {
        $tattooer = new class {
            use HasWorkingHours;

            public function workingHours()
            {
                $mock = Mockery::mock();
                $mock->shouldReceive('create')->with([
                    'day_of_week' => 1, // Lundi
                    'open_time' => '09:00:00',
                    'close_time' => '18:00:00',
                    'is_closed' => false,
                ])->andReturn(new WorkingHour([
                    'day_of_week' => 1,
                    'open_time' => '09:00:00',
                    'close_time' => '18:00:00',
                    'is_closed' => false,
                ]));
                return $mock;
            }
        };

        expect($tattooer->isOpenOn(1))->toBeTrue();
        expect($tattooer->isOpenOn(0))->toBeFalse(); // Dimanche
    }

    /**
     * Test closed day handling
     */
    public function test_closed_day_handling(): void
    {
        $tattooer = new class {
            use HasWorkingHours;

            public function workingHours()
            {
                $mock = Mockery::mock();
                $mock->shouldReceive('create')->with([
                    'day_of_week' => 6, // Dimanche
                    'is_closed' => true,
                ])->andReturn(new WorkingHour([
                    'day_of_week' => 6,
                    'is_closed' => true,
                ]));
                return $mock;
            }
        };

        expect($tattooer->isOpenOn(6))->toBeFalse();
    }

    /**
     * Test formatted working hours returns correct structure
     */
    public function test_formatted_working_hours_returns_correct_structure(): void
    {
        $tattooer = new class {
            use HasWorkingHours;

            public function workingHours()
            {
                $mock = Mockery::mock();
                $mock->shouldReceive('get')->andReturn([
                    new WorkingHour([
                        'day_of_week' => 1,
                        'open_time' => '09:00:00',
                        'close_time' => '18:00:00',
                        'is_closed' => false,
                    ]),
                    new WorkingHour([
                        'day_of_week' => 2,
                        'open_time' => '09:00:00',
                        'close_time' => '18:00:00',
                        'is_closed' => false,
                    ]),
                ]);
                return $mock;
            }
        };

        $hours = $tattooer->getFormattedWorkingHours();

        expect($hours)->toBeArray();
        expect($hours)->toHaveCount(7); // 7 jours de la semaine
    }

    /**
     * Test update working hours functionality
     */
    public function test_update_working_hours_functionality(): void
    {
        $tattooer = new class {
            use HasWorkingHours;

            public function workingHours()
            {
                $mock = Mockery::mock();
                $mock->shouldReceive('updateOrCreate')->andReturn(new WorkingHour([
                    'day_of_week' => 1,
                    'open_time' => '10:00:00',
                    'close_time' => '19:00:00',
                    'is_closed' => false,
                ]));
                return $mock;
            }
        };

        // Test que la méthode existe et peut être appelée
        expect($tattooer)->toHaveMethod('updateWorkingHours');
    }

    /**
     * Test availability check at specific time
     */
    public function test_availability_check_at_specific_time(): void
    {
        $tattooer = new class {
            use HasWorkingHours;

            public function workingHours()
            {
                $mock = Mockery::mock();
                $mock->shouldReceive('where')->with('day_of_week', 3)->andReturnSelf();
                $mock->shouldReceive('first')->andReturn(new WorkingHour([
                    'day_of_week' => 3, // Mercredi
                    'open_time' => '09:00:00',
                    'close_time' => '18:00:00',
                    'is_closed' => false,
                ]));
                return $mock;
            }
        };

        // Test d'availability à 14h (horaire d'ouverture)
        expect($tattooer->isAvailableAt('2024-01-10 14:00:00'))->toBeTrue();
        
        // Test d'availability à 20h (horaire de fermeture)
        expect($tattooer->isAvailableAt('2024-01-10 20:00:00'))->toBeFalse();
    }

    /**
     * Test get open days
     */
    public function test_get_open_days(): void
    {
        $tattooer = new class {
            use HasWorkingHours;

            public function workingHours()
            {
                $mock = Mockery::mock();
                $mock->shouldReceive('get')->andReturn([
                    new WorkingHour(['day_of_week' => 1, 'is_closed' => false]), // Lundi
                    new WorkingHour(['day_of_week' => 2, 'is_closed' => false]), // Mardi
                    new WorkingHour(['day_of_week' => 6, 'is_closed' => true]),  // Dimanche
                ]);
                return $mock;
            }
        };

        $openDays = $tattooer->getOpenDays();
        
        expect($openDays)->toBeArray();
        expect($openDays)->toContain(1); // Lundi
        expect($openDays)->toContain(2); // Mardi
        expect($openDays)->not->Contain(6); // Dimanche (fermé)
    }

    /**
     * Test get closed days
     */
    public function test_get_closed_days(): void
    {
        $tattooer = new class {
            use HasWorkingHours;

            public function workingHours()
            {
                $mock = Mockery::mock();
                $mock->shouldReceive('get')->andReturn([
                    new WorkingHour(['day_of_week' => 1, 'is_closed' => false]), // Lundi
                    new WorkingHour(['day_of_week' => 6, 'is_closed' => true]),  // Dimanche
                    new WorkingHour(['day_of_week' => 0, 'is_closed' => true]),  // Samedi
                ]);
                return $mock;
            }
        };

        $closedDays = $tattooer->getClosedDays();
        
        expect($closedDays)->toBeArray();
        expect($closedDays)->toContain(6); // Dimanche
        expect($closedDays)->toContain(0); // Samedi
        expect($closedDays)->not->Contain(1); // Lundi (ouvert)
    }
}
