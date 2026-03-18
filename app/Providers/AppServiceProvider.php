<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\View;
use App\ViewComposers\UnreadMessagesComposer;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom Blade directives for policies
        Blade::if('canUpdateBooking', function ($booking) {
            return auth()->check() && auth()->user()->can('update', $booking);
        });

        Blade::if('canSendDesign', function ($booking) {
            return auth()->check() && auth()->user()->can('sendDesign', $booking);
        });

        Blade::if('canPayDeposit', function ($booking) {
            return auth()->check() && auth()->user()->can('payDeposit', $booking);
        });

        Blade::if('canConfirmAppointment', function ($booking) {
            return auth()->check() && auth()->user()->can('confirmAppointment', $booking);
        });

        Blade::if('canArchiveConversation', function ($conversation) {
            return auth()->check() && auth()->user()->can('archive', $conversation);
        });

        Blade::if('canManagePortfolio', function ($tattooer) {
            return auth()->check() && auth()->user()->can('managePortfolio', $tattooer);
        });

        Blade::if('canManageSchedule', function ($tattooer) {
            return auth()->check() && auth()->user()->can('manageWorkingHours', $tattooer);
        });

        // View Composer pour les messages non-lus
        view()->composer('layouts.tattooer', UnreadMessagesComposer::class);
        view()->composer('layouts.client', \App\View\Composers\ClientLayoutComposer::class);

        // Nonce CSP pour les assets Livewire injectés (production uniquement)
        if (method_exists(Livewire::class, 'scriptNonce')) {
            Livewire::scriptNonce(fn() => app('csp-nonce', ''));
        }
    }
}
