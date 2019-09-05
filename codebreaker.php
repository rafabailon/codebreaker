<?php

	class secretCode {

		const CHANCES = 10;
		const SIZE = 4;

		const PLUS = '+';
		const MINUS = '-';

		const MIN_DIGIT = 1;
		const MAX_DIGIT = 6;

		const TEXT_ERROR = "You have not hit any digit";
		const TEXT_END = "The game is over";

		private $secretCode, $countSecretCode, $uniqueSecretCode;
		private $currentRound = 1, $currentResult = '', $victory = false;

		public function __construct(){
			$this->secretCode = $this->generate();
			$this->setValues();
		}

		private function setValues(){
			$this->countSecretCode = array_count_values($this->secretCode);
			$this->uniqueSecretCode = array_unique($this->secretCode);
		}

		public function getSize(){
			return self::SIZE;
		}

		public function generate(){
			$random = array();
			for ($i = 0; $i < self::SIZE; $i++) {
				$random[] = rand(self::MIN_DIGIT, self::MAX_DIGIT);
			}
			return $random;
		}

		public function check($userNumber){
			if($this->canPlay()){
				$userMatrix = str_split($userNumber);
				$allMatches = count(array_intersect($this->secretCode, $userMatrix));
				$exactMatches = count(array_intersect_assoc($this->secretCode, $userMatrix));
				$countUserNumber = array_count_values($userMatrix);
				foreach($this->uniqueSecretCode as$value){
					if (array_key_exists($value, $countUserNumber) && $countUserNumber[$value] < $this->countSecretCode[$value]) {
						$allMatches = $allMatches - ($this->countSecretCode[$value] - $countUserNumber[$value]);
					}
				}
				$minusMatches = $allMatches-$exactMatches;
				$compareResult = str_repeat(self::PLUS, $exactMatches) . str_repeat(self::MINUS, $minusMatches);
				if(empty($compareResult)){
					$this->currentResult = self::TEXT_ERROR;
					$this->victory = false;
				}else{
					if(strlen($compareResult) != self::SIZE){
						$this->currentResult = $compareResult;
						$this->victory = false;
					}else{
						$flag = true;
						$matrixResult = str_split($compareResult);
						for($i = 0; $i < self::SIZE; $i++){
							if($matrixResult[$i] == '-'){
								$this->currentResult = $compareResult;
								$this->victory = false;
								$flag = false;
							}
						}
						if($flag){
							$this->currentResult = $compareResult;
							$this->victory = true;
						}
					}
				}
				$this->currentRound++;
			}else{
				$this->currentResult = self::TEXT_END;
			}
			return $this->currentResult;
		}

		public function setSecretNumber($secretNumber){
			$this->secretCode = $secretNumber;
			$this->setValues();
		}

		public function validateNumber($userNumber){
			$flag = false;
			if(is_numeric($userNumber)){
				$flag = strlen($userNumber) == self::SIZE;
			}else if(strcasecmp($userNumber, 'q') == 0){
				exit();
			}
			return $flag;
		}

		public function isVictory(){
			return $this->victory;
		}

		public function canPlay(){
			return ($this->currentRound <= self::CHANCES && $this->victory == false);
		}

		public function getSizeNumber(){
			return self::SIZE;
		}

		public function getCurrentRound(){
			return $this->currentRound;
		}

	}

	$code = new secretCode();
	
	# Examples
	############################################
	 $code->setSecretNumber(array(1, 2, 3, 4));
	# $code->setSecretNumber(array(3, 4, 3, 5));
	# $code->setSecretNumber(array(4, 3, 4, 2));
	# $code->setSecretNumber(array(2, 3, 1, 4));

	$userNumber = 0;
	$sizeDigit = $code->getSizeNumber();

	while($code->canPlay()){
		$currentRound = $code->getCurrentRound();
		echo "Enter a number ($sizeDigit digits) (Round $currentRound): ";
		fscanf(STDIN, "%[^\\n]", $userNumber);
		$userNumber = trim($userNumber);
		if(!$code->validateNumber($userNumber)){
			echo "Invalid number \n";
		}else{
			$result = $code->check($userNumber);
			echo "$result \n";
		}
	}

	if($code->isVictory()){
		echo "Congratulations, you have won! \n";
	}else{
		echo "Sorry, you have lost! \n";
	}

?>
