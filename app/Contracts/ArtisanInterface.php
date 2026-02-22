<?php

namespace App\Contracts;

/**
 * Interface commune à Tattooer et Piercer.
 * Permet au TattooerController et aux Services d'être polymorphiques
 * sans connaître le type concret de l'artisan.
 */
interface ArtisanInterface
{
    public function artisanType(): string;
    public function artisanLabel(): string;
    public function routePrefix(): string;
    public function isTattooer(): bool;
    public function isPiercer(): bool;
    public function isPro(): bool;
    public function isFree(): bool;
    public function bookingRequests();
}
