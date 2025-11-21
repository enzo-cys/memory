<?php
/**
 * Vue : Menu principal du jeu Memory
 * -----------------------------------
 */
?>

<div class="memory-container">
    <div class="menu-card">
        <h2>Nouvelle Partie</h2>
        <p class="description">Choisissez le nombre de paires et testez votre mémoire !</p>

        <form method="POST" action="/game/start" class="game-form">
            <div class="form-group">
                <label for="pairs">Nombre de paires :</label>
                <select name="pairs" id="pairs" class="form-select">
                    <?php $selected_pairs = isset($default_pairs) ? $default_pairs : 6; ?>
                    <option value="3" <?= $selected_pairs === 3 ? 'selected' : '' ?>>3 paires (Facile)</option>
                    <option value="4" <?= $selected_pairs === 4 ? 'selected' : '' ?>>4 paires (Facile)</option>
                    <option value="5" <?= $selected_pairs === 5 ? 'selected' : '' ?>>5 paires (Facile)</option>
                    <option value="6" <?= $selected_pairs === 6 ? 'selected' : '' ?>>6 paires (Moyen)</option>
                    <option value="7" <?= $selected_pairs === 7 ? 'selected' : '' ?>>7 paires (Moyen)</option>
                    <option value="8" <?= $selected_pairs === 8 ? 'selected' : '' ?>>8 paires (Moyen)</option>
                    <option value="9" <?= $selected_pairs === 9 ? 'selected' : '' ?>>9 paires (Difficile)</option>
                    <option value="10" <?= $selected_pairs === 10 ? 'selected' : '' ?>>10 paires (Difficile)</option>
                    <option value="11" <?= $selected_pairs === 11 ? 'selected' : '' ?>>11 paires (Expert)</option>
                    <option value="12" <?= $selected_pairs === 12 ? 'selected' : '' ?>>12 paires (Expert)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Commencer la partie
            </button>
        </form>
    </div>

    <div class="menu-actions">
        <a href="/game/leaderboard" class="btn btn-success">
            Voir le Classement
        </a>
    </div>
</div>
