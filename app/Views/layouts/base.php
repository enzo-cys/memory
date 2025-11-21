<?php

/**
 * Layout principal
 * -----------------
 * Ce fichier définit la structure HTML commune à toutes les pages.
 * Il inclut dynamiquement le contenu spécifique à chaque vue via la variable $content.
 */
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">

  <!-- Titre de la page (sécurisé avec htmlspecialchars, valeur par défaut si non défini) -->
  <title><?= isset($title) ? htmlspecialchars($title, ENT_QUOTES, 'UTF-8') : 'Memory Game - Overlord' ?></title>

  <!-- Bonne pratique : rendre le site responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/assets/css/global.css">
</head>
<body<?php
      $bodyClass = '';
      if (isset($show_game_header) && $show_game_header) {
        $bodyClass = 'in-game';
      }
      echo $bodyClass ? ' class="' . $bodyClass . '"' : '';
      ?>>
  <!-- Header global (caché pendant une partie) -->
  <?php if (!isset($show_game_header) || !$show_game_header): ?>
    <header class="site-header">
      <div class="header-content">
        <div class="header-title">
          <a href="/game" class="title-link">
            <h1 class="site-title">Memory Game</h1>
            <p class="site-subtitle">ذاكرة - Jeu des paires</p>
          </a>
        </div>
        <div class="header-nav">
          <a href="/game" class="nav-link">Jouer une partie</a>
          <a href="/game/leaderboard" class="nav-link">Voir le Classement</a>
        </div>
      </div>
    </header>
  <?php endif; ?>

  <?php if (isset($show_game_header) && $show_game_header): ?>
    <!-- Header pendant le jeu -->
    <header class="game-header">
      <div class="game-header-content">
        <div class="game-stats">
          <div class="stat-box">
            <span class="stat-label">Temps</span>
            <span class="stat-value" id="timer">00:00</span>
          </div>
          <div class="stat-box">
            <span class="stat-label">Coups</span>
            <span class="stat-value"><?= $moves ?? 0 ?></span>
          </div>
          <div class="stat-box">
            <span class="stat-label">Paires</span>
            <span class="stat-value"><?= ($matches ?? 0) ?> / <?= $pairs ?? 0 ?></span>
          </div>
        </div>
        <a href="/game/quit" class="btn-quit" onclick="return confirm('Abandonner la partie ?')">Quitter</a>
      </div>
    </header>
  <?php endif; ?>

  <!-- Contenu principal injecté depuis BaseController -->
  <main>
    <?= $content ?>
  </main>

  <!-- Footer global -->
  <footer class="site-footer">
    <div class="footer-content">
      <h3>Vous voulez être dans le classement alors vous devez :</h3>
      <ul class="footer-rules">
        <li>Trouvez toutes les paires identiques</li>
        <li>Le faire en moins de temps qu'il ne faut pour dire "congolexicomatisation des loi du marché du travail"</li>
        <li>Moins de tentative = meilleurs score</li>
      </ul>
      <div class="footer-tips">
        <h4>Comment fonctionne le score ?</h4>
        <p>Le score est calculé en additionnant le nombre de coups et le temps en secondes. Plus le score est bas, meilleur il est.</p>
        <p><strong>Exemple :</strong> 10 coups + 30 secondes = Score de 40 points</p>
        <h4>Comment améliorer votre score ?</h4>
        <ul>
          <li>Mémorisez bien l'emplacement des cartes dès la première révélation</li>
          <li>Jouez rapidement mais sans précipitation pour éviter les erreurs</li>
          <li>Minimisez le nombre de tentatives infructueuses</li>
          <li>Entraînez-vous régulièrement pour développer votre mémoire visuelle</li>
        </ul>
      </div>
    </div>
  </footer>
  </body>

</html>