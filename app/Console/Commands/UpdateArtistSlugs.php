<?php

namespace App\Console\Commands;

use App\Models\Tattooer;
use App\Models\Pierceur;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UpdateArtistSlugs extends Command
{
    protected $signature = 'artists:update-slugs';
    protected $description = 'Met à jour les slugs pour tous les artistes (tattooers et pierceurs)';

    public function handle()
    {
        $this->info('Mise à jour des slugs pour les tattooers...');
        
        $tattooers = Tattooer::whereNull('slug')->orWhere('slug', '')->get();
        foreach ($tattooers as $tattooer) {
            $tattooer->slug = Str::slug($tattooer->user->name . '-' . $tattooer->id);
            $tattooer->save();
            $this->line("Tattooer: {$tattooer->user->name} -> slug: {$tattooer->slug}");
        }
        
        $this->info("Tattooers mis à jour: {$tattooers->count()}");
        
        $this->info('Mise à jour des slugs pour les pierceurs...');
        
        $pierceurs = Pierceur::whereNull('slug')->orWhere('slug', '')->get();
        foreach ($pierceurs as $pierceur) {
            $pierceur->slug = Str::slug($pierceur->user->name . '-' . $pierceur->id);
            $pierceur->save();
            $this->line("Pierceur: {$pierceur->user->name} -> slug: {$pierceur->slug}");
        }
        
        $this->info("Pierceurs mis à jour: {$pierceurs->count()}");
        
        $this->info('Mise à jour terminée !');
        
        return Command::SUCCESS;
    }
}
