<?php

namespace App\TicTacToeGame;

class Grid
{
    public const GRID_ROWS = 3;
    public const GRID_COLUMNS = 3;

    /** @var GridRow[] */
    private $rows;

    /**
     * Grid constructor.
     */
    public function __construct()
    {
        for ($i = 0; $i < Grid::GRID_ROWS; $i++) {
            $this->rows[] = new GridRow();
        }
    }

    /**
     * @param int $row
     * @param int $column
     * @param string|null $symbol
     * @throws TicTacToeException
     */
    public function setValue(int $row, int $column, ?string $symbol): void
    {
        try {
            $this->rows[$row]->setValue($column, $symbol);
        } catch (TicTacToeException $exception) {
            throw new TicTacToeException("Failed to set value at row $row, column $column.", 0, $exception);
        }
    }

    public function serialize(): array
    {
        $data = [];

        foreach ($this->rows as $gridRow){
            $data[] = $gridRow->serialize();
        }

        return $data;
    }

    public function isAvailableSquare(int $row, int $column): bool
    {
        return $this->rows[$row]->isAvailableColumn($column);
    }
}
