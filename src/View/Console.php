<?php

namespace Game\CodeBreaker\View;

use Game\Codebreaker\Config;
use Game\Codebreaker\Object\SecretCode;

class Console
{
    public function initGame(): void
    {
        fwrite(STDOUT, "Codebreaker started! Starts the game.\n");
        fwrite(STDOUT, "To exit the game press 'q'.\n\n");
    }

    public function read(int $currentRound) : string
    {
        $size = SecretCode::SIZE;
        fwrite(STDOUT, "Enter a number ($size digits) (Round $currentRound): ");

        return trim(fgets(STDIN));
    }

    public function invalidNumber() : void
    {
        $size = SecretCode::SIZE;
        $min = SecretCode::MIN_DIGIT;
        $max = SecretCode::MAX_DIGIT;
        fwrite(STDOUT, "Invalid number. A valid code has $size digits and numbers from $min to $max \n");
    }

    public function numberMatches(int $exactMatches, int $allMatches) : void
    {
        $plus = str_repeat(SecretCode::PLUS, $exactMatches);
        $min = str_repeat(SecretCode::MINUS, $allMatches);
        fwrite(STDOUT, "Result: $plus$min \n");
    }

    public function exitGame() : bool
    {
        fwrite(STDOUT, "\nAre you sure you want to exit the game? (y/n): ");

        return trim(fgets(STDIN)) === 'y';
    }

    public function endGame(SecretCode $game): void
    {
        $secretNumber = $game->getSecretCode();
        $numberRound = $game->getCurrentRound();
        if ($game->isVictory()) {
            fwrite(STDOUT, "\nCongratulations, you have won! \n");
            fwrite(STDOUT, "You guessed the secret number $secretNumber in $numberRound attempts \n");
        } else {
            fwrite(STDOUT, "\nSorry, you have lost!\n");
            fwrite(STDOUT, "The secret code is $secretNumber \n");
        }
    }
}
