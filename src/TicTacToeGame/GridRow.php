<?php

namespace App\TicTacToeGame;

class GridRow
{
    /** @var Square[] */
    private $row;

    /**
     * GridRow constructor.
     */
    public function __construct()
    {
        for ($i = 0; $i < Grid::GRID_COLUMNS; $i++) {
            $this->row[] = new Square();
        }
    }

    public function setValue(int $column, ?string $symbol): void
    {
        try {
            $this->row[$column]->setValue($symbol);
        } catch (TicTacToeException $exception) {
        }
    }

    public function serialize(): array
    {
        $data = [];
        foreach ($this->row as $square){
            $data[] = $square->serialize();
        }

        return $data;
    }

    public function isAvailableColumn(int $column): bool
    {
        return $this->row[$column]->isAvailable();
    }
}
