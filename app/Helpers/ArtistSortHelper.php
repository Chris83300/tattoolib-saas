<?php

namespace App\Helpers;

class ArtistSortHelper
{
    /**
     * Calculer le rang de tri d'un artiste pour la marketplace.
     * Plus le rang est ÉLEVÉ, plus l'artiste apparaît en premier (tri DESC).
     *
     * 100 = PRO payant direct (pas de studio, abonnement PRO actif)
     *  90 = PRO via studio (artiste rattaché à un studio)
     *  50 = STARTER payant direct
     *  30 = Trial actif
     *   0 = Pas d'accès (ne devrait pas passer le scope marketplaceVisible)
     */
    public static function calculateRank($artisan): int
    {
        if ($artisan->is_blocked ?? false) return 0;

        $isSubscribed = (bool) ($artisan->is_subscribed ?? false);
        $studioId     = $artisan->studio_id ?? null;
        $plan         = $artisan->current_plan ?? 'starter';
        $trialEndsAt  = $artisan->trial_ends_at;

        // PRO direct (artiste indépendant, abonnement PRO actif)
        if ($isSubscribed && !$studioId && $plan === 'pro') return 100;

        // Artiste studio (studio rattaché)
        if ($studioId) return 90;

        // STARTER payant direct
        if ($isSubscribed && !$studioId) return 50;

        // Trial actif
        if ($trialEndsAt) {
            $endsAt = $trialEndsAt instanceof \Carbon\Carbon
                ? $trialEndsAt
                : \Carbon\Carbon::parse($trialEndsAt);
            if ($endsAt->isFuture()) return 30;
        }

        return 0;
    }

    /**
     * Trier une collection Eloquent d'artistes avec rang + rotation hebdomadaire.
     */
    public static function sortCollection($artists): \Illuminate\Support\Collection
    {
        $weeklySeed = (int) now()->startOfWeek()->timestamp;

        // Ajouter sort_rank à chaque artiste
        $withRank = $artists->map(function ($artist) {
            $artist->sort_rank = self::calculateRank($artist);
            return $artist;
        });

        // Grouper par rang, trier les groupes par rang décroissant
        $groups = $withRank->groupBy('sort_rank');
        $sortedKeys = $groups->keys()->sort()->reverse()->values();

        $result = collect();
        foreach ($sortedKeys as $rank) {
            $group = $groups->get($rank);
            // Rotation hebdomadaire au sein de chaque tier
            $items = $group->values()->all();
            mt_srand($weeklySeed + count($items));
            shuffle($items);
            foreach ($items as $item) {
                $result->push($item);
            }
        }

        return $result;
    }

    /**
     * Trier un tableau d'artistes (réponses API JSON).
     * Utilise crc32 pour la rotation hebdomadaire (déterministe par id+type).
     */
    public static function sortArray(array $artists): array
    {
        $weeklySeed = (int) now()->startOfWeek()->timestamp;

        foreach ($artists as &$artist) {
            $isBlocked   = $artist['is_blocked'] ?? false;
            $studioId    = $artist['studio_id'] ?? null;
            $isSubscribed = $artist['is_subscribed'] ?? false;
            $plan        = $artist['current_plan'] ?? 'starter';
            $trialEndsAt = $artist['trial_ends_at'] ?? null;

            if ($isBlocked) {
                $rank = 0;
            } elseif ($isSubscribed && !$studioId && $plan === 'pro') {
                $rank = 100;
            } elseif ($studioId) {
                $rank = 90;
            } elseif ($isSubscribed && !$studioId) {
                $rank = 50;
            } elseif ($trialEndsAt && strtotime($trialEndsAt) > time()) {
                $rank = 30;
            } else {
                $rank = 0;
            }

            $artist['sort_rank'] = $rank;
        }
        unset($artist);

        usort($artists, function ($a, $b) use ($weeklySeed) {
            if ($a['sort_rank'] !== $b['sort_rank']) {
                return $b['sort_rank'] - $a['sort_rank'];
            }
            // Même rang → rotation hebdomadaire déterministe
            $hashA = crc32($weeklySeed . ($a['id'] ?? 0) . ($a['artist_type'] ?? $a['type'] ?? ''));
            $hashB = crc32($weeklySeed . ($b['id'] ?? 0) . ($b['artist_type'] ?? $b['type'] ?? ''));
            return $hashA - $hashB;
        });

        return $artists;
    }

    /**
     * Générer l'expression SQL ORDER BY pour le tri PRO en premier.
     * Utilisable dans orderByRaw().
     */
    public static function sqlOrderByRank(string $table): string
    {
        return "CASE
            WHEN {$table}.is_subscribed = 1 AND {$table}.studio_id IS NULL AND {$table}.current_plan = 'pro' THEN 100
            WHEN {$table}.studio_id IS NOT NULL THEN 90
            WHEN {$table}.is_subscribed = 1 AND {$table}.studio_id IS NULL THEN 50
            WHEN {$table}.trial_ends_at > NOW() THEN 30
            ELSE 0
        END DESC";
    }
}
