<?php

namespace Joc4enRatlla\Models;

/**
 * Clase Player
 *
 * Representa a un jugador en el juego 4 en raya.
 */
class Player {
    /**
     * @var string Nombre del jugador.
     */
    private $name;

    /**
     * @var string Color de las fichas del jugador.
     */
    private $color;

    /**
     * @var bool Indica si el jugador es automático o no.
     */
    private $isAutomatic;

    /**
     * Constructor de la clase Player.
     *
     * @param string $name Nombre del jugador.
     * @param string $color Color de las fichas del jugador.
     * @param bool $isAutomatic Indica si el jugador es automático o no (por defecto, falso).
     */
    public function __construct($name, $color, $isAutomatic = false) {
        $this->name = $name;
        $this->color = $color;
        $this->isAutomatic = $isAutomatic;
    }

    /**
     * Obtiene el nombre del jugador.
     *
     * @return string Nombre del jugador.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Obtiene el color de las fichas del jugador.
     *
     * @return string Color de las fichas.
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * Indica si el jugador es automático o no.
     *
     * @return bool Verdadero si el jugador es automático, falso en caso contrario.
     */
    public function isAutomatic() {
        return $this->isAutomatic;
    }

    /**
     * Establece el nombre del jugador.
     *
     * @param string $name Nombre del jugador.
     * @return Player Devuelve el objeto Player para permitir la concatenación de métodos.
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Establece el color de las fichas del jugador.
     *
     * @param string $color Color de las fichas.
     * @return Player Devuelve el objeto Player para permitir la concatenación de métodos.
     */
    public function setColor($color) {
        $this->color = $color;
        return $this;
    }

    /**
     * Establece si el jugador es automático o no.
     *
     * @param bool $isAutomatic Verdadero si el jugador es automático, falso en caso contrario.
     * @return Player Devuelve el objeto Player para permitir la concatenación de métodos.
     */
    public function setAutomatic($isAutomatic) {
        $this->isAutomatic = $isAutomatic;
        return $this;
    }

    /**
     * Devuelve una representación en cadena del objeto Player.
     *
     * @return string Representación del objeto Player.
     */
    public function __toString() {
        $playMode = $this->isAutomatic ? 'Automático' : 'Manual';
        $ret = "Nombre: " . $this->name . "<br/>";
        $ret .= "Color de las fichas: " . $this->color . "<br/>";
        $ret .= "Modo de juego: " . $playMode . "<br/>";
        return $ret;
    }
}
