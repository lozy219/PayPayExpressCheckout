<?php

class TextBook {
	var $id;
	var $title;
	var $module_code;
	var $price;
	var $condition;

	function __construct($id, $title, $module_code, $price, $condition = 10){
		$this->id          = $id;
		$this->title       = $title;
		$this->module_code = $module_code;
		$this->price       = $price;
		$this->condition   = $condition;
	}

}

?>