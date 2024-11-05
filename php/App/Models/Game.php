<?php

namespace Joc4enRatlla\Models;

use Joc4enRatlla\Exceptions\IllegalMoveException;
use Joc4enRatlla\Models\Board;
use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Logger\GameLogger;

/**
 * Class Game
 * 
 * Representa un juego de "4 en raya" con el tablero, el siguiente jugador, 
 * los jugadores, el ganador y el marcador.
 */
class Game
{

    /**
     * @var Logger El registro de eventos del juego.
     */ private GameLogger $logger;
    /**
     * @var Board La cuadrícula del juego.
     */
    private Board $board;

    /**
     * @var int Índice del siguiente jugador en turno.
     */
    private int $nextPlayer;

    /**
     * @var array Lista de jugadores.
     */
    private array $players;

    /**
     * @var Player|null El ganador del juego, si existe.
     */
    private ?Player $winner;

    /**
     * @var array Lista de puntuaciones de los jugadores.
     */
    private array $scores;

    /**
     * Constructor de la clase Game.
     *
     * @param Player $jugador1 El jugador 1.
     * @param Player $jugador2 El jugador 2.
     */
    public function __construct(Player $jugador1, Player $jugador2) {
        $this->board = new Board();
        $this->players = [1 => $jugador1, 2 => $jugador2]; 
        $this->nextPlayer = 1; 
        $this->winner = null;
        $this->scores = [1 => 0, 2 => 0];

        $this->logger = new GameLogger();
        
    }

    /**
     * Obtiene la cuadrícula del juego.
     *
     * @return Board Retorna un objeto de tipo Board.
     */
    public function getBoard(): Board {
        return $this->board;
    }

    /**
     * Obtiene la lista de jugadores.
     *
     * @return array La lista de jugadores.
     */
    public function getPlayers(): array {
        return $this->players;
    }

    /**
     * Obtiene el ganador del juego.
     *
     * @return Player|null Retorna el jugador ganador o null si aún no hay ganador.
     */
    public function getWinner(): ?Player {
        return $this->winner;
    }

    /**
     * Obtiene el marcador de las partidas.
     *
     * @return array Retorna la puntuación de los jugadores.
     */
    public function getScores(): array {
        return $this->scores;
    }

    /**
     * Reinicia la partida, reseteando el tablero y el estado del juego.
     *
     * @return void
     */
    public function reset(): void {
        $this->board = new Board(); 
        $this->nextPlayer = 1; 
        $this->winner = null; 
        $this->logger->logReset();
    }

    /**
     * Gestiona el flujo de la partida.
     *
     * @param int $columna El número de la columna donde se realiza el movimiento.
     * @return void
     * @throws IllegalMoveException Si el movimiento no es válido.
     */
    public function play(int $columna): void {
        try {
            if ($this->board->isValidMove($columna)) {
                $coord = $this->board->setMovementOnBoard($columna, $this->nextPlayer);

                $playerName = $this->players[$this->nextPlayer]->getName();
                $this->logger->logMove($playerName, $columna);

                if ($this->board->checkWin($coord)) {
                    $this->winner = $this->players[$this->nextPlayer];
                    $this->scores[$this->nextPlayer]++;
                    $this->logger->logWin($playerName); 
                }
                $this->nextPlayer = $this->nextPlayer === 1 ? 2 : 1; 
            } else {
                throw new IllegalMoveException('Movimiento no válido');
            }
        } catch (IllegalMoveException $e) {
            $_SESSION['error'] = "Movimiento no válido: " . $e->getMessage();
            $this->logger->logError("Illegal move attempt: " . $e->getMessage());
        }
    }

    /**
     * Realiza un movimiento automático para el jugador de IA.
     *
     * @return void
     */
    public function playAutomatic(): void {
        $opponent = $this->nextPlayer === 1 ? 2 : 1;

        // Verifica si hay un movimiento ganador para el jugador actual
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

        // Verifica si el oponente tiene un movimiento ganador y lo bloquea
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

        // Elige un movimiento aleatorio si no hay movimientos ganadores
        $possibles = [];
        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            if ($this->board->isValidMove($col)) {
                $possibles[] = $col;
            }
        }

        if (!empty($possibles)) {
            $randomIndex = array_rand($possibles);
            $this->play($possibles[$randomIndex]);
        }
    }

    /**
     * Guarda los atributos del juego en la sesión.
     *
     * @return void
     */
    public function save(): void {
        $_SESSION['board'] = serialize($this->board); 
        $_SESSION['nextPlayer'] = $this->nextPlayer;
        $_SESSION['players'] = serialize($this->players);
        $_SESSION['winner'] = $this->winner ? serialize($this->winner) : null; 
        $_SESSION['scores'] = $this->scores;
    }

    /**
     * Restaura los datos de la partida anterior.
     *
     * @return Game|null Retorna un objeto Game si existen datos guardados, null si no.
     */
    public static function restore(): ?Game
    {
        if (isset($_SESSION['game'])) {
            return unserialize($_SESSION['game']);
        }
        return null;
    }
}
?>
