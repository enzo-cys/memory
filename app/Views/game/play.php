<?php

/**
 * Vue : Partie en cours
 * ---------------------
 */
$currentTime = time() - $start_time;
$isComplete = $matches >= $pairs;
$needsFlipBack = isset($_SESSION['needs_flip_back']) && $_SESSION['needs_flip_back'];
// Nettoyer le flag après l'avoir lu
if ($needsFlipBack) {
    unset($_SESSION['needs_flip_back']);
}
?>

<div class="memory-container">

    <?php if ($isComplete): ?>
        <div class="victory-notice">
            <h2>Félicitations !</h2>
            <p>Vous avez trouvé toutes les paires !</p>
            <a href="<?= url('/game/finish') ?>" class="btn btn-success">Voir les résultats</a>
        </div>
    <?php endif; ?>

    <div id="game-board" class="game-board pairs-<?= $pairs ?>">
        <?php foreach ($cards as $card): ?>
            <?php
            $cardData = json_decode($card['symbol'], true);
            $imageName = $cardData['image'] ?? 'dos.png';
            ?>
            <div class="card-wrapper">
                <?php if ($card['isMatched']): ?>
                    <div class="card matched">
                        <div class="card-front">
                            <img src="<?= asset('images/' . $imageName) ?>" alt="Card" class="card-image">
                        </div>
                    </div>
                <?php elseif ($card['isFlipped']): ?>
                    <div class="card flipped">
                        <div class="card-front">
                            <img src="<?= asset('images/' . $imageName) ?>" alt="Card" class="card-image">
                        </div>
                    </div>
                <?php else: ?>
                    <form method="POST" action="<?= url('/game/flip') ?>" class="card-form">
                        <input type="hidden" name="card_id" value="<?= $card['id'] ?>">
                        <button type="submit" class="card">
                            <div class="card-back">
                                <img src="<?= asset('images/dos.png') ?>" alt="Card back" class="card-image">
                            </div>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    // Timer - Mise à jour toutes les secondes
    (function() {
        const startTime = <?= $start_time ?>;
        const timerEl = document.getElementById('timer');

        function updateTimer() {
            const now = Math.floor(Date.now() / 1000);
            const elapsed = now - startTime;
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            timerEl.textContent =
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');
        }

        updateTimer();
        <?php if ($matches < $pairs): ?>
            setInterval(updateTimer, 1000);
        <?php endif; ?>
    })();

    // Préserver la position de scroll lors des rechargements
    (function() {
        // Sauvegarder la position avant chaque soumission de formulaire
        const forms = document.querySelectorAll('.card-form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                sessionStorage.setItem('gameScrollY', window.scrollY.toString());
            });
        });

        // Restaurer la position après le chargement de la page
        const savedScrollY = sessionStorage.getItem('gameScrollY');
        if (savedScrollY !== null) {
            window.scrollTo(0, parseInt(savedScrollY));
        }
    })();

    // Auto-retourner les cartes incorrectes après 1 seconde
    <?php if ($needsFlipBack): ?>
            (function() {
                setTimeout(function() {
                    // Recharger la page pour retourner les cartes
                    window.location.href = '<?= url('/game/flipback') ?>';
                }, 1000);
            })();
    <?php endif; ?>
</script>