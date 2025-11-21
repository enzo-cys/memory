<?php
/**
 * Redirection automatique vers le dossier public
 * Ce fichier redirige toutes les requêtes vers le dossier public/
 */

// Redirection permanente vers le dossier public
header('Location: /memory/public/', true, 301);
exit;
