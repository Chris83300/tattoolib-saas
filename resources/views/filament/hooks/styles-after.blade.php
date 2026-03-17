<style>
    /*
     * CSS custom Ink&Pik — chargé via PanelsRenderHook::STYLES_AFTER
     * ─────────────────────────────────────────────────────────────────
     * Ce hook est injecté APRÈS les feuilles de style Filament.
     * Les règles s'appliquent correctement sans être écrasées.
     */

    /* ── TYPOGRAPHIE ─────────────────────────────────── */
    .fi-body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
    }

    /* ── SIDEBAR ─────────────────────────────────────── */
    .fi-sidebar {
        width: 260px !important;
    }

    .fi-sidebar-group-btn {
        border: 1px solid rgba(233, 198, 160, 0.25) !important;
        border-radius: 0.625rem !important;
        margin-bottom: 0.125rem !important;
    }

    .fi-sidebar-item-button {
        border-radius: 0.625rem !important;
        transition: all 0.15s ease !important;
    }

    /* ── WIDGETS STATS ───────────────────────────────── */
    .fi-wi-stats-overview-stat {
        border-radius: 1rem !important;
        border: 1px solid rgba(233, 198, 160, 0.25) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
    }

    .fi-wi-stats-overview-stat:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    /* ── TABLES ──────────────────────────────────────── */
    .fi-ta-table {
        border-radius: 0.75rem !important;
        overflow: hidden !important;
    }

    .fi-ta-header-cell {
        font-size: 0.75rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
    }

    /* ── CARDS / SECTIONS ────────────────────────────── */
    .fi-section {
        border-radius: 1rem !important;
    }

    /* ── TOPBAR ──────────────────────────────────────── */
    .fi-topbar {
        border-bottom: 1px solid rgba(233, 198, 160, 0.15) !important;
        backdrop-filter: blur(8px) !important;
    }

    /* ── BADGES ──────────────────────────────────────── */
    .fi-badge {
        border-radius: 0.375rem !important;
    }

    /* ── CLASSES CUSTOM INK&PIK ──────────────────────── */
    .inkpik-gold { color: #e9c6a0; }
    .inkpik-gold-bg { background-color: rgba(233, 198, 160, 0.1); }
    .inkpik-border { border: 1px solid rgba(233, 198, 160, 0.25); }

    .inkpik-card {
        border-radius: 1rem;
        border: 1px solid rgba(233, 198, 160, 0.2);
        background-color: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 0.5rem 0.5rem !important;
        margin-top: 1rem;
    }
    .dark .inkpik-card {
        background-color: rgb(17 24 39);
        border-color: rgba(233, 198, 160, 0.15);
    }

    .inkpik-textarea{
        border-radius: 1rem;
        padding: 1rem;
        border: 2px solid #e9c6a0;
        width: 100%;
    }

    .inkpik-name{
        color: #e9c6a0;
        font-size: 1.2rem;
        margin-bottom: 1rem;
        margin-top: 6px;
        text-decoration: underline;
        text-underline-offset: 8px;
    }
    .inkpik-name-admin{
        color: #b87333;
        font-size: 1.2rem;
        margin-bottom: 1rem;
        margin-top: 6px;
        text-decoration: underline;
        text-underline-offset: 8px;
    }
    .inkpik-avatar{
        font-size: 1.5rem;
    }

    .inkpik-btn {
        padding: 0.5rem 1rem;
        border-radius: 50rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: background-color 0.15s ease;
        background-color: rgba(233, 198, 160, 0.15);
        color: #e9c6a0;
        border: 1px solid rgba(233, 198, 160, 0.3);
    }
    .inkpik-btn:hover { background-color: rgba(233, 198, 160, 0.25); }

    .inkpik-badge-pending {
        display: inline-flex; align-items: center;
        padding: 0.125rem 0.5rem; border-radius: 0.375rem;
        font-size: 0.75rem; font-weight: 500;
        background-color: rgb(254 243 199); color: rgb(92 45 0);
    }
    .dark .inkpik-badge-pending {
        background-color: rgba(120, 53, 15, 0.3); color: rgb(251 191 36);
    }

    .inkpik-badge-success {
        display: inline-flex; align-items: center;
        padding: 0.125rem 0.5rem; border-radius: 0.375rem;
        font-size: 0.75rem; font-weight: 500;
        background-color: rgb(209 250 229); color: rgb(6 78 59);
    }
    .dark .inkpik-badge-success {
        background-color: rgba(6, 78, 59, 0.3); color: rgb(52 211 153);
    }

    .inkpik-badge-danger {
        display: inline-flex; align-items: center;
        padding: 0.125rem 0.5rem; border-radius: 0.375rem;
        font-size: 0.75rem; font-weight: 500;
        background-color: rgb(254 226 226); color: rgb(127 29 29);
    }
    .dark .inkpik-badge-danger {
        background-color: rgba(127, 29, 29, 0.3); color: rgb(248 113 113);
    }
</style>
