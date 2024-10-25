<?php
namespace Joc4enRatlla\Controllers;

use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Models\Game;
use Joc4enRatlla\Exceptions\IllegalMoveException;

/**
 * Clase GameController
 *
 * Controla el flujo del juego 4 en línea, gestionando las solicitudes del cliente y coordinando la lógica del juego.
 */
class GameController
{
    /**
     * @var Game $game Instancia del juego que gestiona el estado y las operaciones del mismo.
     */
    private Game $game;

    /**
     * Constructor de la clase GameController.
     *
     * Inicializa una nueva partida o restaura una existente desde la sesión. 
     * Procesa la solicitud recibida para jugar la partida.
     *
     * @param array|null $request Array asociativo que contiene los parámetros de la solicitud del cliente.
     */
    public function __construct($request = null)
    {
        if (!isset($_SESSION['game'])) {
            $jugador1 = new Player($request['name'], $request['color']);
            $jugador2 = new Player("Jugador 2", "pink", true);
            $this->game = new Game($jugador1, $jugador2);
            $this->game->save();
        } else {
            $this->game = Game::restore();
        }
        $this->play($request);
    }

    /**
     * Controla el flujo del juego según las acciones del usuario y actualiza el estado del juego.
     *
     * Gestiona las acciones de reinicio, salida del juego, así como los movimientos de los jugadores,
     * lanzando excepciones si el movimiento no es válido.
     *
     * @param array $request Array asociativo que contiene los parámetros de la solicitud del cliente.
     * @return void
     */
    public function play(array $request)
    {
        $error = "";
        
        // Reinicia el juego si se solicita
        if (isset($request['reset'])) {
            $this->game->reset();
            $this->game->save();
        }

        // Finaliza la sesión y sale del juego si se solicita
        if (isset($request['exit'])) {
            unset($_SESSION['game']);
            session_destroy();
            header("location:/index.php");
            exit();
        }

        // Gestiona el movimiento del jugador si no es automático y aún no hay ganador
        if (!$this->game->getWinner() && !$this->game->getPlayer()->isAutomatic() && isset($request['columna'])) {
            try {
                $this->game->play($request['columna']);
            } catch (IllegalMoveException $e) {
                $error = $e->getMessage();
            }
        }

        // Si es el turno de un jugador automático, realiza el movimiento automático
        if (!$this->game->getWinner() && $this->game->getPlayer()->isAutomatic()) {
            $this->game->playAutomatic();
        }

        // Obtiene los datos actualizados del juego para la vista
        $board = $this->game->getBoard();
        $players = $this->game->getPlayers();
        $winner = $this->game->getWinner();
        $scores = $this->game->getScores();

        // Carga la vista principal del juego
        loadView('index', compact('board', 'players', 'winner', 'scores', 'error'));
    }
}
