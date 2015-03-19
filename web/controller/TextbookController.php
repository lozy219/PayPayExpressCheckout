<?php

require_once("model/Textbook.php");

/**
 * Textbook Controller
 * interact with database
 */
class TextbookController {
    private static $db = false;

    public static function establish($db) {
        if (!(TextbookController::$db)) {
            TextbookController::$db = $db;
        }
    }

    // get all the textbook including the sold ones
    public static function fetchAllTextbook() {
        // fake data for testing
        // return array(
        //         new Textbook(1, 'Introduction to Algorithms', 'CS3230', 35, 8, '1'),
        //         new Textbook(2, 'Interaction Design', 'CS3240', 55, 9.5, '1'),
        //         new Textbook(3, 'Database Management', 'CS2102', 15, 6, '1'),
        //         new Textbook(4, 'Database Management', 'CS2102', 20, 8.5, '0'),
        //         new Textbook(5, 'Japanese II', 'LAJ2201', 25, 8, '1'),
        //         new Textbook(6, 'Expensive Book', 'CS5241', 235, 5, '1'),
        //     );

        // fetch all the book
        $query = TextbookController::$db->prepare('SELECT * FROM book ORDER BY id');
        $query->execute();

        $books = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $books[] = new Textbook($row['id'], 
                                    $row['title'], 
                                    $row['module'], 
                                    $row['price'], 
                                    $row['condition'], 
                                    $row['availability']
                                );
        }
        return $books;
    }

    // mark a group of textbook as sold
    public static function markSold($book_ids) {
        foreach ($book_ids as $id) {
            $query = TextbookController::$db->prepare("UPDATE book SET availability='0' WHERE id=" . $id);
            $query->execute();
        }
    }
}

?>