<?php

/**
 * Fonction helper pour générer des URLs
 */

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        return \Core\Config::url($path);
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return \Core\Config::url('assets/' . ltrim($path, '/'));
    }
}
