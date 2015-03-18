<?php

require_once("TextBook.php");

class TextBookController {
	private $db;
	function __construct($db){
		$this->db = $db;
	}

	public function fetchAllTextBook() {
		$query = $this->db->prepare('SELECT * FROM book;');
		$query->execute();

		$books = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$books[] = new TextBook($row['id'], 
									$row['title'], 
									$row['module'], 
									$row['price'], 
									$row['condition'], 
									$row['availability']
								);
		}
		return $books;
	}
}

?>