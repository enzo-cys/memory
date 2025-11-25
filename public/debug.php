<?php
echo "<h1>Debug Info</h1>";
echo "<pre>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "dirname(SCRIPT_NAME): " . dirname($_SERVER['SCRIPT_NAME']) . "\n";
echo "\nPHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "</pre>";

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helpers.php';

echo "<h2>URL Tests</h2>";
echo "<pre>";
echo "Base URL: " . \Core\Config::getBaseUrl() . "\n";
echo "url('/'): " . url('/') . "\n";
echo "url('/game'): " . url('/game') . "\n";
echo "url('/game/play'): " . url('/game/play') . "\n";
echo "asset('css/global.css'): " . asset('css/global.css') . "\n";
echo "</pre>";

echo "<h2>Router Test</h2>";
echo "<pre>";
$testUri = "/memory/public/game";
$path = parse_url($testUri, PHP_URL_PATH) ?? '/';
echo "Test URI: $testUri\n";
echo "Parsed path: $path\n";

$baseUrl = \Core\Config::getBaseUrl();
echo "Base URL: $baseUrl\n";

if ($baseUrl && strpos($path, $baseUrl) === 0) {
    $cleanPath = substr($path, strlen($baseUrl)) ?: '/';
    echo "Clean path (après retrait base): $cleanPath\n";
} else {
    echo "Base URL non détecté dans le path\n";
}
echo "</pre>";

echo "<h2>Test Links</h2>";
echo '<a href="' . url('/game') . '">Aller au jeu</a><br>';
echo '<a href="' . url('/game/leaderboard') . '">Classement</a><br>';
