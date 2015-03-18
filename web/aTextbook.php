<?php

class Textbook {
	var $id;
	var $title;
	var $module_code;
	var $price;
	var $condition;
	var $availability;

	function __construct($id, $title, $module_code, $price, $condition, $availability) {
		$this->id           = $id;
		$this->title        = $title;
		$this->module_code  = $module_code;
		$this->price        = $price;
		$this->condition    = $condition;
		$this->availability = $availability;
	}

}

?>