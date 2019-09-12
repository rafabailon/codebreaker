<?php

namespace Game\Codebreaker\Object;

use Game\Codebreaker\Config;

class SecretCode
{
    public const PLUS = '+';
    public const MINUS = '-';
    public const CHANCES = 10;
    public const SIZE = 4;
    public const MIN_DIGIT = 1;
    public const MAX_DIGIT = 6;

    private $secretCode;
    private $countSecretCode;
    private $uniqueSecretCode;
    private $currentResult;
    private $currentRound = 1;
    private $victory = false;

    public function __construct(array $code = null)
    {
        if (is_null($code)) {
            $this->secretCode = $this->generate();
            $this->init();
        } else {
            $this->secretCode = $code;
        }
    }

    public function generate() : array
    {
        $random = [];
        for ($i = 0; $i < self::SIZE; $i++) {
            $random[] = rand(self::MIN_DIGIT, self::MAX_DIGIT);
        }
        return $random;
    }

    private function init() : void
    {
        $this->countSecretCode = array_count_values($this->secretCode);
        $this->uniqueSecretCode = array_unique($this->secretCode);
    }

    public function getCurrentRound() : int
    {
        return $this->currentRound;
    }

    public function getSecretCode() : int
    {
        return (int) implode('', $this->secretCode);
    }

    public function canPlay() : bool
    {
        return ($this->currentRound <= self::CHANCES && $this->victory === false);
    }

    public function validateNumber($user) : bool
    {
        $flag = true;
        if (is_numeric($user) && strlen($user) === self::SIZE) {
            $numberMatrix = str_split($user);
            foreach ($numberMatrix as $value) {
                if ($value < self::MIN_DIGIT || $value > self::MAX_DIGIT) {
                    $flag = false;
                    break;
                }
            }
        } else{
            $flag = false;
        }

        return $flag;
    }

    public function isVictory() : bool
    {
        return $this->victory;
    }

    public function check($userNumber) : array
    {
        if ($this->canPlay() && $this->validateNumber($userNumber)) {
            $userMatrix = str_split($userNumber);
            $allMatches = count(array_intersect($this->secretCode, $userMatrix));
            $exactMatches = count(array_intersect_assoc($this->secretCode, $userMatrix));
            $countUserNumber = array_count_values($userMatrix);
            foreach ($this->uniqueSecretCode as $value) {
                if (array_key_exists($value, $countUserNumber) && $countUserNumber[$value] < $this->countSecretCode[$value]) {
                    $allMatches -= ($this->countSecretCode[$value] - $countUserNumber[$value]);
                }
            }
            $minusMatches = $allMatches - $exactMatches;
            if (($exactMatches === 0 && $minusMatches) || (($exactMatches+$minusMatches) !== self::SIZE) || $minusMatches !== 0) {
                $this->victory = false;
            } else {
                $this->victory = true;
            }
            $this->currentRound++;
            $this->currentResult = array($exactMatches, $minusMatches);
        } else {
            $this->currentResult = null;
        }

        return $this->currentResult;
    }
}
