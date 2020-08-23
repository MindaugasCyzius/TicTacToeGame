<?php

namespace App\TicTacToeGame;

class StateChecker
{
    private static $winScenarios = [
        // Rows
        [[0,0], [0,1], [0,2]],
        [[1,0], [1,1], [1,2]],
        [[2,0], [2,1], [2,2]],
        // Columns
        [[0,0], [1,0], [2,0]],
        [[0,1], [1,1], [2,1]],
        [[0,2], [1,2], [2,2]],
        // Diagonal
        [[0,0], [1,1], [2,2]],
        [[2,0], [1,1], [0,2]],
    ];

    public static function hasPlayerWon(Grid $grid): bool
    {
        return self::check($grid, 'X');
    }

    public static function hasCPUWon(Grid $grid): bool
    {
        return self::check($grid, 'O');
    }

    public static function hasValidMoves(Grid $grid): int
    {
        $data = $grid->serialize();
        foreach ($data as $key => $row) {
            $data[$key] = array_reduce(
                $row,
                function ($sum, $item) {
                    return $sum += $item ? 1 : 0;
                },
                0
            );
        }

        $total = array_reduce(
            $data,
            function ($sum, $item) {
                return $sum += $item ? $item : 0;
            },
            0
        );

        return Grid::GRID_ROWS * Grid::GRID_COLUMNS - $total;
    }

    private static function check(Grid $grid, string $symbol): bool
    {
       $data = $grid->serialize();

       foreach (self::$winScenarios as $winScenario) {
           $counter = 0;
           foreach ($winScenario as $position) {
                $row = $position[0];
                $column = $position[1];

                if ($data[$row][$column] == $symbol) {
                    $counter++;

                    if ($counter == 3) {
                        return true;
                    }
                }
           }
       }

       return false;
    }
}
