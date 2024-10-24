<?php

namespace Joc4enRatlla\Models;

/**
 * Class Board
 *
 * Representa el tablero del juego Cuatro en Línea.
 */
class Board
{
    /**
     * Número de filas en el tablero.
     */
    public const FILES = 6;

    /**
     * Número de columnas en el tablero.
     */
    public const COLUMNS = 7;

    /**
     * Direcciones posibles para comprobar una victoria.
     * [0, 1] - Horizontal derecha
     * [1, 0] - Vertical hacia abajo
     * [1, 1] - Diagonal abajo-derecha
     * [1, -1] - Diagonal abajo-izquierda
     */
    public const DIRECTIONS = [
        [0, 1],   // Horizontal derecha
        [1, 0],   // Vertical abajo
        [1, 1],   // Diagonal abajo-derecha
        [1, -1]   // Diagonal abajo-izquierda
    ];

    /**
     * Matriz que representa el estado del tablero.
     *
     * @var array
     */
    private array $slots;

    /**
     * Constructor de la clase Board.
     * Inicializa el tablero vacío.
     */
    public function __construct()
    {
        $this->slots = $this->createEmptyBoard();
    }

    /**
     * Obtiene el estado actual del tablero.
     *
     * @return array Matriz que representa el tablero.
     */
    public function getSlots(): array
    {
        return $this->slots;
    }

    /**
     * Crea una representación vacía del tablero.
     * Llena el tablero con valores de 0 que representan espacios vacíos.
     *
     * @return array Tablero vacío.
     */
    private static function createEmptyBoard(): array
    {
        $emptyBoard = array_fill(1, self::FILES, array_fill(1, self::COLUMNS, 0));
        return $emptyBoard;
    }

    /**
     * Realiza un movimiento en el tablero en la columna especificada por el jugador.
     *
     * @param int $column Columna donde se quiere colocar la ficha.
     * @param int $player Identificador del jugador que realiza el movimiento.
     * @return array Coordenadas [fila, columna] donde se realizó el movimiento.
     */
    public function setMovementOnBoard(int $column, int $player): array
    {
        $selectedRow = null;
        foreach (range(self::FILES, 1) as $row) {
            if ($this->slots[$row][$column] === 0) {
                $this->slots[$row][$column] = $player;
                $selectedRow = $row;
                break;
            }
        }
        return [$selectedRow, $column];
    }

    /**
     * Comprueba si un movimiento ha resultado en una victoria.
     *
     * @param array $coord Coordenadas [fila, columna] del último movimiento.
     * @return bool True si se ha formado una línea de 4, de lo contrario False.
     */
    public function checkWin(array $coord): bool
    {
        [$x, $y] = $coord;
        $playerToken = $this->slots[$x][$y];

        foreach (self::DIRECTIONS as $direction) {
            $lineCount = $this->getLineLength($x, $y, $direction, $playerToken) 
                       + $this->getLineLength($x, $y, [-$direction[0], -$direction[1]], $playerToken) 
                       + 1;

            if ($lineCount >= 4) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calcula la longitud de una línea en una dirección dada, partiendo de una ficha.
     *
     * @param int $x Fila del último movimiento.
     * @param int $y Columna del último movimiento.
     * @param array $direction Dirección en la que se va a comprobar [dx, dy].
     * @param int $player Identificador del jugador.
     * @return int Número de fichas alineadas en la dirección indicada.
     */
    private function getLineLength(int $x, int $y, array $direction, int $player): int
    {
        [$dx, $dy] = $direction;
        $total = 0;

        for ($i = 1; $i < 4; $i++) {
            $newX = $x + ($i * $dx);
            $newY = $y + ($i * $dy);

            if ($this->isWithinBounds($newX, $newY) && $this->slots[$newX][$newY] === $player) {
                $total++;
            } else {
                break;
            }
        }

        return $total;
    }

    /**
     * Verifica si las coordenadas están dentro de los límites del tablero.
     *
     * @param int $x Fila a comprobar.
     * @param int $y Columna a comprobar.
     * @return bool True si las coordenadas están dentro del tablero, False en caso contrario.
     */
    private function isWithinBounds(int $x, int $y): bool
    {
        return $x > 0 && $x <= self::FILES && $y > 0 && $y <= self::COLUMNS;
    }

    /**
     * Verifica si un movimiento en la columna es válido.
     * Un movimiento es válido si la columna no está llena.
     *
     * @param int $column Columna a verificar.
     * @return bool True si el movimiento es válido, False en caso contrario.
     */
    public function isValidMove(int $column): bool
    {
        return $this->slots[1][$column] === 0;
    }

    /**
     * Comprueba si el tablero está completamente lleno.
     *
     * @return bool True si el tablero está lleno, False si aún hay espacios vacíos.
     */
    public function isFull(): bool
    {
        foreach (range(1, self::COLUMNS) as $col) {
            if ($this->slots[1][$col] === 0) {
                return false;
            }
        }
        return true;
    }
}
