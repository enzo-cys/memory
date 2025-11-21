<?php
/**
 * Vue : Fin de partie - Formulaire de sauvegarde du score
 * --------------------------------------------------------
 */
?>

<div class="memory-container">
    <div class="result-card">
        <h1 class="result-title">Partie Terminée !</h1>
        
        <div class="result-stats">
            <div class="stat-item">
                <span class="stat-label">Temps</span>
                <span class="stat-value"><?= $formatted_time ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Coups</span>
                <span class="stat-value"><?= $moves ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Paires</span>
                <span class="stat-value"><?= $pairs ?></span>
            </div>
            <div class="stat-item highlight">
                <span class="stat-label">Score Total</span>
                <span class="stat-value"><?= $score ?></span>
            </div>
        </div>

        <div class="save-score-section">
            <h2>Enregistrer votre score</h2>
            <p class="subtitle">Entrez votre pseudo !</p>

            <form method="POST" action="/game/save-score" class="score-form">
                <div class="form-group">
                    <label for="pseudo">Pseudo :</label>
                    <input 
                        type="text" 
                        name="pseudo" 
                        id="pseudo" 
                        class="form-input"
                        placeholder="Votre pseudo"
                        required
                        maxlength="20"
                        pattern="[A-Za-z0-9_-]+"
                        title="Lettres, chiffres, tirets et underscores uniquement"
                    >
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        Enregistrer mon score
                    </button>
                    <a href="/game" class="btn btn-secondary">
                        Nouvelle partie
                    </a>
                </div>
            </form>
        </div>

        <div class="info-note">
            <p>Plus votre score est bas, meilleur il est !</p>
            <p>Score = Nombre de coups + Temps en secondes</p>
        </div>
    </div>
</div>
