<?php

    namespace Game\Codebreaker\Object;

    use Game\Codebreaker\Config;

    class SecretCode
    {
        private $secretCode, $countSecretCode, $uniqueSecretCode, $currentResult;
        private $currentRound = 1;
        private $victory = false;

        public function __construct()
        {
            $i = func_num_args();
            if($i === 1){
                $this->setSecretNumber(func_get_args());
            }else{
                $this->secretCode = $this->generate();
                $this->setValues();
            }
        }

        public function generate() : array
        {
            $random = [];
            for ($i = 0; $i < Config::SIZE; $i++) {
                $random[] = rand(Config::MIN_DIGIT, Config::MAX_DIGIT);
            }
            return $random;
        }

        private function setValues() : void
        {
            $this->countSecretCode = array_count_values($this->secretCode);
            $this->uniqueSecretCode = array_unique($this->secretCode);
        }

        public function setSecretNumber($secretNumber) : void
        {
            $this->secretCode = $secretNumber;
            $this->setValues();
        }

        public function getCurrentRound() : int
        {
            return $this->currentRound;
        }

        public function getSecretCode() : int
        {
            $response = (int) implode('', $this->secretCode);
            return $response;
        }

        public function canPlay() : bool
        {
            return ($this->currentRound <= Config::CHANCES && $this->victory === false);
        }

        public function validateNumber($user) : bool
        {
            $flag = true;
            if(is_numeric($user) && strlen($user) === Config::SIZE) {
                $numberMatrix = str_split($user);
                foreach ($numberMatrix as $value) {
                    if ($value < Config::MIN_DIGIT || $value > Config::MAX_DIGIT) {
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
                if(($exactMatches === 0 && $minusMatches) || (($exactMatches+$minusMatches) !== Config::SIZE) || $minusMatches !== 0){
                    $this->victory = false;
                }else{
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
