<?php

namespace Joc4enRatlla\Models;

use Joc4enRatlla\Exceptions\IllegalMoveException;
use Joc4enRatlla\Models\Board;
use Joc4enRatlla\Models\Player;

/**
 * Class Game
 * 
 * Representa una partida del juego Cuatro en Línea, gestionando el estado del juego, el tablero, los jugadores y sus movimientos.
 */
class Game {
    /**
     * @var Board El tablero del juego.
     */
    private Board $board;

    /**
     * @var int El jugador que tiene el siguiente turno.
     */
    private int $nextPlayer;
    
    /**
     * @var Player[] Lista de los jugadores en el juego.
     */
    private array $players;

    /**
     * @var Player|null El jugador ganador, si hay alguno.
     */
    private ?Player $winner;
    
    /**
     * @var int[] Puntuaciones de los jugadores.
     */
    private array $scores = [1 => 0, 2 => 0];

    /**
     * Constructor de la clase Game.
     *
     * @param Player $jugador1 El primer jugador.
     * @param Player $jugador2 El segundo jugador.
     */
    public function __construct(Player $jugador1, Player $jugador2) {
        $this->board = new Board();
        $this->nextPlayer = random_int(1, 2); // Determina aleatoriamente quién juega primero.
        $this->players = [1 => $jugador1, 2 => $jugador2];
        $this->winner = null;
    }

    /**
     * Obtiene el tablero actual del juego.
     *
     * @return Board El tablero del juego.
     */
    public function getBoard(): Board {
        return $this->board;
    }

    /**
     * Obtiene el jugador que tiene el próximo turno.
     *
     * @return int El número del siguiente jugador (1 o 2).
     */
    public function getNextPlayer(): int {
        return $this->nextPlayer;
    }

    /**
     * Obtiene el jugador que tiene el turno actual.
     *
     * @return Player El jugador actual.
     */
    public function getPlayer(): Player {
        return $this->players[$this->nextPlayer];
    }

    /**
     * Obtiene el jugador ganador, si hay uno.
     *
     * @return Player|null El jugador ganador o null si no hay ninguno.
     */
    public function getWinner(): ?Player {
        return $this->winner;
    }

    /**
     * Establece el siguiente jugador que debe jugar.
     *
     * @param int $jugador El número del jugador (1 o 2).
     * @return void
     */
    public function setNextPlayer(int $jugador): void {
        $this->nextPlayer = $jugador;
    }

    /**
     * Obtiene la lista de jugadores.
     *
     * @return Player[] Un array con los dos jugadores.
     */
    public function getPlayers(): array {
        return $this->players;
    }

    /**
     * Obtiene las puntuaciones de los jugadores.
     *
     * @return int[] Un array con las puntuaciones de los jugadores.
     */
    public function getScores(): array {
        return $this->scores;
    }

    /**
     * Establece las puntuaciones de los jugadores.
     *
     * @param int[] $scores Array asociativo con las puntuaciones de los jugadores.
     * @return void
     */
    public function setScores(array $scores): void {
        $this->scores = $scores;
    }

    /**
     * Reinicia el juego, creando un nuevo tablero y reiniciando los jugadores.
     *
     * @return void
     */
    public function reset(): void {
        $this->board = new Board();
        $this->nextPlayer = random_int(1, 2);
        $this->winner = null;
    }

    /**
     * Realiza un movimiento en el juego.
     * Si el movimiento no es válido, lanza una excepción.
     *
     * @param int $columna La columna donde se realiza el movimiento.
     * @throws IllegalMoveException Si el movimiento es inválido.
     * @return void
     */
    public function play(int $columna): void {
        if (!$this->board->isValidMove($columna)) {
            throw new IllegalMoveException("Moviment no vàlid");
        }

        $coord = $this->board->setMovementOnBoard($columna, $this->nextPlayer);

        if ($this->board->checkWin($coord)) {
            $this->winner = $this->players[$this->nextPlayer];
            $this->scores[$this->nextPlayer]++;
        } else {
            $this->nextPlayer = ($this->nextPlayer == 1) ? 2 : 1;
        }

        $this->save();
    }

    /**
     * Realiza un movimiento automático (jugada de la máquina).
     * 
     * Trata de ganar en el próximo turno, o de evitar que el oponente gane.
     *
     * @return void
     */
    public function playAutomatic(): void {
        $opponent = $this->nextPlayer === 1 ? 2 : 1;

        // Intentar ganar en la siguiente jugada
        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            if ($this->board->isValidMove($col)) {
                $tempBoard = clone($this->board);
                $coord = $tempBoard->setMovementOnBoard($col, $this->nextPlayer);
                if ($tempBoard->checkWin($coord)) {
                    $this->play($col);
                    return;
                }
            }
        }

        // Bloquear al oponente si puede ganar
        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            if ($this->board->isValidMove($col)) {
                $tempBoard = clone($this->board);
                $coord = $tempBoard->setMovementOnBoard($col, $opponent);
                if ($tempBoard->checkWin($coord)) {
                    $this->play($col);
                    return;
                }
            }
        }

        // Elegir una jugada al azar o en el medio
        $possibles = [];
        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            if ($this->board->isValidMove($col)) {
                $possibles[] = $col;
            }
        }

        // Elegir columna al azar, con preferencia por el centro
        if (count($possibles) > 2) {
            $random = rand(-1, 1);
        }
        $middle = (int) (count($possibles) / 2) + $random;
        $this->play($possibles[$middle]);
    }

    /**
     * Guarda el estado del juego en la sesión.
     *
     * @return void
     */
    public function save(): void {
        $_SESSION['game'] = serialize($this);
    }

    /**
     * Restaura el estado del juego desde la sesión.
     *
     * @return Game El juego restaurado.
     */
    public static function restore(): Game {
        return unserialize($_SESSION['game'], [Game::class]);
    }
}
