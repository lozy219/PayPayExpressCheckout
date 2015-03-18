<?php

require_once("TextBook.php");

class TextBookController {
	private $app;
	function __construct($app){
		$this->app = $app;
	}

	public function fetchAllTextBook() {
		$query = $this->app['pdo']->prepare('SELECT * FROM book;');
		$query->execute();

		$books = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			// $app['monolog']->addDebug('Row ' . $row['id']);
			$books[] = $row;
		}
		print_r($books);
		die();
		return $books;
	}
}

?>