<?php

	class secretCode {
		const PLUS = '+';
		const MINUS = '-';

		const MINDIGIT = 1;
		const MAXDIGIT = 6;
		const SIZE = 4;

		private $randomNumber;

		public function __construct(){
			$this->randomNumber = $this->generate();
		}

		public function getSize(){
			return self::SIZE;
		}

		public function generate(){
			$random = array();
			for ($i = 0; $i < self::SIZE; $i++) {
				$random[] = rand(self::MINDIGIT, self::MAXDIGIT);
			}
			return $random;
		}

		public function check($cbNumber){
			$matrixNumber = str_split($cbNumber);
			$allMatches = count(array_intersect($this->randomNumber, $matrixNumber));
			$exactMatches = count(array_intersect_assoc($this->randomNumber, $matrixNumber));
			$countRandomNumber = array_count_values($this->randomNumber);
			$countMatrixNumber = array_count_values($matrixNumber);
			$uniqueRandomNumber = array_unique($this->randomNumber);
			foreach ($uniqueRandomNumber as $value) {
				if (array_key_exists($value, $countMatrixNumber) && $countMatrixNumber[$value] < $countRandomNumber[$value]) {
					$diff = $countRandomNumber[$value] - $countMatrixNumber[$value];
					if($diff > 0){
						$allMatches = $allMatches - $diff;
					}
				}
			}
			$minusMatches = $allMatches-$exactMatches;
			if($minusMatches < 0){
				$minusMatches = 0;
			}
			$compareResult = str_repeat(self::PLUS, $exactMatches) . str_repeat(self::MINUS, $minusMatches);
			return $compareResult;
		}

		public function setSecretNumber($secretNumber){
			$this->randomNumber = $secretNumber;
		}
	}

	const CHANCES = 10;

	$code = new secretCode();
	
	# Examples
	############################################
	# $code->setSecretNumber(array(1, 2, 3, 4));
	# $code->setSecretNumber(array(3, 4, 3, 5));
	# $code->setSecretNumber(array(4, 3, 4, 2));
	# $code->setSecretNumber(array(2, 3, 1, 4));
	
	$stdin = fopen('php://stdin', 'r');

	$flag = false;
	$round = 1;
	$userNumber = 0;

	$sizeNumber = $code->getSize();

	while($round <= CHANCES && !$flag){
		echo "Enter a number ($sizeNumber digits) (Round $round): ";
		fscanf(STDIN, "%d\n", $userNumber);
		if(strlen($userNumber) != $sizeNumber){
			echo "Invalid number \n";
		}else{
			$result = $code->check($userNumber);
			if(strlen($result) != $sizeNumber){
				if(empty($result)){
					echo "You have not hit any digit\n";
				}else{
					echo $result."\n";
				}
			}else{
				$matrixResult = str_split($result);
				$locateMinus = false;
				for($i = 0; $i < $sizeNumber; $i++){
					if($matrixResult[$i] == '-'){
						$locateMinus = true;
						break;
					}
				}
				if($locateMinus){
					echo $result."\n";
				}else{
					$flag = true;
					echo $result."\n";
				}
			}
			$round++;
		}
	}

	fclose($stdin);

	if($flag){
		echo 'Congratulations, you have won!';
	}else{
		echo 'Sorry, you have lost!';
	}

?>
