<?php

namespace Tests\Browser\Data;

class ScreenshotRoutes
{
    public static function guest(): array
    {
        return [
            '/',
            '/marketplace',
            '/auth/login',
            '/register',
            '/register/tattooer',
            '/register/pierceur',
            '/register/studio',
            '/register/plan',
            '/auth/forgot-password',
            '/tarifs',
        ];
    }

    public static function tattooer(): array
    {
        return [
            '/tattooer/dashboard',
            '/tattooer/profil',
            '/tattooer/portfolio',
            '/tattooer/requests',
            '/tattooer/calendar',
            '/tattooer/clients',
            '/tattooer/messages',
            '/tattooer/settings',
            '/tattooer/payments',
            '/tattooer/compliance/documents',
            '/tattooer/subscription-plans',
            '/tattooer/pricing',
            '/tattooer/statistiques',
            '/tattooer/disponibilites',
        ];
    }

    public static function pierceur(): array
    {
        return [
            '/pierceur/dashboard',
            '/pierceur/profil',
            '/pierceur/portfolio',
            '/pierceur/requests',
            '/pierceur/calendar',
            '/pierceur/clients',
            '/pierceur/messages',
            '/pierceur/settings',
            '/pierceur/payments',
            '/pierceur/statistiques',
            '/pierceur/disponibilites',
        ];
    }

    public static function client(): array
    {
        return [
            '/client/dashboard',
            '/client/booking-requests',
            '/client/messages',
            '/client/profile',
            '/client/settings',
            '/client/bookings',
            '/client/reviews',
        ];
    }

    public static function studio(): array
    {
        return [
            '/studio/dashboard',
            '/studio/artists',
            '/studio/planning',
            '/studio/clients',
            '/studio/messages',
            '/studio/parametres',
            '/studio/billing',
            '/studio/demandes',
            '/studio/compliance',
        ];
    }

    public static function admin(): array
    {
        return [
            '/admin',
            '/admin/users',
            '/admin/tattooers',
            '/admin/pierceurs',
            '/admin/studios',
            '/admin/booking-requests',
            '/admin/appointments',
            '/admin/payments',
            '/admin/transactions',
            '/admin/subscriptions',
            '/admin/cancellations',
            '/admin/reviews',
            '/admin/complaints',
            '/admin/compliance-records',
            '/admin/conversations',
            '/admin/data-processing-records',
            '/admin/refunds-page',
            '/admin/support-chat',
        ];
    }
}
