<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tattooer;
use App\Services\CacheService;

class WarmupCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warmup {--artist= : Specific artist ID to warmup} {--marketplace : Warmup marketplace only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-warm cache for marketplace and artist profiles';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cacheService = app(CacheService::class);
        
        $this->info('Starting cache warmup...');
        
        if ($this->option('marketplace')) {
            $this->warmupMarketplace($cacheService);
        } elseif ($artistId = $this->option('artist')) {
            $this->warmupArtist($cacheService, $artistId);
        } else {
            $this->warmupAll($cacheService);
        }
        
        $this->info('Cache warmup completed successfully!');
        
        return Command::SUCCESS;
    }
    
    /**
     * Warmup marketplace cache
     */
    private function warmupMarketplace(CacheService $cacheService): void
    {
        $this->info('Warming up marketplace cache...');
        
        $bar = $this->output->createProgressBar(5);
        
        // Warmup marketplace principale
        $cacheService->getMarketplaceListings();
        $bar->advance();
        
        // Warmup avec filtres communs
        $commonFilters = [
            ['city' => 'Paris'],
            ['styles' => 'realism'],
            ['rating' => 4],
            ['city' => 'Lyon', 'styles' => 'traditional'],
        ];
        
        foreach ($commonFilters as $filters) {
            $cacheService->getMarketplaceListings($filters);
            $bar->advance();
        }
        
        // Warmup données de filtrage
        $cacheService->getAvailableStyles();
        $bar->advance();
        
        $cacheService->getAvailableCities();
        $bar->advance();
        
        $bar->finish();
        $this->newLine();
    }
    
    /**
     * Warmup specific artist cache
     */
    private function warmupArtist(CacheService $cacheService, int $artistId): void
    {
        $this->info("Warming up cache for artist {$artistId}...");
        
        $artist = Tattooer::find($artistId);
        
        if (!$artist) {
            $this->error("Artist {$artistId} not found!");
            return;
        }
        
        $bar = $this->output->createProgressBar(4);
        
        $cacheService->getArtistProfile($artist);
        $bar->advance();
        
        $cacheService->getPortfolio($artist);
        $bar->advance();
        
        $cacheService->getWorkingHours($artist);
        $bar->advance();
        
        $cacheService->getDashboardStats($artist);
        $bar->advance();
        
        $bar->finish();
        $this->newLine();
    }
    
    /**
     * Warmup all caches
     */
    private function warmupAll(CacheService $cacheService): void
    {
        // Warmup marketplace
        $this->warmupMarketplace($cacheService);
        
        // Warmup artist profiles
        $this->info('Warming up artist profiles...');
        
        $activeArtists = Tattooer::where('status', 'active')->count();
        $bar = $this->output->createProgressBar($activeArtists);
        
        Tattooer::where('status', 'active')
            ->chunk(50, function($tattooers) use ($cacheService, $bar) {
                foreach ($tattooers as $tattooer) {
                    $cacheService->getArtistProfile($tattooer);
                    $cacheService->getPortfolio($tattooer);
                    $cacheService->getWorkingHours($tattooer);
                    $cacheService->getDashboardStats($tattooer);
                    $bar->advance();
                }
            });
        
        $bar->finish();
        $this->newLine();
        
        // Afficher statistiques du cache
        $this->displayCacheStats($cacheService);
    }
    
    /**
     * Display cache statistics
     */
    private function displayCacheStats(CacheService $cacheService): void
    {
        $this->newLine();
        $this->info('Cache Statistics:');
        
        $stats = $cacheService->getCacheStats();
        
        $this->line("Total Keys: {$stats['total_keys']}");
        $this->line("Memory Usage: {$stats['memory_usage']}");
        $this->line("Hit Rate: " . round($stats['hit_rate'], 2) . "%");
    }
}
