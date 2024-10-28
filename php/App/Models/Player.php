<?php

namespace Joc4enRatlla\Models;

/**
 * Classe  Jugador
 * Representa un jugador amb el seu nom, color y si es o no la máquina
 */
class Player {
    /**
     * @var string name El nom del jugador
     * @var string color El color del jugador
     * @var boolean isAutomatic Si el jugador es la máquina o no
     */
    private $name;  
    private $color;     
    private $isAutomatic; 


    /**
     * Constructor de la clase Jugador
     *
     * @param String $name El nom del jugador
     * @param String $color El color del jugador
     * @param boolean $isAutomatic Si es la máquina o no
     */
    public function __construct( $name, $color, $isAutomatic = false) {
        $this->name = $name;
        $this->color = $color;
        $this->isAutomatic = false;
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function getName(){
        return $this->name;
    }

    public function getColor(){
        return $this->color;
    }

    public function getIsAutomatic(){
        return $this->isAutomatic;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setColor($color){
        $this->color = $color;
    }

    public function setAutomatic(){
        $this->isAutomatic = true;
    }
}
?>