<?php
class UtilHelper extends AppHelper{
    public function calDate($start){
       // echo $start; 
       // echo $end;
    $now = date("Y-m-d H:i:s");
        $diff = floor((strtotime($now)- strtotime($start))/24/3600); 
        return  $diff;
    }

    public function changeStringHasKeyword($string, $keyword_r){
    	foreach ($keyword_r as $value) {
    		$string = str_replace($value, "<b style='color:red'>".$value."</b>", $string);
    	}
    	return $string; 
    }
}
