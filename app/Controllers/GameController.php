<?php

namespace App\Controllers;

use Core\BaseController;
use App\Models\GameModel;
use App\Models\Card;

/**
 * Classe GameController
 * ---------------------
 * Contrôleur gérant toutes les actions du jeu Memory
 */
class GameController extends BaseController
{
    private GameModel $gameModel;

    public function __construct()
    {
        // Démarrer la session si pas déjà fait
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->gameModel = new GameModel();
    }

    /**
     * Page d'accueil du jeu - Menu principal
     */
    public function index(): void
    {
        // Récupérer le paramètre pairs si fourni (depuis le classement)
        $defaultPairs = isset($_GET['pairs']) ? (int)$_GET['pairs'] : 6;
        $defaultPairs = max(3, min(12, $defaultPairs));

        $this->render('game/index', [
            'title' => 'Memory Game - ذاكرة',
            'default_pairs' => $defaultPairs
        ]);
    }

    /**
     * Démarre une nouvelle partie
     */
    public function start(): void
    {
        // Récupérer le nombre de paires depuis le formulaire
        $pairs = isset($_POST['pairs']) ? (int)$_POST['pairs'] : 6;
        $pairs = max(3, min(12, $pairs));

        // Générer les cartes
        $cards = $this->gameModel->generateCards($pairs);

        // Initialiser la session de jeu
        $_SESSION['game'] = [
            'cards' => array_map(fn($card) => $card->toArray(), $cards),
            'pairs' => $pairs,
            'moves' => 0,
            'matches' => 0,
            'flipped' => [],
            'start_time' => time()
        ];

        // Rediriger vers la partie
        header('Location: /game/play');
        exit;
    }

    /**
     * Affiche la partie en cours
     */
    public function play(): void
    {
        // Vérifier qu'une partie est en cours
        if (!isset($_SESSION['game'])) {
            header('Location: /game');
            exit;
        }

        $game = $_SESSION['game'];

        $this->render('game/play', [
            'title' => 'Partie en cours - Memory',
            'cards' => $game['cards'],
            'moves' => $game['moves'],
            'pairs' => $game['pairs'],
            'matches' => $game['matches'],
            'start_time' => $game['start_time'],
            'show_game_header' => true
        ]);
    }

    /**
     * Gère le retournement d'une carte (appelé via formulaire)
     */
    public function flip(): void
    {
        if (!isset($_SESSION['game']) || !isset($_POST['card_id'])) {
            header('Location: /game');
            exit;
        }

        $cardId = (int)$_POST['card_id'];
        $game = &$_SESSION['game'];

        // Si deux cartes sont déjà retournées (en attente de retournement), les retourner d'abord
        if (count($game['flipped']) === 2) {
            foreach ($game['cards'] as &$c) {
                if (!$c['isMatched']) {
                    $c['isFlipped'] = false;
                }
            }
            $game['flipped'] = [];
            unset($_SESSION['needs_flip_back']);
        }

        // Vérifier que la carte existe et n'est pas déjà appariée ou déjà retournée
        $card = null;
        foreach ($game['cards'] as &$c) {
            if ($c['id'] === $cardId && !$c['isMatched'] && !$c['isFlipped']) {
                $card = &$c;
                break;
            }
        }

        if (!$card) {
            header('Location: /game/play');
            exit;
        }

        // Retourner la carte
        $card['isFlipped'] = true;
        $game['flipped'][] = $cardId;

        // Si deux cartes sont retournées, vérifier la paire
        if (count($game['flipped']) === 2) {
            $game['moves']++;

            $card1 = null;
            $card2 = null;

            foreach ($game['cards'] as &$c) {
                if ($c['id'] === $game['flipped'][0]) {
                    $card1 = &$c;
                }
                if ($c['id'] === $game['flipped'][1]) {
                    $card2 = &$c;
                }
            }

            // Vérifier si c'est une paire
            if ($card1['symbol'] === $card2['symbol']) {
                $card1['isMatched'] = true;
                $card2['isMatched'] = true;
                $game['matches']++;
                $game['flipped'] = [];

                // Si toutes les paires sont trouvées, enregistrer le temps final
                if ($game['matches'] >= $game['pairs']) {
                    $game['end_time'] = time();
                }
            } else {
                // Marquer qu'il faut retourner les cartes
                $_SESSION['needs_flip_back'] = true;
            }
        }

        header('Location: /game/play');
        exit;
    }

    /**
     * Retourne les cartes non appariées (appelé automatiquement après délai)
     */
    public function flipback(): void
    {
        if (!isset($_SESSION['game'])) {
            header('Location: /game');
            exit;
        }

        $game = &$_SESSION['game'];

        // Retourner toutes les cartes non appariées
        foreach ($game['cards'] as &$card) {
            if (!$card['isMatched']) {
                $card['isFlipped'] = false;
            }
        }

        $game['flipped'] = [];

        header('Location: /game/play');
        exit;
    }

    /**
     * Affiche le formulaire de fin de partie
     */
    public function finish(): void
    {
        if (!isset($_SESSION['game'])) {
            header('Location: /game');
            exit;
        }

        $game = $_SESSION['game'];
        // Utiliser le temps enregistré quand la dernière paire a été trouvée
        $time = isset($game['end_time']) ? ($game['end_time'] - $game['start_time']) : (time() - $game['start_time']);

        $this->render('game/finish', [
            'title' => 'Partie terminée !',
            'moves' => $game['moves'],
            'time' => $time,
            'pairs' => $game['pairs'],
            'formatted_time' => $this->gameModel->formatTime($time),
            'score' => $this->gameModel->calculateScore($game['moves'], $time)
        ]);
    }

    /**
     * Sauvegarde le score et redirige vers le menu
     */
    public function saveScore(): void
    {
        if (!isset($_SESSION['game']) || !isset($_POST['pseudo'])) {
            header('Location: /game');
            exit;
        }

        $game = $_SESSION['game'];
        $pseudo = trim($_POST['pseudo']);
        $time = time() - $game['start_time'];

        if (!empty($pseudo)) {
            $this->gameModel->saveScore(
                $pseudo,
                $game['moves'],
                $time,
                $game['pairs']
            );
        }

        // Nettoyer la session
        unset($_SESSION['game']);

        header('Location: /game');
        exit;
    }

    /**
     * Abandonne la partie en cours
     */
    public function quit(): void
    {
        unset($_SESSION['game']);
        header('Location: /game');
        exit;
    }

    /**
     * Affiche le classement des 10 meilleurs joueurs
     */
    public function leaderboard(): void
    {
        // Récupérer le filtre par nombre de paires (paramètre GET)
        $filterPairs = isset($_GET['pairs']) ? (int)$_GET['pairs'] : null;

        // Valider le filtre
        if ($filterPairs !== null) {
            $filterPairs = max(3, min(12, $filterPairs));
        }

        $leaderboard = $this->gameModel->getLeaderboard($filterPairs);

        $this->render('game/leaderboard', [
            'title' => 'Classement des Meilleurs Joueurs',
            'leaderboard' => $leaderboard,
            'filter_pairs' => $filterPairs
        ]);
    }
}
