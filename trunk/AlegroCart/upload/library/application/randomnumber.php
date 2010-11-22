<?php
class RandomNumber{
	var $RandomNumbers = array();

    function uRand($min = NULL, $max = NULL){
      $break='false';
      while($break=='false'){
	    srand(time());  
        $rand=mt_rand($min,$max);
        if(array_search($rand,$this->RandomNumbers)===false){
          $this->RandomNumbers[]=$rand;
          $break='stop';
        } 
      }
    return $rand;
    }
	function clearrand(){
      $Empty = array();
      $this->RandomNumbers = $Empty;
    }
}
?>