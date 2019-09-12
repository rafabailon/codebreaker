<?php

namespace Game\Codebreaker;

use Game\Codebreaker\Object\SecretCode;
use Game\Codebreaker\View\Console;

class CodeBreaker
{
    public function exec(SecretCode $game) : void
    {
        $view = new Console();
        $view->initGame();
        while ($game->canPlay()) {
            $userNumber = $view->read($game->getCurrentRound());
            if (strcasecmp($userNumber, 'q') === 0) {
                if ($view->exitGame()) {
                    break 1;
                }
            } else if (!$game->validateNumber($userNumber)) {
                $view->invalidNumber();
            } else {
                $result = $game->check($userNumber);
                $view->numberMatches($result[0], $result[1]);
            }
        }
        $view->endGame($game);
    }
}
