<?php

namespace Joc4enRatlla\Models;

use Joc4enRatlla\Exceptions\IllegalMoveException;
use Joc4enRatlla\Models\Board;
use Joc4enRatlla\Models\Player;

/**
 * Clase Game que representa la lógica principal de una partida,
 * gestionando los jugadores, el tablero y el estado de la partida.
 */
class Game {
    /**
     * Tablero del juego.
     *
     * @var Board
     */
    private Board $board;

    /**
     * Número del próximo jugador en turno (1 o 2).
     *
     * @var int
     */
    private int $nextPlayer;

    /**
     * Array de jugadores de la partida.
     *
     * @var Player[]
     */
    private array $players;

    /**
     * Jugador ganador de la partida, si existe.
     *
     * @var Player|null
     */
    private ?Player $winner;

    /**
     * Puntuaciones de los jugadores.
     *
     * @var int[]
     */
    private array $scores = [1 => 0, 2 => 0];

    /**
     * Constructor de la clase Game.
     *
     * @param Player $jugador1 Jugador 1 de la partida.
     * @param Player $jugador2 Jugador 2 de la partida.
     */
    public function __construct(Player $jugador1, Player $jugador2) {
        $this->board = new Board();
        $this->nextPlayer = random_int(1, 2);
        $this->players = [1 => $jugador1, 2 => $jugador2];
        $this->winner = null;
    }
    
    /**
     * Obtiene el tablero del juego.
     *
     * @return Board
     */
    public function getBoard(): Board {
        return $this->board;
    }
    
    /**
     * Obtiene el número del siguiente jugador en turno.
     *
     * @return int
     */
    public function getNextPlayer(): int {
        return $this->nextPlayer;
    }

    /**
     * Obtiene el jugador actual en turno.
     *
     * @return Player
     */
    public function getPlayer(): Player {
        return $this->players[$this->nextPlayer];
    }

    /**
     * Obtiene el jugador ganador de la partida, si existe.
     *
     * @return Player|null
     */
    public function getWinner(): ?Player {
        return $this->winner;
    }
    
    /**
     * Establece el siguiente jugador en turno.
     *
     * @param int $jugador Número del jugador (1 o 2).
     * @return void
     */
    public function setNextPlayer(int $jugador): void {
        $this->nextPlayer = $jugador;
    }
    
    /**
     * Obtiene el array de jugadores de la partida.
     *
     * @return Player[]
     */
    public function getPlayers(): array {
        return $this->players;
    }
    
    /**
     * Obtiene las puntuaciones de los jugadores.
     *
     * @return int[]
     */
    public function getScores(): array {
        return $this->scores;
    }
    
    /**
     * Establece las puntuaciones de los jugadores.
     *
     * @param int[] $scores Array de puntuaciones.
     * @return void
     */
    public function setScores(array $scores): void {
        $this->scores = $scores;
    }
    
    /**
     * Reinicia el estado de la partida.
     *
     * @return void
     */
    public function reset(): void {
        $this->board = new Board();
        $this->nextPlayer = random_int(1, 2);
        $this->winner = null;
    }

    /**
     * Realiza un movimiento en el juego en la columna especificada.
     *
     * @param int $columna Número de la columna para el movimiento.
     * @throws IllegalMoveException Si el movimiento no es válido.
     * @return void
     */
    public function play($columna) {
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
     * Realiza un movimiento automático por el jugador de turno.
     *
     * @return void
     */
    public function playAutomatic() {
        $opponent = $this->nextPlayer === 1 ? 2 : 1;

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

        $possibles = array();
        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            if ($this->board->isValidMove($col)) {
                $possibles[] = $col;
            }
        }

        if (count($possibles)) {
            $random = count($possibles) > 2 ? rand(-1, 1) : 0;
            $middle = (int) ((count($possibles) + 0.9) / 2) + $random;
            $inthemiddle = $possibles[$middle];
            $this->play($inthemiddle);
        }
    }

    /**
     * Guarda el estado actual del juego en la sesión.
     *
     * @return void
     */
    public function save() {
        $_SESSION['game'] = serialize($this);
    }

    /**
     * Restaura el juego desde la sesión.
     *
     * @return Game
     */
    public static function restore() {
        return unserialize($_SESSION['game'], [Game::class]);
    }

    /**
     * Guarda el estado actual del juego en la base de datos.
     *
     * @param mixed $db Conexión a la base de datos.
     * @return bool True si se guardó correctamente, false en caso contrario.
     */
    public function saveGame($db) {
        $usuari_id = $_SESSION['user_id'];
        $game  = $_SESSION['game'];

        $query = "INSERT INTO partides (usuari_id, game ) 
                  VALUES (:usuari_id, :game) 
                  ON DUPLICATE KEY UPDATE 
                  game = :game";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':usuari_id', $usuari_id);
        $stmt->bindParam(':game', $game);

        return $stmt->execute();
    }

    /**
     * Restaura el juego desde la base de datos.
     *
     * @param mixed $db Conexión a la base de datos.
     * @return Game
     */
    public static function restoreGame($db) {
        $usuari_id = $_SESSION['user_id'];
        $query = "SELECT * FROM partides WHERE usuari_id = :usuari_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':usuari_id', $usuari_id);
        $stmt->execute();
        $partida = $stmt->fetch(\PDO::FETCH_ASSOC);

        return unserialize($partida['game'], [Game::class]);
    }
}
