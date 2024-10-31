<?php

namespace Joc4enRatlla\Models;

/**
 * Class Player
 * 
 * Representa un jugador en el juego con su nombre, color y si es una máquina o un jugador humano.
 */
class Player {
    /**
     * @var string El nombre del jugador.
     */
    private string $name;

    /**
     * @var string El color del jugador.
     */
    private string $color;

    /**
     * @var bool Indica si el jugador es una máquina (IA).
     */
    private bool $isAutomatic;

    /**
     * Constructor de la clase Player.
     *
     * @param string $name El nombre del jugador.
     * @param string $color El color del jugador.
     * @param bool $isAutomatic Indica si es la máquina (por defecto es false).
     */
    public function __construct(string $name, string $color, bool $isAutomatic = false) {
        $this->name = $name;
        $this->color = $color;
        $this->isAutomatic = $isAutomatic; 
    }

    /**
     * Obtiene el nombre del jugador.
     *
     * @return string El nombre del jugador.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Obtiene el color del jugador.
     *
     * @return string El color del jugador.
     */
    public function getColor(): string {
        return $this->color;
    }

    /**
     * Indica si el jugador es automático (máquina).
     *
     * @return bool True si el jugador es automático, false en caso contrario.
     */
    public function isAutomatic(): bool {
        return $this->isAutomatic; 
    }

    /**
     * Define el nombre del jugador.
     *
     * @param string $name El nuevo nombre del jugador.
     * @return void
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * Define el color del jugador.
     *
     * @param string $color El nuevo color del jugador.
     * @return void
     */
    public function setColor(string $color): void {
        $this->color = $color;
    }

    /**
     * Define que el jugador es automático (máquina).
     *
     * @return void
     */
    public function setAutomatic(): void {
        $this->isAutomatic = true;
    }
}
?>
