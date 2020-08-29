<?php

namespace App\TicTacToeGame;

class CPUMoveGenerator
{

    private $h;
/*    public function generate(Grid $grid): ChangeAction
    {
        $counter = 0;
        do {
            $row = rand(0, 2);
            $column = rand(0, 2);
            $counter++;

        } while (!$grid->isAvailableSquare($row, $column) && $counter < 1000);

        return new ChangeAction($row, $column, 'O');
    }*/

    public function generate(Grid $grid): ChangeAction
    {
        $data = $grid->serialize();

        $bestMove = [];
        $bestValue = -1000;

        $this->h = fopen('a.txt', 'w');
        fputs($this->h, "Started\n");

        for ($i = 0; $i < Grid::GRID_COLUMNS; $i++) {
            for ($j = 0; $j < Grid::GRID_ROWS; $j++) {
                if (empty($data[$i][$j])) {
                    // make the move
                    $data[$i][$j] = 'O';
                    // compute evaluation function for this move.
                    $moveValue = $this->miniMax($data, 1, true);
                    // undo the move
                    $data[$i][$j] = '';
                    // if the value of the current move is bigger than the bestValue update bestValue
                    if ($moveValue > $bestValue) {
                        $bestValue = $moveValue;
                        $bestMove = [$i, $j];
                    }
                }
            }
        }

        fputs($this->h, "Finished\n");
        fclose($this->h);

        return new ChangeAction($bestMove[0], $bestMove[1], 'O');
    }

    private function write(string $message): void
    {
        fputs($this->h, $message . "\n");
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
        $this->write("e: $level" . ($isBotTeam ? "cpu" : "player"));

        // if not moves left returns draw
        if (StateChecker::playerWon($data)) {
            $this->write("Player won");
            return -1;
        }

        if (StateChecker::cpuWon($data)) {
            $this->write("CPU won");
            return 1;
        }

        if (StateChecker::noMoreMoves($data) && StateChecker::hasAnyoneWon($data)) {
            $this->write('Draw');
            return 0;
        }

        // my team
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

            $this->write("Best cpu move: $bestMove");
            return $bestMove;
        } else { // opponent team
            $bestMove = 1;
            for ($i = 0; $i < Grid::GRID_ROWS; $i++) {
                for ($j = 0; $j < Grid::GRID_COLUMNS; $j++) {
                    if ($data[$i][$j] == '') {
                        // make the move
                        $data[$i][$j] = 'O';
                        $score = $this->miniMax($data, $level + 1, !$isBotTeam);
                        // call minimax recursively and choose the minimum value
                        $bestMove = min($score, $bestMove);
                        // undo the move
                        $data[$i][$j] = '';
                    }
                }
            }

            $this->write("Best player move: $bestMove");
            return $bestMove;
        }
    }
}
