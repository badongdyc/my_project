<?php
	function getTime(){
		return date('Y-m-d H:i:s',time());
	}

	function format(&$item){
		if($item>=1000&&$item<1000000){
			$item=number_format($item/1000,1).'k';
		}else if($item>=1000000){
			$item=number_format($item/1000000,1).'m';
		}
	}
?>