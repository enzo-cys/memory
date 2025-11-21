<?php
namespace App\Models;

/**
 * Classe Card
 * -----------
 * Représente une carte du jeu Memory
 * Contient les propriétés et méthodes pour gérer l'état d'une carte
 */
class Card
{
    /**
     * @var int Identifiant unique de la carte
     */
    private int $id;

    /**
     * @var string Symbole/emoji affiché sur la carte
     */
    private string $symbol;

    /**
     * @var bool État de retournement de la carte
     */
    private bool $isFlipped;

    /**
     * @var bool État d'appariement de la carte
     */
    private bool $isMatched;

    /**
     * Constructeur de la carte
     *
     * @param int $id Identifiant de la carte
     * @param string $symbol Symbole de la carte
     */
    public function __construct(int $id, string $symbol)
    {
        $this->id = $id;
        $this->symbol = $symbol;
        $this->isFlipped = false;
        $this->isMatched = false;
    }

    /**
     * Retourne l'identifiant de la carte
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Retourne le symbole de la carte
     *
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * Vérifie si la carte est retournée
     *
     * @return bool
     */
    public function isFlipped(): bool
    {
        return $this->isFlipped;
    }

    /**
     * Vérifie si la carte est appariée
     *
     * @return bool
     */
    public function isMatched(): bool
    {
        return $this->isMatched;
    }

    /**
     * Retourne la carte
     */
    public function flip(): void
    {
        $this->isFlipped = !$this->isFlipped;
    }

    /**
     * Marque la carte comme appariée
     */
    public function setMatched(): void
    {
        $this->isMatched = true;
    }

    /**
     * Réinitialise l'état de retournement
     */
    public function reset(): void
    {
        $this->isFlipped = false;
    }

    /**
     * Retourne un tableau associatif des propriétés de la carte
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'symbol' => $this->symbol,
            'isFlipped' => $this->isFlipped,
            'isMatched' => $this->isMatched
        ];
    }
}
