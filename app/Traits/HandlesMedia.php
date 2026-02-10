<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;

trait HandlesMedia
{
    /**
     * Upload avatar (unique)
     */
    public function uploadAvatar(UploadedFile $file): Media
    {
        // Supprimer ancien avatar
        $this->clearMediaCollection('avatar');

        return $this->addMedia($file)
            ->usingFileName($this->generateMediaFileName($file, 'avatar'))
            ->toMediaCollection('avatar');
    }

    /**
     * Upload banner (unique)
     */
    public function uploadBanner(UploadedFile $file): Media
    {
        $this->clearMediaCollection('banner');

        return $this->addMedia($file)
            ->usingFileName($this->generateMediaFileName($file, 'banner'))
            ->toMediaCollection('banner');
    }

    /**
     * Upload portfolio (multiple)
     */
    public function uploadPortfolioImage(UploadedFile $file): Media
    {
        return $this->addMedia($file)
            ->usingFileName($this->generateMediaFileName($file, 'portfolio'))
            ->toMediaCollection('portfolio');
    }

    /**
     * Upload before/after (multiple)
     */
    public function uploadBeforeAfter(UploadedFile $file, string $type): Media
    {
        return $this->addMedia($file)
            ->withCustomProperties(['type' => $type]) // 'before' ou 'after'
            ->usingFileName($this->generateMediaFileName($file, 'before_after'))
            ->toMediaCollection('before_after');
    }

    /**
     * Obtenir URL avatar
     */
    public function getAvatarUrl(): string
    {
        return $this->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png');
    }

    /**
     * Obtenir URL banner
     */
    public function getBannerUrl(): string
    {
        return $this->getFirstMediaUrl('banner') ?: asset('images/default-banner-placeholder.png');
    }

    /**
     * Obtenir toutes les images portfolio
     */
    public function getPortfolioImages(): array
    {
        return $this->getMedia('portfolio')->map(function(Media $media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
                'name' => $media->name,
                'size' => $media->size,
                'mime_type' => $media->mime_type,
                'created_at' => $media->created_at,
            ];
        })->toArray();
    }

    /**
     * Obtenir images before/after
     */
    public function getBeforeAfterImages(): array
    {
        return $this->getMedia('before_after')->groupBy('custom_properties.type')->toArray();
    }

    /**
     * Supprimer une image portfolio
     */
    public function deletePortfolioImage(int $mediaId): bool
    {
        $media = $this->getMedia('portfolio')->where('id', $mediaId)->first();

        if ($media) {
            $media->delete();

            // Invalider cache si disponible
            if (class_exists('\App\Services\CacheService')) {
                app(\App\Services\CacheService::class)->invalidateArtist($this);
            }

            return true;
        }

        return false;
    }

    /**
     * Obtenir taille totale portfolio (en bytes)
     */
    public function getPortfolioSize(): int
    {
        return $this->getMedia('portfolio')->sum('size');
    }

    /**
     * Compter images portfolio
     */
    public function getPortfolioCount(): int
    {
        return $this->getMedia('portfolio')->count();
    }

    /**
     * Vérifier si portfolio a des images
     */
    public function hasPortfolioImages(): bool
    {
        return $this->getPortfolioCount() > 0;
    }

    /**
     * Générer nom de fichier sécurisé
     */
    private function generateMediaFileName(UploadedFile $file, string $collection): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->timestamp;
        $random = substr(md5(uniqid()), 0, 8);
        $className = class_basename($this);

        return sprintf(
            '%s_%s_%s_%s_%s.%s',
            $className,
            $this->id,
            $collection,
            $timestamp . $random,
            $extension
        );
    }

    /**
     * Valider fichier image
     */
    public function validateImageFile(UploadedFile $file): array
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        $errors = [];

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            $errors[] = 'Le fichier doit être une image (JPEG, PNG, WebP, GIF).';
        }

        if ($file->getSize() > $maxSize) {
            $errors[] = 'La taille du fichier ne doit pas dépasser 5MB.';
        }

        return $errors;
    }
}
