<?php

namespace App\Policies;

use App\Models\AccountingTransaction;
use App\Models\User;

class AccountingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isTattooer();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AccountingTransaction $transaction): bool
    {
        return $user->isTattooer() && $transaction->tattooer_id === $user->tattooer->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isTattooer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AccountingTransaction $transaction): bool
    {
        return $user->isTattooer() && $transaction->tattooer_id === $user->tattooer->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AccountingTransaction $transaction): bool
    {
        return $user->isTattooer() && $transaction->tattooer_id === $user->tattooer->id;
    }

    /**
     * Determine whether the user can access dashboard.
     */
    public function accessDashboard(User $user): bool
    {
        return $user->isTattooer();
    }

    /**
     * Determine whether the user can generate reports.
     */
    public function generateReports(User $user): bool
    {
        return $user->isTattooer();
    }

    /**
     * Determine whether the user can export data.
     */
    public function exportData(User $user): bool
    {
        return $user->isTattooer();
    }
}
