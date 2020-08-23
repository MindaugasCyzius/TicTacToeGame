<?php

namespace App\TicTacToeGame;

class ChangeAction
{
    /** @var int */
    private $row;
    /** @var int */
    private $column;
    /** @var string */
    private $symbol;

    /**
     * ChangeAction constructor.
     * @param int $row
     * @param int $column
     * @param string $symbol
     */
    public function __construct(int $row, int $column, string $symbol)
    {
        $this->row = $row;
        $this->column = $column;
        $this->symbol = $symbol;
    }

    /**
     * @return int
     */
    public function getRow(): int
    {
        return $this->row;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }
}
