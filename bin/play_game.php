#!/usr/bin/env php

<?php

	use Game\Codebreaker\Object\SecretCode;require __DIR__ . '/../vendor/autoload.php';

    # Examples
    ############################################
    # $game->exec(new SecretCode(array(1, 2, 3, 4)));
    # $game->exec(new SecretCode(array(3, 4, 3, 5)));
    # $game->exec(new SecretCode(array(4, 3, 4, 2)));
    # $game->exec(new SecretCode(array(2, 3, 1, 4)));

	$game = new Game\Codebreaker\CodeBreaker();
	$game->exec(new SecretCode());

?>
