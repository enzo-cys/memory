-- ========================================
-- MEMORY GAME - Base de données
-- ========================================
-- Création de la base de données pour le jeu Memory
-- Auteur: Enzo CYS
-- Date: 19 Novembre 2025
-- ========================================

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS memory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE memory;

-- ========================================
-- Table: scores
-- Description: Stocke tous les scores des joueurs
-- ========================================
CREATE TABLE IF NOT EXISTS scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(20) NOT NULL,
    moves INT NOT NULL COMMENT 'Nombre de coups joués',
    time INT NOT NULL COMMENT 'Temps en secondes',
    pairs INT NOT NULL COMMENT 'Nombre de paires dans la partie',
    played_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_pseudo (pseudo),
    INDEX idx_played_at (played_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Vue: leaderboard
-- Description: Top 10 des meilleurs scores (plus bas = meilleur)
-- ========================================
CREATE OR REPLACE VIEW leaderboard AS
SELECT 
    pseudo,
    moves,
    time,
    pairs,
    (moves + time) AS score,
    played_at,
    RANK() OVER (ORDER BY (moves + time) ASC, played_at ASC) AS `rank`
FROM scores
ORDER BY score ASC, played_at ASC
LIMIT 10;

-- ========================================
-- Vue: player_stats
-- Description: Statistiques par joueur
-- ========================================
CREATE OR REPLACE VIEW player_stats AS
SELECT 
    pseudo,
    COUNT(*) AS total_games,
    MIN(moves + time) AS best_score,
    AVG(moves + time) AS avg_score,
    MAX(played_at) AS last_played
FROM scores
GROUP BY pseudo
ORDER BY best_score ASC;