<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helpers.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

use Core\Router;
use Core\Config;

// Initialisation du routeur
$router = new Router();

// ========================================
// ROUTES DU JEU MEMORY
// ========================================

// Page d'accueil du jeu (menu principal)
$router->get('/', 'App\\Controllers\\GameController@index');
$router->get('/index.php', 'App\\Controllers\\GameController@index'); // Pour accès direct
$router->get('/game', 'App\\Controllers\\GameController@index');

// Démarrer une nouvelle partie
$router->post('/game/start', 'App\\Controllers\\GameController@start');

// Jouer (afficher la partie en cours)
$router->get('/game/play', 'App\\Controllers\\GameController@play');

// Retourner une carte
$router->post('/game/flip', 'App\\Controllers\\GameController@flip');

// Retourner automatiquement les cartes incorrectes
$router->get('/game/flipback', 'App\\Controllers\\GameController@flipback');

// Afficher le formulaire de fin de partie
$router->get('/game/finish', 'App\\Controllers\\GameController@finish');

// Sauvegarder le score
$router->post('/game/save-score', 'App\\Controllers\\GameController@saveScore');

// Quitter la partie
$router->get('/game/quit', 'App\\Controllers\\GameController@quit');

// Afficher le classement
$router->get('/game/leaderboard', 'App\\Controllers\\GameController@leaderboard');
// Exécution du routeur
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
