<?php

require_once("TextBook.php");

class TextBookController {
	private $db;
	// function __construct($db){
	// 	$this->db = $db;
	// }

	function __construct(){
	
	}

	public function fetchAllTextBook() {
		return array(
				new TextBook(1, 'Introduction to Algorithms', 'CS3230', 35, 8, '1'),
				new TextBook(2, 'Interaction Design', 'CS3240', 55, 9.5, '1'),
				new TextBook(3, 'Database Management', 'CS2102', 15, 6, '1'),
				new TextBook(4, 'Database Management', 'CS2102', 20, 8.5, '0'),
				new TextBook(5, 'Japanese II', 'LAJ2201', 25, 8, '1'),
				new TextBook(6, 'Expensive Book', 'CS5241', 235, 5, '1'),
			);

		// $query = $this->db->prepare('SELECT * FROM book;');
		// $query->execute();

		// $books = array();
		// while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
		// 	$books[] = new TextBook($row['id'], 
		// 							$row['title'], 
		// 							$row['module'], 
		// 							$row['price'], 
		// 							$row['condition'], 
		// 							$row['availability']
		// 						);
		// }
		// return $books;
	}

}

?>