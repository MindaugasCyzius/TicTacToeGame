<?php

namespace App\Controller;

use App\TicTacToeGame\CPUMoveGenerator;
use App\TicTacToeGame\State;
use App\TicTacToeGame\ChangeAction;
use App\TicTacToeGame\StateChecker;
use App\TicTacToeGame\TicTacToeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    const USER_VALUE = 'X';
    const USER_WON_MESSAGE = 'Player won!';
    const CPU_WON_MESSAGE = 'CPU won!';
    const DRAW_MESSAGE = 'It is a draw!';

    /**
     * @Route("/")
     * @param Request $request
     * @return Response
     * @throws TicTacToeException
     */
    public function index(Request $request): Response
    {
        /** @var Session $session */
        $session = $this->get('session');
        
        $gameState = $session->get('gameState', null);
        $state = new State($gameState);

        if ($request->isMethod(Request::METHOD_POST)) {
            $position = $request->request->get('pos');
            $row = substr($position, 0,1);
            $column = substr($position, 1,1);
            $changeAction = new ChangeAction($row, $column,self::USER_VALUE);

            try {
                $state->change($changeAction);
            } catch (TicTacToeException $exception) {
                $errorMessage = $exception->getMessage();
            }

            if (StateChecker::hasValidMoves($state->getGrid()) > 0) {
                $cpuMoveGenerator = new CPUMoveGenerator();
                $state->change($cpuMoveGenerator->generate($state->getGrid()));
            }

            $session->set('gameState', $state->serialize());
        }

        if (StateChecker::hasPlayerWon($state->getGrid())) {
            $message = self::USER_WON_MESSAGE;
        }
        if (StateChecker::hasCPUWon($state->getGrid())) {
            $message = self::CPU_WON_MESSAGE;
        }
        if (StateChecker::hasValidMoves($state->getGrid()) == 0) {
            $message = self::DRAW_MESSAGE;
        }

        return $this -> render(
            'game/game.html.twig',
            [
                'grid' => $state->serialize(),
                'message' => $message ?? null,
                'errorMessage' => $errorMessage ?? null,
                'userWinMessage' => self::USER_WON_MESSAGE,
                'cpuWinMessage' => self::CPU_WON_MESSAGE,
                'drawMessage' => self::DRAW_MESSAGE,
            ]
        );
    }

    /**
     * @Route("/restart")
     * @return Response
     */
    public function restart(): Response
    {
        $this->get('session')->set('gameState', null);

        return $this->redirect('/');
    }
}
