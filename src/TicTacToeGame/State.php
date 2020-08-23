<?php


namespace App\TicTacToeGame;

class State
{
    /** @var Grid */
    private $grid;

    /**
     * Grid constructor.
     * @param array $gameState
     * @throws TicTacToeException
     */
    public function __construct(?array $gameState)
    {
        $this->grid = new Grid();

        if ($gameState) {
            foreach ($gameState as $rowKey => $row) {
                foreach ($row as $columnKey => $value) {
                    $this->grid->setValue($rowKey, $columnKey, $value);
                }
            }
        }
    }

    public function change(ChangeAction $action)
    {
        $row = $action->getRow();
        $column = $action->getColumn();
        $symbol = $action->getSymbol();

        try {
            $this->grid->setValue($row, $column, $symbol);
        } catch (TicTacToeException $exception) {
        }
    }

    public function getGrid(): Grid
    {
        return $this->grid;
    }

    public function serialize(): array
    {
        return $this->grid->serialize();
    }
}
