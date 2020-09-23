<?php
final class RandomNumber{

	public $RandomNumbers = array();

	public function uRand($min = NULL, $max = NULL){
		$break='false';
		while($break=='false'){
			$rand = mt_rand($min,$max);
			if(array_search($rand,$this->RandomNumbers) === false){
				$this->RandomNumbers[] = $rand;
			$break = 'stop';
			}
		}
		return $rand;
	}

	public function clearrand(){
		$Empty = array();
		$this->RandomNumbers = $Empty;
	}

}
?>
