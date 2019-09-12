<?php

	namespace Game\CodeBreaker\View;

    use Game\Codebreaker\Config;

	class Console
	{
		public function initGame(): void
		{
			fwrite(STDOUT, "Codebreaker started! Starts the game.\n");
			fwrite(STDOUT, "To exit the game press 'q'.\n\n");
		}

		public function read(int $currentRound) : string
		{
		    $size = Config::SIZE;
			fwrite(STDOUT, "Enter a number ($size digits) (Round $currentRound): ");
            $response = trim(fgets(STDIN));
			return $response;
		}

		public function invalidNumber() : void
		{
		    $size = Config::SIZE;
		    $min = Config::MIN_DIGIT;
		    $max = Config::MAX_DIGIT;
			fwrite(STDOUT, "Invalid number. A valid code has $size digits and numbers from $min to $max \n");
		}

		public function numberMatches(int $exactMatches, int $allMatches) : void
		{
		    $plus= str_repeat(Config::PLUS, $exactMatches);
		    $min = str_repeat(Config::MINUS, $allMatches);
			fwrite(STDOUT, "Result: $plus$min \n");
		}

		public function exitGame() : bool
		{
			fwrite(STDOUT, "\nAre you sure you want to exit the game? (y/n): ");
            $response = trim(fgets(STDIN));
			return $response == 'y';
		}

		public function endGame(bool $victoryCond, int $secretNum, int $chancesNum): void
		{
			if ($victoryCond) {
				fwrite(STDOUT, "\nCongratulations, you have won! \n");
				fwrite(STDOUT, "You guessed the secret number $secretNum in $chancesNum attempts \n");
			} else {
				fwrite(STDOUT, "\nSorry, you have lost!\n");
				fwrite(STDOUT, "The secret code is $secretNum \n");
			}
		}
	}
