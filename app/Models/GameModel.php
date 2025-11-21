<?php
namespace App\Models;

use Core\Database;
use PDO;

/**
 * Classe GameModel
 * ----------------
 * Gère la logique métier du jeu Memory
 * Interaction avec la base de données pour les scores
 */
class GameModel
{
    /**
     * Images des personnages d'Overlord disponibles pour les cartes
     * Format: ['image' => 'nom_fichier.png']
     */
    private const SHAPES = [
        ['image' => 'archangel.png'],
        ['image' => 'aura.png'],
        ['image' => 'clementine.png'],
        ['image' => 'demiurge.png'],
        ['image' => 'eclair.png'],
        ['image' => 'evil.png'],
        ['image' => 'jircniv.png'],
        ['image' => 'mare.png'],
        ['image' => 'momon.png'],
        ['image' => 'pandora.png'],
        ['image' => 'sebas.png'],
        ['image' => 'supreme.png']
    ];

    /**
     * @var PDO Instance PDO pour la base de données
     */
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    /**
     * Génère un jeu de cartes aléatoire
     *
     * @param int $pairs Nombre de paires (entre 3 et 12)
     * @return array Tableau de cartes mélangées
     */
    public function generateCards(int $pairs): array
    {
        // Valider le nombre de paires
        $pairs = max(3, min(12, $pairs));

        // Sélectionner aléatoirement des formes
        $selectedShapes = [];
        $availableShapes = self::SHAPES;
        shuffle($availableShapes);

        for ($i = 0; $i < $pairs; $i++) {
            $selectedShapes[] = $availableShapes[$i];
        }

        // Créer les paires de cartes
        $cards = [];
        $cardId = 0;

        foreach ($selectedShapes as $shapeData) {
            // Convertir en JSON pour stocker l'image
            $symbol = json_encode($shapeData);
            // Créer deux cartes identiques (une paire)
            $cards[] = new Card($cardId++, $symbol);
            $cards[] = new Card($cardId++, $symbol);
        }

        // Mélanger les cartes
        shuffle($cards);

        return $cards;
    }

    /**
     * Sauvegarde un score dans la base de données
     *
     * @param string $pseudo Pseudo du joueur
     * @param int $moves Nombre de coups
     * @param int $time Temps en secondes
     * @param int $pairs Nombre de paires
     * @return bool
     */
    public function saveScore(string $pseudo, int $moves, int $time, int $pairs): bool
    {
        try {
            $query = "INSERT INTO scores (pseudo, moves, time, pairs, played_at) 
                      VALUES (:pseudo, :moves, :time, :pairs, NOW())";
            
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([
                ':pseudo' => htmlspecialchars($pseudo, ENT_QUOTES, 'UTF-8'),
                ':moves' => $moves,
                ':time' => $time,
                ':pairs' => $pairs
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Calcule le score (plus c'est bas, mieux c'est)
     *
     * @param int $moves Nombre de coups
     * @param int $time Temps en secondes
     * @return int
     */
    public function calculateScore(int $moves, int $time): int
    {
        return $moves + $time;
    }

    /**
     * Formate le temps en format mm:ss
     *
     * @param int $seconds Temps en secondes
     * @return string
     */
    public function formatTime(int $seconds): string
    {
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Récupère le classement des 10 meilleurs scores
     *
     * @param int|null $pairs Filtre optionnel par nombre de paires
     * @return array
     */
    public function getLeaderboard(?int $pairs = null): array
    {
        try {
            if ($pairs !== null) {
                $query = "SELECT * FROM leaderboard WHERE pairs = :pairs ORDER BY score ASC, time ASC LIMIT 10";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([':pairs' => $pairs]);
            } else {
                $query = "SELECT * FROM leaderboard ORDER BY score ASC, time ASC LIMIT 10";
                $stmt = $this->pdo->query($query);
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }
}
