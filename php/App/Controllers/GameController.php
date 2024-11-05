<?php

namespace Joc4enRatlla\Controllers;

use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Models\Game;
use Joc4enRatlla\Exceptions\IllegalMoveException;

/**
 * Controlador para gestionar la lógica del juego, incluyendo la creación, restauración,
 * y operaciones de juego como mover y reiniciar partidas.
 */
class GameController {
    /**
     * Instancia de la clase Game que representa el estado actual del juego.
     *
     * @var Game
     */
    private Game $game;

    /**
     * Conexión a la base de datos para guardar y restaurar el estado del juego.
     *
     * @var mixed
     */
    private $db;
    
    /**
     * Constructor de GameController.
     * Inicializa el juego a partir de la solicitud o restaura un juego existente desde la sesión.
     *
     * @param array|null $request   Datos de la solicitud, incluyendo información de los jugadores y sus movimientos.
     * @param mixed $db             Conexión a la base de datos, opcional para guardar y restaurar el juego.
     */
    public function __construct($request = null, $db = null) {
        $this->db = $db;

        if (!isset($_SESSION['game'])) {
            $jugador1 = new Player($request['name'], $request['color']);
            $jugador2 = new Player("Jugador 2", "pink", true);
            $this->game = new Game($jugador1, $jugador2);
            $this->game->save();
        } else {
            $this->game = Game::restore();
        }

        // Ejecuta el turno actual del juego
        $this->play($request);
    }

    /**
     * Ejecuta una jugada o realiza una operación en el juego según la solicitud.
     *
     * @param array $request   Datos de la solicitud que incluyen acciones de juego (e.g., jugar, reiniciar, guardar).
     * @return void
     */
    public function play(array $request) {
        $error = "";

        if (isset($request['reset'])) {
            $this->game->reset();
            $this->game->save();
        }

        if (isset($request['exit'])) {
            unset($_SESSION['game']);
            session_destroy();
            header("location:/index.php");
            exit();
        }

        if (isset($request['save'])) {
            $this->game->saveGame($this->db);
        }

        if (isset($request['restore'])) {
            $this->game = Game::restoreGame($this->db);
            $this->game->save();
        }

        if (!$this->game->getBoard()->isFull()) {
            if (!$this->game->getWinner() && !$this->game->getPlayer()->isAutomatic() && isset($request['columna'])) {
                try {
                    $this->game->play($request['columna']);
                } catch (IllegalMoveException $e) {
                    $error = $e->getMessage();
                }
            }

            if (!$this->game->getWinner() && $this->game->getPlayer()->isAutomatic()) {
                $this->game->playAutomatic();
            }

        } else {
            $error = "Empat";
        }

        $board = $this->game->getBoard();
        $players = $this->game->getPlayers();
        $winner = $this->game->getWinner();
        $scores = $this->game->getScores();

        loadView('index', compact('board', 'players', 'winner', 'scores', 'error'));
    }
}
