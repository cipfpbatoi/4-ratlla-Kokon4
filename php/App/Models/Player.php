<?php
namespace Joc4enRatlla\Models;

/**
 * Classe Jugador
 * Representa un jugador amb el seu nom, color y si es o no la máquina
 */
class Player {
    /**
     * @var string $name El nom del jugador
     * @var string $color El color del jugador
     * @var boolean $isAutomatic Si el jugador es la máquina o no
     */
    private $name;
    private $color;
    private $isAutomatic;


    /**
     * Constructor de la clase Jugador
     *
     * @param string $name El nom del jugador
     * @param string $color El color del jugador
     * @param boolean $isAutomatic Si es la máquina o no
     */
    public function __construct(string $name, string $color, bool $isAutomatic = false) {
        $this->name = $name;
        $this->color = $color;
        $this->isAutomatic = $isAutomatic; 
    }

    /**
     * Obté el nom del jugador
     *
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Obté el color del jugador
     *
     * @return string
     */
    public function getColor(): string {
        return $this->color;
    }

    /**
     * Retorna si el jugador és automàtic
     *
     * @return bool
     */
    public function isAutomatic(): bool {
        return $this->isAutomatic; 
    }

    /**
     * Defineix el nom del jugador
     *
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * Defineix el color del jugador
     *
     * @param string $color
     */
    public function setColor(string $color): void {
        $this->color = $color;
    }

    /**
     * Defineix que el jugador és automàtic
     *
     * @return void
     */
    public function setAutomatic(): void {
        $this->isAutomatic = true;
    }
}
?>