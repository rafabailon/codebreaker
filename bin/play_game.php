#!/usr/bin/env php

<?php

	require __DIR__ . '/../vendor/autoload.php';

    # Examples
    ############################################
    # $code->setSecretNumber(array(1, 2, 3, 4));
    # $code->setSecretNumber(array(3, 4, 3, 5));
    # $code->setSecretNumber(array(4, 3, 4, 2));
    # $code->setSecretNumber(array(2, 3, 1, 4));

	$game = new Game\Codebreaker\CodeBreaker();
	$game->exec();

?>
