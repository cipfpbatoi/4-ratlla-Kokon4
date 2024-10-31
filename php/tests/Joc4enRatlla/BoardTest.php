<?php

namespace Joc4enRatlla\Tests;

use Joc4enRatlla\Models\Board;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    private Board $board;

    protected function setUp(): void
    {
        // Se ejecuta antes de cada prueba
        $this->board = new Board();
    }

    public function testInitialBoardSetup()
    {
        // Verifica que la cuadrícula esté vacía al inicio
        $slots = $this->board->getSlots();
        for ($row = 1; $row <= Board::FILES; $row++) {
            for ($col = 1; $col <= Board::COLUMNS; $col++) {
                $this->assertEquals(0, $slots[$row][$col], "El slot [$row][$col] no está vacío");
            }
        }
    }

    public function testSetMovementOnBoard()
    {
        // Realiza un movimiento y verifica que se aplique correctamente
        $this->board->setMovementOnBoard(1, 1); // Jugador 1 coloca en la columna 1
        $this->assertEquals(1, $this->board->getSlots()[6][1]); // La fila 6 debe tener el jugador 1
    }

    public function testSetMovementOnFullColumn()
    {
        // Llena una columna y verifica que lanza excepción
        for ($i = 0; $i < Board::FILES; $i++) {
            $this->board->setMovementOnBoard(1, 1);
        }
        
        $this->expectException(\Exception::class);
        $this->board->setMovementOnBoard(1, 2); // Jugador 2 intenta jugar en una columna llena
    }

    public function testCheckWinHorizontal()
    {
        // Realiza un movimiento para crear una línea horizontal y verifica la victoria
        $this->board->setMovementOnBoard(1, 1);
        $this->board->setMovementOnBoard(2, 1);
        $this->board->setMovementOnBoard(3, 1);
        $this->board->setMovementOnBoard(4, 1);

        $this->assertTrue($this->board->checkWin([6, 4]), "Debería haber un ganador horizontal");
    }

    public function testCheckWinVertical()
    {
        // Realiza un movimiento para crear una línea vertical y verifica la victoria
        $this->board->setMovementOnBoard(1, 1);
        $this->board->setMovementOnBoard(1, 1);
        $this->board->setMovementOnBoard(1, 1);
        $this->board->setMovementOnBoard(1, 1);

        $this->assertTrue($this->board->checkWin([3, 1]), "Debería haber un ganador vertical");
    }

    public function testCheckWinDiagonal()
    {
        // Realiza un movimiento para crear una línea diagonal y verifica la victoria
        $this->board->setMovementOnBoard(1, 1);
        $this->board->setMovementOnBoard(2, 2);
        $this->board->setMovementOnBoard(3, 1);
        $this->board->setMovementOnBoard(3, 2);
        $this->board->setMovementOnBoard(4, 1);
        $this->board->setMovementOnBoard(4, 2);
        $this->board->setMovementOnBoard(4, 1); // Movimiento diagonal ganadora

        $this->assertTrue($this->board->checkWin([4, 1]), "Debería haber un ganador diagonal");
    }

    public function testIsValidMove()
    {
        // Verifica si un movimiento es válido
        $this->assertTrue($this->board->isValidMove(1), "Debería ser un movimiento válido");
        $this->board->setMovementOnBoard(1, 1);
        $this->assertFalse($this->board->isValidMove(1), "Debería ser un movimiento no válido");
    }

    public function testIsFull()
    {
        // Llena el tablero y verifica que esté lleno
        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            for ($row = 1; $row <= Board::FILES; $row++) {
                $this->board->setMovementOnBoard($col, 1);
            }
        }
        $this->assertTrue($this->board->isFull(), "El tablero debería estar lleno");
    }
}
