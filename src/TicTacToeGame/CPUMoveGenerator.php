<?php

namespace App\TicTacToeGame;

class CPUMoveGenerator
{
    public function generate(Grid $grid): ChangeAction
    {
        $counter = 0;
        do {
            $row = rand(0, 2);
            $column = rand(0, 2);
            $counter++;

        } while (!$grid->isAvailableSquare($row, $column) && $counter < 1000);

        return new ChangeAction($row, $column, 'O');
    }
}
