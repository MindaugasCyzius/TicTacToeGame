<?php

namespace App\TicTacToeGame;

class CPUMoveGenerator
{
    public function generate(Grid $grid): ChangeAction
    {
        $data = $grid->serialize();

        $bestMove = [];
        $bestValue = 0;

        //win game
        for ($i = 0; $i < Grid::GRID_COLUMNS; $i++) {
            for ($j = 0; $j < Grid::GRID_ROWS; $j++) {
                if ($data[$i][$j] == '') {
                    $data[$i][$j] = 'O';
                    if (StateChecker::cpuWon($data)) {
                        return new ChangeAction($i, $j, 'O');
                    }
                    $data[$i][$j] = '';
                }
            }
        }

        // Prevent losing action
        for ($i = 0; $i < Grid::GRID_COLUMNS; $i++) {
            for ($j = 0; $j < Grid::GRID_ROWS; $j++) {
                if ($data[$i][$j] == '') {
                    $data[$i][$j] = 'X';
                    if (StateChecker::playerWon($data)) {
                        return new ChangeAction($i, $j, 'O');
                    }
                    $data[$i][$j] = '';
                }
            }
        }

        // Take best position
        if ($data[1][1] == '') {
            return new ChangeAction(1,1, 'O');
        }

        //Initialize algorithm for other cases
        for ($i = 0; $i < Grid::GRID_COLUMNS; $i++) {
            for ($j = 0; $j < Grid::GRID_ROWS; $j++) {
                if ($data[$i][$j] == '') {
                    // make the move
                    $data[$i][$j] = 'O';
                    // compute evaluation function for this move.
                    $moveValue = $this->miniMax($data, 1, true);
                    // undo the move
                    $data[$i][$j] = '';
                    if ($moveValue >= $bestValue) {
                        $bestValue = $moveValue;
                        $bestMove = [$i, $j];
                    }
                }
            }
        }

        return new ChangeAction($bestMove[0], $bestMove[1], 'O');
    }

    /**
     * Implements minimax Algorithm in order to minimize the possible loss of the bot.
     * @param array $data
     * @param int $level
     * @param bool $isBotTeam
     * @return int
     */
    private function miniMax(array $data, int $level, bool $isBotTeam): int
    {
        if (StateChecker::playerWon($data)) {
            return -1;
        }

        if (StateChecker::cpuWon($data)) {
            return 1;
        }

        if (StateChecker::noMoreMoves($data) && StateChecker::hasAnyoneWon($data)) {
            return 0;
        }

        if ($isBotTeam) {
            $bestMove = -1;
            for ($i = 0; $i < Grid::GRID_ROWS; $i++) {
                for ($j = 0; $j < Grid::GRID_COLUMNS; $j++) {
                    if ($data[$i][$j] == '') {
                        // make the move
                        $data[$i][$j] = 'X';
                        $score = $this->miniMax($data, $level + 1, !$isBotTeam);
                        // call minimax recursively and choose the maximum value
                        $data[$i][$j] = '';
                        $bestMove = max($score, $bestMove);
                    }
                }
            }

            return $bestMove;
        } else {
            $bestMove = 1;
            for ($i = 0; $i < Grid::GRID_ROWS; $i++) {
                for ($j = 0; $j < Grid::GRID_COLUMNS; $j++) {
                    if ($data[$i][$j] == '') {
                        // make move
                        $data[$i][$j] = 'O';
                        $score = $this->miniMax($data, $level + 1, !$isBotTeam);
                        $bestMove = min($score, $bestMove);
                        // undo the move
                        $data[$i][$j] = '';
                    }
                }
            }

            return $bestMove;
        }
    }
}
