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

    /* ── SUPPORT CHAT ─────────────────────────────── */
    /* Rows de conversation */
    .chat-row-active  { background-color: rgba(99,102,241,0.07); border-left: 4px solid #6366f1; }
    .chat-row-unread  { background-color: rgba(255,237,213,0.9); border-left: 4px solid #fb923c; }
    .chat-row-default { border-left: 4px solid transparent; }
    .chat-row-default:hover { background-color: rgba(249,250,251,1); }
    .dark .chat-row-active  { background-color: rgba(99,102,241,0.15); }
    .dark .chat-row-unread  { background-color: rgba(154,52,18,0.12); }
    .dark .chat-row-default:hover { background-color: rgba(31,41,55,0.6); }

    /* Avatars de conversation */
    .chat-avatar-unread   { background-color: #ef4444 !important; }
    .chat-avatar-awaiting { background-color: #eab308 !important; }
    .chat-avatar-default  { background-color: #9ca3af !important; }
    .dark .chat-avatar-default { background-color: #4b5563 !important; }

    /* Animation pulse custom (animate-pulse ne se compile pas dans Filament) */
    @keyframes inkpik-pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.4; }
    }
    .chat-pulse { animation: inkpik-pulse 2s cubic-bezier(0.4,0,0.6,1) infinite; }

    /* Badges état (non lu / en attente / répondu) */
    .chat-badge-unread {
        display: inline-block; padding: 2px 6px; border-radius: 9999px;
        background-color: #ef4444; color: #fff; font-size: 10px; font-weight: 700;
    }
    .chat-badge-awaiting {
        display: inline-block; padding: 2px 6px; border-radius: 9999px;
        background-color: rgba(234,179,8,0.2); color: #ca8a04; font-size: 10px; font-weight: 500;
    }
    .dark .chat-badge-awaiting { color: #facc15; }
    .chat-badge-replied {
        display: inline-block; padding: 2px 6px; border-radius: 9999px;
        background-color: rgba(34,197,94,0.2); color: #16a34a; font-size: 10px; font-weight: 500;
    }
    .dark .chat-badge-replied { color: #4ade80; }

    /* Bulles de messages */
    .chat-bubble-admin {
        background-color: #4f46e5; color: #fff;
        border-radius: 1rem 0.25rem 1rem 1rem;
    }
    .chat-bubble-user {
        background-color: #fff; color: #1f2937;
        border: 1px solid #e5e7eb; border-radius: 0.25rem 1rem 1rem 1rem;
    }
    .dark .chat-bubble-user { background-color: #374151; color: #f3f4f6; border-color: #4b5563; }

    /* Badges type conversation */
    .chat-type-support {
        display: inline-block; padding: 2px 6px; border-radius: 0.25rem; font-size: 0.75rem;
        background-color: rgba(168,85,247,0.1); color: #9333ea;
    }
    .dark .chat-type-support { background-color: rgba(168,85,247,0.3); color: #c084fc; }
    .chat-type-private {
        display: inline-block; padding: 2px 6px; border-radius: 0.25rem; font-size: 0.75rem;
        background-color: rgba(59,130,246,0.1); color: #2563eb;
    }
    .dark .chat-type-private { background-color: rgba(59,130,246,0.3); color: #60a5fa; }
</style>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/filament/hooks/styles-after.blade.php ENDPATH**/ ?>