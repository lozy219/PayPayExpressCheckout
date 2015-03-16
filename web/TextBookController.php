<?php

require_once("TextBook.php");

class TextBookController {
	function __construct(){
			
	}

	public function fetchAllTextBook() {
		return array(
			new TextBook(
				"Introduction to Algorithms",
				"CS3230",
				"35",
				"9"
			),
			new TextBook(
				"Database Management",
				"CS2102",
				"15",
				"8"
			),
			new TextBook(
				"Introduction to Algorithms",
				"CS3230",
				"85",
				"10"
			),
			new TextBook(
				"骗钱大法",
				"CS3217",
				"200",
				"6"
			),
		);
	}
}

?>