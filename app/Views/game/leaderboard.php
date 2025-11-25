<?php

/**
 * Vue : Classement des meilleurs joueurs
 * ---------------------------------------
 */
?>

<div class="memory-container">
    <div class="leaderboard-header">
        <h2 class="page-title">Classement - Top 10</h2>
        <p class="page-subtitle">Les meilleurs joueurs de Memory</p>
    </div>

    <!-- Filtre par nombre de paires -->
    <div class="leaderboard-filter">
        <label for="pairs-filter">Filtrer par nombre de paires :</label>
        <select id="pairs-filter" class="form-select" onchange="if(this.value === '') { window.location.href='<?= url('/game/leaderboard') ?>'; } else { window.location.href='<?= url('/game/leaderboard') ?>?pairs=' + this.value; }">
            <option value="" <?= !isset($filter_pairs) ? 'selected' : '' ?>>Tous les classements</option>
            <option value="3" <?= isset($filter_pairs) && $filter_pairs === 3 ? 'selected' : '' ?>>3 paires</option>
            <option value="4" <?= isset($filter_pairs) && $filter_pairs === 4 ? 'selected' : '' ?>>4 paires</option>
            <option value="5" <?= isset($filter_pairs) && $filter_pairs === 5 ? 'selected' : '' ?>>5 paires</option>
            <option value="6" <?= isset($filter_pairs) && $filter_pairs === 6 ? 'selected' : '' ?>>6 paires</option>
            <option value="7" <?= isset($filter_pairs) && $filter_pairs === 7 ? 'selected' : '' ?>>7 paires</option>
            <option value="8" <?= isset($filter_pairs) && $filter_pairs === 8 ? 'selected' : '' ?>>8 paires</option>
            <option value="9" <?= isset($filter_pairs) && $filter_pairs === 9 ? 'selected' : '' ?>>9 paires</option>
            <option value="10" <?= isset($filter_pairs) && $filter_pairs === 10 ? 'selected' : '' ?>>10 paires</option>
            <option value="11" <?= isset($filter_pairs) && $filter_pairs === 11 ? 'selected' : '' ?>>11 paires</option>
            <option value="12" <?= isset($filter_pairs) && $filter_pairs === 12 ? 'selected' : '' ?>>12 paires</option>
        </select>
    </div>

    <div class="leaderboard-card">
        <?php if (empty($leaderboard)): ?>
            <div class="empty-state">
                <p>Aucun score enregistré pour le moment</p>
                <p>Soyez le premier à jouer !</p>
                <a href="<?= url('/game' . (isset($filter_pairs) ? '?pairs=' . $filter_pairs : '')) ?>" class="btn btn-primary">Jouer maintenant</a>
            </div>
        <?php else: ?>
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th class="rank-col">#</th>
                        <th class="pseudo-col">Joueur</th>
                        <th class="moves-col">Coups</th>
                        <th class="time-col">Temps</th>
                        <th class="pairs-col">Paires</th>
                        <th class="score-col">Score</th>
                        <th class="date-col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaderboard as $index => $entry): ?>
                        <tr class="rank-<?= $index + 1 ?> <?= $index < 3 ? 'podium-rank' : '' ?>">
                            <td class="rank-col">
                                <?php if ($index === 0): ?>
                                    <span class="rank-badge rank-1">1</span>
                                <?php elseif ($index === 1): ?>
                                    <span class="rank-badge rank-2">2</span>
                                <?php elseif ($index === 2): ?>
                                    <span class="rank-badge rank-3">3</span>
                                <?php else: ?>
                                    <span class="rank-number"><?= $index + 1 ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="pseudo-col">
                                <?= htmlspecialchars($entry['pseudo'], ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td class="moves-col"><?= $entry['moves'] ?></td>
                            <td class="time-col">
                                <?php
                                $minutes = floor($entry['time'] / 60);
                                $seconds = $entry['time'] % 60;
                                echo sprintf('%02d:%02d', $minutes, $seconds);
                                ?>
                            </td>
                            <td class="pairs-col"><?= $entry['pairs'] ?> paires</td>
                            <td class="score-col">
                                <strong><?= $entry['score'] ?></strong>
                            </td>
                            <td class="date-col">
                                <?= date('d/m/Y', strtotime($entry['played_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="leaderboard-actions">
        <a href="<?= url('/game' . (isset($filter_pairs) ? '?pairs=' . $filter_pairs : '')) ?>" class="btn btn-primary">Jouer une partie</a>
    </div>
</div>