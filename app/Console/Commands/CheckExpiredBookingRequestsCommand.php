<?php

namespace App\Console\Commands;

use App\Jobs\CheckExpiredBookingRequests;
use Illuminate\Console\Command;

class CheckExpiredBookingRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking-requests:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie et marque les demandes de réservation expirées';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Vérification des demandes de réservation expirées...');

        CheckExpiredBookingRequests::dispatch();

        $this->info('Job de vérification dispatché avec succès.');

        return Command::SUCCESS;
    }
}
