<?php

class TextBook {
	var $title;
	var $module_code;
	var $price;
	var $condition;

	function __construct($title, $module_code, $price, $condition = 10){
		$this->title       = $title;
		$this->module_code = $module_code;
		$this->price       = $price;
		$this->condition   = $condition;
	}

}

?>