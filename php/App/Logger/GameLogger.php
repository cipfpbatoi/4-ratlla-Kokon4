<?php

namespace Joc4enRatlla\Logger; 

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class GameLogger
{
    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger('game');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/logs/game.log', Logger::INFO));
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/logs/error.log', Logger::ERROR));
    }

    public function logMove(string $playerName, int $column): void
    {
        $this->logger->info("El jugador $playerName ha fet un moviment a la columna $column.");
    }

    public function logWin(string $playerName): void
    {
        $this->logger->info("El jugador $playerName ha guanyat la partida!");
    }

    public function logError(string $message): void
    {
        $this->logger->error($message);
    }
    public function logReset(): void
    {
        $this->logger->info("The game has been reset.");
    }
}
