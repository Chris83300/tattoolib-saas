<?php
if (!function_exists('csp_nonce')) {
    function csp_nonce(): string
    {
        try {
            return app()->make('csp-nonce');
        } catch (\Exception $e) {
            return '';
        }
    }
}
