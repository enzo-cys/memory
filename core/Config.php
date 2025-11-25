<?php

namespace Core;

/**
 * Classe Config
 * -------------
 * Gère la configuration globale de l'application
 */
class Config
{
    private static ?string $baseUrl = null;

    /**
     * Détecte et retourne l'URL de base de l'application
     * Ex: /memory/public ou / si à la racine
     */
    public static function getBaseUrl(): string
    {
        if (self::$baseUrl === null) {
            $scriptName = dirname($_SERVER['SCRIPT_NAME']);
            self::$baseUrl = $scriptName !== '/' ? rtrim($scriptName, '/') : '';
        }
        return self::$baseUrl;
    }

    /**
     * Génère une URL complète depuis un chemin relatif
     * Ex: url('/game') -> /memory/public/game
     */
    public static function url(string $path = ''): string
    {
        $path = ltrim($path, '/');
        return self::getBaseUrl() . ($path ? '/' . $path : '');
    }
}
