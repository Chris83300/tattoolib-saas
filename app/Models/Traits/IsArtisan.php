<?php

namespace App\Models\Traits;

/**
 * Trait IsArtisan
 *
 * Fournit les helpers d'identification du type d'artisan (Tattooer ou Piercer).
 * Utilisé dans les deux models pour permettre au TattooerController d'être
 * polymorphique sans connaître le type concret.
 */
trait IsArtisan
{
    /**
     * Retourne le type d'artisan : 'tattooer' ou 'piercer'
     */
    public function artisanType(): string
    {
        return $this instanceof \App\Models\Tattooer ? 'tattooer' : 'piercer';
    }

    /**
     * Retourne le label lisible du type d'artisan
     */
    public function artisanLabel(): string
    {
        return $this instanceof \App\Models\Tattooer ? 'Tatoueur' : 'Pierceur';
    }

    /**
     * Retourne le préfixe de route (ex: 'tattooer.dashboard', 'pierceur.dashboard')
     */
    public function routePrefix(): string
    {
        return $this instanceof \App\Models\Tattooer ? 'tattooer' : 'pierceur';
    }

    /**
     * Vrai si c'est un tatoueur
     */
    public function isTattooer(): bool
    {
        return $this instanceof \App\Models\Tattooer;
    }

    /**
     * Vrai si c'est un pierceur
     */
    public function isPiercer(): bool
    {
        return $this instanceof \App\Models\Piercer;
    }

    /**
     * Retourne l'URL du profil public de l'artisan
     */
    public function getProfileUrl(): string
    {
        $routeName = $this->isTattooer() ? 'marketplace.tattooer.show' : 'marketplace.piercer.show';
        return route($routeName, $this->slug);
    }
}
