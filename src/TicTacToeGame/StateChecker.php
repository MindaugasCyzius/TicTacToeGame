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

/*    private static $winScenarios = [
        // Rows
        [[0,0], [0,1]],
        [[1,0], [1,1]],
        // Columns
        [[0,0], [1,0]],
        [[0,1], [1,1]],
        // Diagonal
        [[0,0], [1,1]],
        [[0,1], [1,0]],
    ];*/

    public static function hasAnyoneWon(array $grid): bool
    {
        if (self::check($grid, 'X') || self::check($grid, 'O')) {
            return true;
        } else {
            return false;
        }
    }

    public static function playerWon(array $data): bool
    {
        return self::check($data, 'X');
    }

    public static function cpuWon(array $data): bool
    {
        return self::check($data, 'O');
    }

    public static function hasPlayerWon(Grid $grid): bool
    {
        $data = $grid->serialize();
        return self::check($data, 'X');
    }

    public static function hasCPUWon(Grid $grid): bool
    {
        $data = $grid->serialize();
        return self::check($data, 'O');
    }

    /**
     * Check if there is any available position.
     * @param array $data
     * @return bool
     */
    public static function noMoreMoves(array $data)
    {
        $availablePositions = array_filter($data, function ($position) {
            return in_array("", $position);
        });
        return !$availablePositions;
    }

    private static function check(Array $data, string $symbol): bool
    {
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
